<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\LessonSchedule;
use App\Models\LessonSection;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonScheduleController extends Controller
{
    /**
     * Display a listing of the lesson schedules.
     */
    public function index()
    {
        $lessonSchedules = LessonSchedule::with(['centre', 'lessonSection', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_date')
            ->get();
            
        return view('lesson-schedules.index', compact('lessonSchedules'));
    }

    /**
     * Show the form for creating a new lesson schedule.
     */
    public function create()
    {
        $centres = Centre::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $lessonSections = LessonSection::where('is_active', true)->orderBy('start_time')->pluck('name', 'id');
        $teachers = Teacher::orderBy('user_id')->get()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->user->name
            ];
        })->pluck('name', 'id');
        $daysOfWeek = [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
        
        return view('lesson-schedules.create', compact('centres', 'lessonSections', 'teachers', 'daysOfWeek'));
    }

    /**
     * Store a newly created lesson schedule in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'centre_id' => 'required|exists:centres,id',
            'lesson_section_id' => 'required|exists:lesson_sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('lesson-schedules.create')
                ->withErrors($validator)
                ->withInput();
        }

        LessonSchedule::create($request->all());

        return redirect()->route('lesson-schedules.index')
            ->with('success', 'Lesson schedule created successfully.');
    }

    /**
     * Display the specified lesson schedule.
     */
    public function show(LessonSchedule $lessonSchedule)
    {
        $lessonSchedule->load(['centre', 'lessonSection', 'teacher', 'students']);
        
        return view('lesson-schedules.show', compact('lessonSchedule'));
    }

    /**
     * Show the form for editing the specified lesson schedule.
     */
    public function edit(LessonSchedule $lessonSchedule)
    {
        $centres = Centre::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $lessonSections = LessonSection::where('is_active', true)->orderBy('start_time')->pluck('name', 'id');
        $teachers = Teacher::orderBy('user_id')->get()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->user->name
            ];
        })->pluck('name', 'id');
        $daysOfWeek = [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
        
        return view('lesson-schedules.edit', compact('lessonSchedule', 'centres', 'lessonSections', 'teachers', 'daysOfWeek'));
    }

    /**
     * Update the specified lesson schedule in storage.
     */
    public function update(Request $request, LessonSchedule $lessonSchedule)
    {
        $validator = Validator::make($request->all(), [
            'centre_id' => 'required|exists:centres,id',
            'lesson_section_id' => 'required|exists:lesson_sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('lesson-schedules.edit', $lessonSchedule->id)
                ->withErrors($validator)
                ->withInput();
        }

        $lessonSchedule->update($request->all());

        return redirect()->route('lesson-schedules.index')
            ->with('success', 'Lesson schedule updated successfully.');
    }

    /**
     * Remove the specified lesson schedule from storage.
     */
    public function destroy(LessonSchedule $lessonSchedule)
    {
        $lessonSchedule->delete();

        return redirect()->route('lesson-schedules.index')
            ->with('success', 'Lesson schedule deleted successfully.');
    }
    
    /**
     * Assign students to a lesson schedule.
     */
    public function assignStudentsForm(LessonSchedule $lessonSchedule)
    {
        $lessonSchedule->load(['centre', 'students']);
        
        // Get students from the same centre
        $students = \App\Models\Student::where('centre_id', $lessonSchedule->centre_id)
            ->where('status', 'active')
            ->with('user')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'enrollment_code' => $student->enrollment_code
                ];
            })
            ->pluck('name', 'id');
            
        $enrolledStudentIds = $lessonSchedule->students->pluck('id')->toArray();
        
        return view('lesson-schedules.assign-students', compact('lessonSchedule', 'students', 'enrolledStudentIds'));
    }
    
    /**
     * Store student assignments to a lesson schedule.
     */
    public function assignStudents(Request $request, LessonSchedule $lessonSchedule)
    {
        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('lesson-schedules.assign-students', $lessonSchedule->id)
                ->withErrors($validator)
                ->withInput();
        }

        $studentIds = $request->input('student_ids', []);
        
        // Sync students with the pivot data
        $syncData = [];
        foreach ($studentIds as $studentId) {
            $syncData[$studentId] = [
                'enrollment_date' => now(),
                'is_active' => true,
            ];
        }
        
        $lessonSchedule->students()->sync($syncData);

        return redirect()->route('lesson-schedules.show', $lessonSchedule->id)
            ->with('success', 'Students assigned to lesson schedule successfully.');
    }
}
