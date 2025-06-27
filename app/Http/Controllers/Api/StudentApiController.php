<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StudentApiController extends Controller
{
    /**
     * Search for students based on query string.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Get the authenticated user
        $user = auth()->user();
        
        // Initialize the query builder
        $studentsQuery = Student::with(['user', 'class'])
            ->select('students.*')
            ->join('users', 'students.user_id', '=', 'users.id');
        
        // Apply role-based restrictions
        if ($user && $user->hasRole('teacher')) {
            $teacher = $user->teacherProfile;
            if ($teacher) {
                // Get IDs of classes assigned to this teacher
                $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                // Only show students from teacher's classes
                $studentsQuery->whereIn('students.class_id', $teacherClassIds);
            } else {
                // If teacher profile not found, don't show any students
                return response()->json([]);
            }
        } elseif ($user && $user->hasRole('student')) {
            $student = $user->studentProfile;
            if ($student) {
                // Only show students from the same class
                $studentsQuery->where('students.class_id', $student->class_id);
            } else {
                // If student profile not found, don't show any students
                return response()->json([]);
            }
        }
        
        // Apply search filters
        $studentsQuery->where(function($q) use ($query) {
            $q->where('users.name', 'like', "%{$query}%")
              ->orWhere('students.enrollment_code', 'like', "%{$query}%")
              ->orWhere('students.mobile_phone', 'like', "%{$query}%")
              ->orWhere('students.email', 'like', "%{$query}%");
        });
        
        // Get results (limit to 10 for quick search)
        $students = $studentsQuery->limit(10)->get();
        
        // Format the results
        $results = $students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'enrollment_code' => $student->enrollment_code,
                'class' => $student->class->name ?? 'Not Assigned',
                'profile_photo_url' => $student->user->profile_photo_url ?? null,
            ];
        });
        
        return response()->json($results);
    }
    
    /**
     * Get detailed information for a specific student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the student with relationships
        $student = Student::with(['user', 'class', 'centre'])->findOrFail($id);
        
        // Get the authenticated user
        $user = auth()->user();
        
        // Check authorization
        if ($user && !$user->hasRole('admin')) {
            if ($user->hasRole('teacher')) {
                $teacher = $user->teacherProfile;
                if ($teacher) {
                    // Check if student belongs to one of teacher's classes
                    $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                    if (!in_array($student->class_id, $teacherClassIds)) {
                        return response()->json(['error' => 'Unauthorized'], 403);
                    }
                } else {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
            } elseif ($user->hasRole('student')) {
                // Students can only view their own profile or classmates
                $currentStudent = $user->studentProfile;
                if (!$currentStudent || ($currentStudent->id != $student->id && $currentStudent->class_id != $student->class_id)) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
            } else {
                // Other roles not allowed
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }
        
        // Get recent attendance (last 10 records)
        $recentAttendance = [];
        if ($student->attendances) {
            $recentAttendance = $student->attendances()
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get()
                ->map(function($attendance) {
                    return [
                        'date' => $attendance->date,
                        'status' => $attendance->status,
                        'subject' => $attendance->subject->name ?? 'N/A',
                    ];
                });
        }
        
        // Return student data with relationships
        return response()->json([
            'id' => $student->id,
            'enrollment_code' => $student->enrollment_code,
            'status' => $student->status,
            'email' => $student->email,
            'mobile_phone' => $student->mobile_phone,
            'guardians_name' => $student->guardians_name,
            'guardians_phone' => $student->guardians_phone,
            'admission_date' => $student->admission_date,
            'user' => [
                'id' => $student->user->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'profile_photo_url' => $student->user->profile_photo_url ?? null,
            ],
            'class' => $student->class ? [
                'id' => $student->class->id,
                'name' => $student->class->name,
            ] : null,
            'centre' => $student->centre ? [
                'id' => $student->centre->id,
                'name' => $student->centre->name,
            ] : null,
            'recent_attendance' => $recentAttendance,
        ]);
    }
}
