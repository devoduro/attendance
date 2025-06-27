<?php

namespace App\Http\Controllers;

use App\Exports\LessonAttendancesExport;
use App\Models\LessonAttendance;
use App\Models\LessonSchedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendanceNotification;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LessonAttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index(Request $request)
    {
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection']);
        
        // Apply filters if provided
        if ($request->filled('centre_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('centre_id', $request->centre_id);
            });
        }
        
        if ($request->filled('teacher_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }
        
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get attendance counts for statistics
        $presentCount = (clone $query)->where('status', 'present')->count();
        $absentCount = (clone $query)->where('status', 'absent')->count();
        $lateCount = (clone $query)->where('status', 'late')->count();
        
        // Get paginated results
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(20);
        
        $centres = \App\Models\Centre::where('is_active', true)->get();
        $teachers = \App\Models\Teacher::with('user')->where('status', 'active')->get();
        $students = \App\Models\Student::with('user')->where('status', 'active')->get();
            
        return view('lesson-attendances.index', compact(
            'attendances', 
            'centres', 
            'teachers', 
            'students', 
            'presentCount', 
            'absentCount', 
            'lateCount'
        ));
    }
    
    /**
     * Export attendance records based on filters.
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        
        $query = LessonAttendance::with([
            'student.user', 
            'lessonSchedule.lessonSection', 
            'lessonSchedule.class',
            'lessonSchedule.teacher.user'
        ]);
        
        // Apply filters if provided
        if ($request->filled('centre_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('centre_id', $request->centre_id);
            });
        }
        
        if ($request->filled('teacher_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }
        
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $fileName = 'attendance_records_' . date('Y-m-d_His');
        
        switch ($format) {
            case 'csv':
                return Excel::download(new LessonAttendancesExport($query), $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
            
            case 'pdf':
                // For PDF export, we need to get the data first
                $attendances = $query->get();
                
                $pdf = PDF::loadView('exports.lesson-attendances-pdf', compact('attendances'));
                return $pdf->download($fileName . '.pdf');
            
            case 'excel':
            default:
                return Excel::download(new LessonAttendancesExport($query), $fileName . '.xlsx');
        }
    }
    
    /**
     * Show attendance for a specific date.
     */
    public function daily(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $centreId = $request->input('centre_id');
        
        $query = LessonSchedule::with(['centre', 'lessonSection', 'teacher.user'])
            ->whereHas('students', function ($query) {
                $query->where('is_active', true);
            });
            
        if ($centreId) {
            $query->where('centre_id', $centreId);
        }
        
        // Get day of week for the selected date
        $dayOfWeek = Carbon::parse($date)->format('l');
        $query->where('day_of_week', $dayOfWeek);
        
        $lessonSchedules = $query->get();
        
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        
        return view('lesson-attendances.daily', compact('lessonSchedules', 'date', 'centres', 'centreId'));
    }
    
    /**
     * Show attendance for the current week.
     */
    public function weekly(Request $request)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $centreId = $request->input('centre_id');
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre'])
            ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        $attendances = $query->orderBy('attendance_date')
            ->get()
            ->groupBy('attendance_date');
            
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        
        return view('lesson-attendances.weekly', compact('attendances', 'startOfWeek', 'endOfWeek', 'centres', 'centreId'));
    }
    
    /**
     * Show attendance for a specific month.
     */
    public function monthly(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $centreId = $request->input('centre_id');
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        $attendances = $query->orderBy('attendance_date')
            ->get()
            ->groupBy('attendance_date');
            
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        
        $years = range(now()->year - 2, now()->year + 2);
        
        return view('lesson-attendances.monthly', compact('attendances', 'month', 'year', 'centres', 'centreId', 'months', 'years'));
    }
    
    /**
     * Show the form for taking attendance for a specific lesson schedule.
     */
    public function takeAttendance(Request $request, LessonSchedule $lessonSchedule)
    {
        $date = $request->input('date', now()->toDateString());
        
        // Load the students enrolled in this lesson schedule
        $lessonSchedule->load(['students.user', 'lessonSection', 'centre']);
        
        // Check if attendance records already exist for this date and lesson schedule
        $attendances = LessonAttendance::where('lesson_schedule_id', $lessonSchedule->id)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');
        
        // Convert date string to Carbon instance for the view
        $attendanceDate = Carbon::parse($date);
            
        return view('lesson-attendances.take', compact('lessonSchedule', 'attendanceDate', 'attendances'));
    }
    
    /**
     * Store attendance records for a lesson schedule.
     */
    public function storeAttendance(Request $request, LessonSchedule $lessonSchedule)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent',
        ]);
        
        $date = $request->input('attendance_date');
        $attendanceData = $request->input('attendance');
        
        foreach ($attendanceData as $data) {
            $studentId = $data['student_id'];
            $status = $data['status'];
            
            // Find or create attendance record
            $attendance = LessonAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'lesson_schedule_id' => $lessonSchedule->id,
                    'attendance_date' => $date,
                ],
                [
                    'status' => $status,
                    'check_in_time' => $status === 'present' ? now() : null,
                ]
            );
            
            // Send notification if not already sent
            if (!$attendance->notification_sent) {
                $this->sendAttendanceNotification($attendance);
                $attendance->notification_sent = true;
                $attendance->save();
            }
        }
        
        return redirect()->route('lesson-attendances.daily', ['date' => $date])
            ->with('success', 'Attendance recorded successfully.');
    }
    
    /**
     * Update attendance status via AJAX.
     */
    public function updateStatus(Request $request, LessonSchedule $lessonSchedule)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,absent',
            'attendance_date' => 'required|date',
        ]);
        
        $studentId = $request->input('student_id');
        $status = $request->input('status');
        $date = $request->input('attendance_date');
        
        $student = Student::findOrFail($studentId);
        
        // Find or create attendance record
        $attendance = LessonAttendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'lesson_schedule_id' => $lessonSchedule->id,
                'attendance_date' => $date,
            ],
            [
                'status' => $status,
                'check_in_time' => $status === 'present' ? now() : null,
            ]
        );
        
        $notificationSent = false;
        
        // Send notification if not already sent
        if (!$attendance->notification_sent) {
            $this->sendAttendanceNotification($attendance);
            $attendance->notification_sent = true;
            $attendance->save();
            $notificationSent = true;
        }
        
        return response()->json([
            'success' => true,
            'student_name' => $student->user->name,
            'status' => $status,
            'check_in_time' => $status === 'present' ? now()->format('H:i') : null,
            'notification_sent' => $notificationSent,
        ]);
    }
    
    /**
     * Send attendance notification email to parent.
     */
    private function sendAttendanceNotification(LessonAttendance $attendance)
    {
        $student = $attendance->student;
        $lessonSchedule = $attendance->lessonSchedule;
        
        // Check if parent email exists
        $parentEmail = $student->parent_email;
        
        if (!$parentEmail) {
            return;
        }
        
        // Send email notification
        try {
            Mail::to($parentEmail)->send(new AttendanceNotification($attendance));
        } catch (\Exception $e) {
            // Log the error but don't stop the process
            \Log::error('Failed to send attendance notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate attendance report.
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $centreId = $request->input('centre_id');
        $studentId = $request->input('student_id');
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        $attendances = $query->orderBy('attendance_date')->get();
        
        // Group by student for summary
        $studentSummary = $attendances->groupBy('student_id')->map(function ($items) {
            $student = $items->first()->student;
            $totalPresent = $items->where('status', 'present')->count();
            $totalAbsent = $items->where('status', 'absent')->count();
            $attendanceRate = $items->count() > 0 ? ($totalPresent / $items->count()) * 100 : 0;
            
            return [
                'student' => $student,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'attendance_rate' => $attendanceRate,
            ];
        });
        
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        $students = Student::with('user')->get()->pluck('user.name', 'id');
        
        return view('lesson-attendances.report', compact(
            'attendances', 
            'studentSummary', 
            'startDate', 
            'endDate', 
            'centres', 
            'students', 
            'centreId', 
            'studentId'
        ));
    }
    
    /**
     * Send birthday wishes to students.
     */
    public function sendBirthdayWishes()
    {
        // Get students whose birthday is today
        $today = now()->format('m-d');
        $students = Student::whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = ?", [$today])
            ->where('status', 'active')
            ->get();
            
        foreach ($students as $student) {
            // Check if parent email exists
            $parentEmail = $student->parent_email;
            
            if (!$parentEmail) {
                continue;
            }
            
            // Send birthday email
            try {
                Mail::to($parentEmail)->send(new \App\Mail\BirthdayWishes($student));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                \Log::error('Failed to send birthday wishes: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('dashboard')
            ->with('success', 'Birthday wishes sent to ' . $students->count() . ' students.');
    }
}
