<?php

namespace App\Http\Controllers;

use App\Models\LessonRescheduleRequest;
use App\Models\LessonSchedule;
use App\Models\LessonSection;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LessonRescheduleController extends Controller
{
    /**
     * Show the form for creating a new reschedule request.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $students = [];
        
        // If user is a student, they can only reschedule their own lessons
        if ($user->hasRole('student') || $user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            if ($student) {
                $students = [$student];
            }
        } 
        // If user is a parent, they can reschedule lessons for their children
        elseif ($user->hasRole('parent') || $user->role === 'parent') {
            $students = Student::where('parent_id', $user->id)->get();
        }
        // Admin and teachers can reschedule for any student
        else {
            $students = Student::all();
        }
        
        // Get all active lesson schedules
        $lessonSchedules = LessonSchedule::where('is_active', true)
            ->with(['subject', 'teacher', 'lessonSection'])
            ->get();
            
        // Get all active lesson sections
        $lessonSections = LessonSection::where('is_active', true)->get();
        
        return view('lesson-reschedule.create', compact('students', 'lessonSchedules', 'lessonSections'));
    }

    /**
     * Store a newly created reschedule request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'current_lesson_schedule_id' => 'required|exists:lesson_schedules,id',
            'requested_lesson_section_id' => 'required|exists:lesson_sections,id',
            'reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the reschedule request
        LessonRescheduleRequest::create([
            'user_id' => Auth::id(),
            'student_id' => $request->student_id,
            'current_lesson_schedule_id' => $request->current_lesson_schedule_id,
            'requested_lesson_section_id' => $request->requested_lesson_section_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('lesson-reschedule.success');
    }

    /**
     * Display success message after creating a reschedule request.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        return view('lesson-reschedule.success');
    }
    
    /**
     * Display a listing of reschedule requests (admin/teacher only).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = LessonRescheduleRequest::with([
            'student', 
            'currentLessonSchedule', 
            'requestedLessonSection',
            'user'
        ])->latest()->get();
        
        return view('lesson-reschedule.index', compact('requests'));
    }
    
    /**
     * Update the status of a reschedule request (admin/teacher only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LessonRescheduleRequest  $rescheduleRequest
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, LessonRescheduleRequest $rescheduleRequest)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rescheduleRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('lesson-reschedule.index')
            ->with('success', 'Reschedule request has been ' . $request->status . '.');
    }
}
