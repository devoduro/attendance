<?php

namespace App\Http\Controllers;

use App\Exports\LessonAttendancesExport;
use App\Models\LessonAttendance;
use App\Models\LessonSchedule;
use App\Models\Student;
use App\Models\Teacher;
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
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.subject', 'lessonSchedule.teacher.user']);
        
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
        
        if ($request->filled('subject_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
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
        $subjects = \App\Models\Subject::where('status', 'active')->get();
            
        return view('lesson-attendances.index', compact(
            'attendances', 
            'centres', 
            'teachers', 
            'students',
            'subjects',
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
            'lessonSchedule.teacher.user',
            'lessonSchedule.subject'
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
        
        if ($request->filled('subject_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
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
        
        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        
        if ($format === 'excel') {
            return Excel::download(new LessonAttendancesExport($attendances), 'attendance_report.xlsx');
        } else if ($format === 'pdf') {
            $pdf = PDF::loadView('exports.lesson-attendances-pdf', compact('attendances'));
            return $pdf->download('attendance_report.pdf');
        }
        
        return back()->with('error', 'Invalid export format.');
    }
    
    /**
     * Show attendance for the current day.
     */
    public function daily(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $centreId = $request->input('centre_id');
        $subjectId = $request->input('subject_id');
        
        $query = LessonSchedule::with(['centre', 'lessonSection', 'teacher.user', 'subject'])
            ->where('start_date', '<=', $date)
            ->where(function($q) use ($date) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $date);
            });
            
        if ($centreId) {
            $query->where('centre_id', $centreId);
        }
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        // Get day of week for the selected date
        $dayOfWeek = Carbon::parse($date)->format('l');
        $query->where('day_of_week', $dayOfWeek);
        
        $lessonSchedules = $query->get();
        
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        $selectedDate = Carbon::parse($date);
        
        // Calculate total students for the selected lesson schedules
        $totalStudents = 0;
        $attendanceTaken = 0;
        $totalPossibleAttendance = 0;
        $presentCount = 0;
        
        foreach ($lessonSchedules as $schedule) {
            $studentCount = $schedule->students()->count();
            $totalStudents += $studentCount;
            $totalPossibleAttendance += $studentCount;
            
            // Count how many attendance records exist for this schedule on this date
            $attendanceRecords = \App\Models\LessonAttendance::where('lesson_schedule_id', $schedule->id)
                ->whereDate('attendance_date', $date);
                
            $attendanceTaken += $attendanceRecords->count();
            
            // Count how many students are present
            $presentCount += \App\Models\LessonAttendance::where('lesson_schedule_id', $schedule->id)
                ->whereDate('attendance_date', $date)
                ->where('status', 'present')
                ->count();
        }
        
        // Calculate attendance taken percentage
        $attendanceTakenPercentage = $totalPossibleAttendance > 0 ? round(($attendanceTaken / $totalPossibleAttendance) * 100) : 0;
        
        // Calculate present rate percentage
        $presentRate = $attendanceTaken > 0 ? round(($presentCount / $attendanceTaken) * 100) : 0;
        
        return view('lesson-attendances.daily', compact(
            'lessonSchedules', 'date', 'centres', 'subjects', 'centreId', 
            'subjectId', 'selectedDate', 'totalStudents', 'attendanceTakenPercentage', 'presentRate'
        ));
    }
    
    /**
     * Show attendance for the current week.
     */
    public function weekly(Request $request)
    {
        $weekStart = $request->input('week_start') ? Carbon::parse($request->input('week_start')) : now()->startOfWeek();
        $startOfWeek = $weekStart->copy()->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        
        $centreId = $request->input('centre_id');
        $subjectId = $request->input('subject_id');
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre', 'lessonSchedule.subject', 'lessonSchedule.teacher.user'])
            ->whereBetween('attendance_date', [$startOfWeek, $endOfWeek]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        if ($subjectId) {
            $query->whereHas('lessonSchedule', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }
        
        $attendances = $query->orderBy('attendance_date')
            ->get()
            ->groupBy('attendance_date');
        
        // Calculate statistics
        $totalLessons = $attendances->count();
        $totalStudents = $attendances->flatten()->count();
        $presentCount = $attendances->flatten()->where('status', 'present')->count();
        $absentCount = $attendances->flatten()->where('status', 'absent')->count();
        $lateCount = $attendances->flatten()->where('status', 'late')->count();
        $presentRate = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
            
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        
        return view('lesson-attendances.weekly', compact(
            'attendances', 'weekStart', 'startOfWeek', 'endOfWeek', 'centres', 'subjects', 
            'centreId', 'subjectId', 'totalLessons', 'totalStudents', 'presentCount', 
            'absentCount', 'lateCount', 'presentRate'
        ));
    }
    
    /**
     * Show attendance for a specific month.
     */
    public function monthly(Request $request)
    {
        $monthInput = $request->input('month');
        if ($monthInput) {
            $selectedMonth = Carbon::createFromFormat('Y-m', $monthInput);
        } else {
            $selectedMonth = now();
        }
        
        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();
        
        $centreId = $request->input('centre_id');
        $subjectId = $request->input('subject_id');
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre', 'lessonSchedule.subject', 'lessonSchedule.teacher.user'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        if ($subjectId) {
            $query->whereHas('lessonSchedule', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }
        
        $attendances = $query->orderBy('attendance_date')
            ->get()
            ->groupBy('attendance_date');
        
        // Calculate statistics
        $totalLessons = $attendances->count();
        $totalStudents = $attendances->flatten()->count();
        $presentCount = $attendances->flatten()->where('status', 'present')->count();
        $absentCount = $attendances->flatten()->where('status', 'absent')->count();
        $lateCount = $attendances->flatten()->where('status', 'late')->count();
        $presentRate = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
            
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        
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
        
        return view('lesson-attendances.monthly', compact(
            'attendances', 'selectedMonth', 'centres', 'subjects', 'centreId', 'subjectId',
            'months', 'years', 'totalLessons', 'totalStudents', 'presentCount', 'absentCount',
            'lateCount', 'presentRate'
        ));
    }
    
    /**
     * Show the form for taking attendance for a specific lesson schedule.
     */
    public function takeAttendance(Request $request, LessonSchedule $lessonSchedule)
    {
        $date = $request->input('date', now()->toDateString());
        
        // Load the students enrolled in this lesson schedule
        $lessonSchedule->load(['students.user', 'lessonSection', 'centre', 'subject', 'teacher.user']);
        
        // Check if attendance records already exist for this date and lesson schedule
        $attendances = LessonAttendance::where('lesson_schedule_id', $lessonSchedule->id)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');
        
        $attendanceDate = Carbon::parse($date);
        
        return view('lesson-attendances.take', compact('lessonSchedule', 'attendances', 'attendanceDate'));
    }
    
    /**
     * Store attendance records for a lesson schedule.
     */
    public function storeAttendance(Request $request, LessonSchedule $lessonSchedule)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent',
        ]);
        
        $date = $request->input('date');
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
        $subjectId = $request->input('subject_id');
        
        $query = LessonAttendance::with(['student.user', 'lessonSchedule.lessonSection', 'lessonSchedule.centre', 'lessonSchedule.subject'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);
            
        if ($centreId) {
            $query->whereHas('lessonSchedule', function ($q) use ($centreId) {
                $q->where('centre_id', $centreId);
            });
        }
        
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        if ($subjectId) {
            $query->whereHas('lessonSchedule', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
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
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        
        return view('lesson-attendances.report', compact(
            'attendances', 
            'studentSummary', 
            'startDate', 
            'endDate', 
            'centres', 
            'students',
            'subjects',
            'centreId', 
            'studentId',
            'subjectId'
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
    
    /**
     * Display attendance report for a specific student.
     *
     * @param  \App\Models\Student  $student
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function studentAttendance(Student $student, Request $request)
    {
        // Get lesson schedules for this student
        $lessonSchedules = $student->lessonSchedules()
            ->with(['centre', 'lessonSection', 'subject', 'teacher.user'])
            ->get();
        
        // Get attendance records for this student
        $query = LessonAttendance::where('student_id', $student->id)
            ->with(['lessonSchedule.centre', 'lessonSchedule.lessonSection', 'lessonSchedule.subject', 'lessonSchedule.teacher.user']);
        
        // Filter by lesson schedule if specified
        if ($request->filled('lesson_schedule_id')) {
            $query->where('lesson_schedule_id', $request->lesson_schedule_id);
        }
        
        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }
        
        // Filter by status if specified
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by subject if specified
        if ($request->filled('subject_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(15);
        
        // Calculate attendance statistics
        $totalAttendances = $attendances->total();
        $presentCount = $query->clone()->where('status', 'present')->count();
        $absentCount = $query->clone()->where('status', 'absent')->count();
        $attendanceRate = $totalAttendances > 0 ? ($presentCount / $totalAttendances) * 100 : 0;
        
        // Get all active subjects for the filter dropdown
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        
        return view('lesson-attendances.student-report', compact(
            'student',
            'attendances',
            'lessonSchedules',
            'totalAttendances',
            'presentCount',
            'absentCount',
            'attendanceRate',
            'subjects'
        ));
    }
    
    /**
     * Export student attendance records to various formats.
     *
     * @param  \App\Models\Student  $student
     * @param  string  $format
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportStudentAttendance(Student $student, $format = 'pdf', Request $request)
    {
        // Get attendance records for this student
        $query = LessonAttendance::where('student_id', $student->id)
            ->with(['lessonSchedule.centre', 'lessonSchedule.lessonSection', 'lessonSchedule.subject', 'lessonSchedule.teacher.user']);
        
        // Apply filters similar to the studentAttendance method
        if ($request->filled('lesson_schedule_id')) {
            $query->where('lesson_schedule_id', $request->lesson_schedule_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('subject_id')) {
            $query->whereHas('lessonSchedule', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        
        // Calculate attendance statistics
        $totalAttendances = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $attendanceRate = $totalAttendances > 0 ? ($presentCount / $totalAttendances) * 100 : 0;
        
        $data = [
            'student' => $student,
            'attendances' => $attendances,
            'totalAttendances' => $totalAttendances,
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'attendanceRate' => $attendanceRate,
        ];
        
        // Export based on format
        switch ($format) {
            case 'pdf':
                $pdf = PDF::loadView('exports.student-attendance-pdf', $data);
                return $pdf->download('student_attendance_' . $student->id . '.pdf');
            case 'excel':
                return Excel::download(new StudentAttendanceExport($data), 'student_attendance_' . $student->id . '.xlsx');
            case 'csv':
                return Excel::download(new StudentAttendanceExport($data), 'student_attendance_' . $student->id . '.csv', \Maatwebsite\Excel\Excel::CSV);
            default:
                return redirect()->back()->with('error', 'Invalid export format');
        }
    }
    
    /**
     * Display attendance report for a specific teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function teacherAttendance(Teacher $teacher, Request $request)
    {
        // Get lesson schedules for this teacher
        $schedulesQuery = LessonSchedule::where('teacher_id', $teacher->id)
            ->with(['centre', 'lessonSection', 'subject', 'students.user']);
        
        // Filter by centre if specified
        if ($request->filled('centre_id')) {
            $schedulesQuery->where('centre_id', $request->centre_id);
        }
        
        // Filter by subject if specified
        if ($request->filled('subject_id')) {
            $schedulesQuery->where('subject_id', $request->subject_id);
        }
        
        $lessonSchedules = $schedulesQuery->get();
        
        // Get recent attendance records for this teacher's schedules
        $scheduleIds = $lessonSchedules->pluck('id')->toArray();
        $attendancesQuery = LessonAttendance::whereIn('lesson_schedule_id', $scheduleIds)
            ->with(['student.user', 'lessonSchedule.centre', 'lessonSchedule.lessonSection', 'lessonSchedule.subject']);
        
        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $attendancesQuery->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $attendancesQuery->where('attendance_date', '<=', $request->date_to);
        } else {
            // Default to last 30 days if no date range specified
            $attendancesQuery->where('attendance_date', '>=', now()->subDays(30)->toDateString());
        }
        
        // Filter by status if specified
        if ($request->filled('status') && $request->status !== 'all') {
            $attendancesQuery->where('status', $request->status);
        }
        
        $recentAttendances = $attendancesQuery->orderBy('attendance_date', 'desc')->paginate(15);
        
        // Calculate attendance statistics
        $attendanceStats = [];
        foreach ($lessonSchedules as $schedule) {
            $scheduleAttendances = LessonAttendance::where('lesson_schedule_id', $schedule->id);
            
            // Apply date filters to statistics as well
            if ($request->filled('date_from')) {
                $scheduleAttendances->where('attendance_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $scheduleAttendances->where('attendance_date', '<=', $request->date_to);
            } else {
                $scheduleAttendances->where('attendance_date', '>=', now()->subDays(30)->toDateString());
            }
            
            $totalAttendances = $scheduleAttendances->count();
            $presentCount = $scheduleAttendances->clone()->where('status', 'present')->count();
            $absentCount = $scheduleAttendances->clone()->where('status', 'absent')->count();
            $attendanceRate = $totalAttendances > 0 ? ($presentCount / $totalAttendances) * 100 : 0;
            
            $attendanceStats[$schedule->id] = [
                'total' => $totalAttendances,
                'present' => $presentCount,
                'absent' => $absentCount,
                'rate' => $attendanceRate,
            ];
        }
        
        // Get all centres and subjects for filter dropdowns
        $centres = \App\Models\Centre::where('is_active', true)->pluck('name', 'id');
        $subjects = \App\Models\Subject::where('status', 'active')->pluck('name', 'id');
        
        return view('lesson-attendances.teacher-report', compact(
            'teacher',
            'lessonSchedules',
            'recentAttendances',
            'attendanceStats',
            'centres',
            'subjects'
        ));
    }
}
