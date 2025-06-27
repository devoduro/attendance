<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\LessonSchedule;
use App\Models\Subject;
use App\Models\AcademicRecord;
use App\Models\User;
use App\Models\House;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Initialize data array
        $data = [];
        
        // Role-specific data for the dashboard
        if ($user->hasRole('admin')) {
            // Admin sees all statistics
            $data['totalStudents'] = Student::where('status', 'active')->count();
            $data['totalLessons'] = LessonSchedule::where('is_active', true)->count();
            $data['activeExams'] = Exam::where('status', 'published')
                ->where(function($query) {
                    $now = Carbon::now();
                    $query->whereNull('end_time')
                        ->orWhere('end_time', '>=', $now);
                })->count();
        } elseif ($user->hasRole('teacher')) {
            // Teachers only see statistics for their assigned classes
            $teacher = $user->teacherProfile;
            if ($teacher) {
                // Get IDs of classes assigned to this teacher
                $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                
                // Count only students in teacher's classes
                $data['totalStudents'] = Student::whereIn('class_id', $teacherClassIds)
                    ->where('status', 'active')->count();
                    
                // Count only lessons assigned to this teacher
                $data['totalLessons'] = LessonSchedule::where('teacher_id', $teacher->id)
                    ->where('is_active', true)->count();
                
                // Count only exams for classes taught by this teacher
                $data['activeExams'] = Exam::where('status', 'published')
                    ->where(function($query) use ($teacherClassIds) {
                        $query->where('teacher_id', auth()->user()->teacherProfile->id)
                            ->orWhereHas('classes', function($q) use ($teacherClassIds) {
                                $q->whereIn('classes.id', $teacherClassIds);
                            });
                    })
                    ->where(function($query) {
                        $now = Carbon::now();
                        $query->whereNull('end_time')
                            ->orWhere('end_time', '>=', $now);
                    })->count();
            } else {
                // Default values if teacher profile not found
                $data['totalStudents'] = 0;
                $data['totalLessons'] = 0;
                $data['activeExams'] = 0;
            }
        } else {
            // Students see general statistics
            $data['totalStudents'] = Student::where('status', 'active')->count();
            $data['totalLessons'] = LessonSchedule::where('is_active', true)->count();
            // Since we don't have an exams table, just set activeExams to 0
            $data['activeExams'] = 0;
        }
        
        // Role-specific data
        if ($user->hasRole('admin')) {
            $this->addAdminData($data);
        } elseif ($user->hasRole('teacher')) {
            $this->addTeacherData($data, $user);
        } else {
            $this->addStudentData($data, $user);
        }
        
        return view('dashboard.index', $data);
    }
    
    /**
     * Add admin-specific data to the dashboard.
     *
     * @param array $data
     * @return void
     */
    private function addAdminData(&$data)
    {
        $data['totalTeachers'] = Teacher::where('status', 'active')->count();
        $data['totalSubjects'] = Subject::where('status', 'active')->count();
        
        // Recent system activity
        $data['recentActivity'] = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // System health data
        $data['storageUsage'] = $this->getStorageUsagePercentage();
        $data['lastBackup'] = $this->getLastBackupTime();
    }
    
    /**
     * Add teacher-specific data to the dashboard.
     *
     * @param array $data
     * @param User $user
     * @return void
     */
    private function addTeacherData(&$data, $user)
    {
        $teacher = $user->teacherProfile;
        
        if (!$teacher) {
            return;
        }
        
        // Get IDs of classes assigned to this teacher
        $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
        
        // Teacher's classes with student count
        $data['teacherClasses'] = $teacher->classes()
            ->where('status', 'active')
            ->withCount('students')
            ->get();
            
        // Students in teacher's classes
        $data['teacherStudents'] = Student::whereIn('class_id', $teacherClassIds)
            ->where('status', 'active')
            ->with('class', 'user')
            ->take(10)
            ->get();
            
        // Recent exams created by this teacher
        $data['recentExams'] = Exam::where('teacher_id', $teacher->id)
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Exams pending grading
        $data['pendingGrading'] = StudentExam::whereHas('exam', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('status', 'pending_grading')
            ->count();
    }
    
    /**
     * Add student-specific data to the dashboard.
     *
     * @param array $data
     * @param User $user
     * @return void
     */
    private function addStudentData(&$data, $user)
    {
        $student = $user->studentProfile;
        
        if (!$student) {
            return;
        }
        
        // Student's current class and program
        $data['currentClass'] = $student->class;
        
        // Get classmates (students in the same class)
        $data['classmates'] = Student::where('students.class_id', $student->class_id)
            ->where('students.id', '!=', $student->id) // Exclude the current student
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*')
            ->orderBy('users.name')
            ->take(10)
            ->with('user')
            ->get();
        
        $data['classmatesCount'] = Student::where('students.class_id', $student->class_id)
            ->where('students.id', '!=', $student->id)
            ->count();
        
        // Active exams available to this student
        $now = Carbon::now();
        $data['studentActiveExams'] = Exam::whereHas('classes', function($query) use ($student) {
                $query->where('class_id', $student->class_id);
            })
            ->where('status', 'published')
            ->where(function($query) use ($now) {
                $query->whereNull('start_time')
                    ->orWhere('start_time', '<=', $now);
            })
            ->where(function($query) use ($now) {
                $query->whereNull('end_time')
                    ->orWhere('end_time', '>=', $now);
            })
            ->whereDoesntHave('studentExams', function($query) use ($student) {
                $query->where('student_id', $student->id)
                    ->whereIn('status', ['completed', 'in_progress']);
            })
            ->with('subject')
            ->get();
            
        // Recent exam results
        $data['studentRecentResults'] = StudentExam::where('student_id', $student->id)
            ->where('status', 'completed')
            ->with('exam')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    }
    
    /**
     * Get storage usage as a percentage.
     *
     * @return string
     */
    private function getStorageUsagePercentage()
    {
        try {
            // Get disk free space and total space in bytes
            $diskFreeSpace = disk_free_space(storage_path());
            $diskTotalSpace = disk_total_space(storage_path());
            
            if ($diskTotalSpace > 0) {
                $usedSpace = $diskTotalSpace - $diskFreeSpace;
                $percentage = round(($usedSpace / $diskTotalSpace) * 100, 1);
                return $percentage . '%';
            }
        } catch (\Exception $e) {
            // Log the error but don't crash the dashboard
            \Log::error('Error calculating storage usage: ' . $e->getMessage());
        }
        
        return '0%';
    }
    
    /**
     * Get the time of the last backup.
     *
     * @return string
     */
    private function getLastBackupTime()
    {
        try {
            // Check if backups directory exists
            if (!Storage::disk('local')->exists('backups')) {
                return 'Never';
            }
            
            // Get backup files
            $backupFiles = Storage::disk('local')->files('backups');
            
            if (!empty($backupFiles)) {
                $lastBackup = max(array_map(function($file) {
                    return Storage::disk('local')->lastModified($file);
                }, $backupFiles));
                
                return Carbon::createFromTimestamp($lastBackup)->format('M d, Y, h:i A');
            }
        } catch (\Exception $e) {
            // Log the error but don't crash the dashboard
            \Log::error('Error getting last backup time: ' . $e->getMessage());
        }
        
        return 'Never';
    }
}
