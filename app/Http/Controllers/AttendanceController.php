<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display the attendance register for a specific class on a specific date.
     *
     * @param  \App\Models\SchoolClass  $class
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showClassAttendance(SchoolClass $class, Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $students = $class->students()->with(['user', 'attendances' => function ($query) use ($date) {
            $query->where('date', $date);
        }])->get();

        return view('attendance.class', compact('class', 'students', 'date'));
    }

    /**
     * Store the attendance records for a class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeClassAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->attendance as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $request->class_id,
                        'date' => $request->date,
                    ],
                    [
                        'status' => $data['status'],
                        'remarks' => $data['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Attendance recorded successfully.');
    }
}
