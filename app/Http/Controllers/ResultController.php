<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\GradeScheme;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the results.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Result::with(['student', 'subject', 'academicYear']);
        
        // Apply filters if provided
        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        $results = $query->paginate(20);
        $classes = SchoolClass::where('status', 'active')->get();
        $subjects = Subject::all();
        
        return view('results.index', compact('results', 'classes', 'subjects'));
    }

    /**
     * Show the form for creating a new result.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::where('status', 'active')->get();
        $subjects = Subject::all();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $gradeSchemes = GradeScheme::all();
        
        return view('results.create', compact('students', 'subjects', 'academicYears', 'gradeSchemes'));
    }

    /**
     * Store a newly created result in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required',
            'semester_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
        ]);
        
        // Calculate grade based on score
        $grade = $this->calculateGrade($validated['score']);
        $validated['grade'] = $grade;
        
        Result::create($validated);
        
        return redirect()->route('results.index')
            ->with('success', 'Result recorded successfully.');
    }

    /**
     * Display the specified result.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        return view('results.show', compact('result'));
    }

    /**
     * Show the form for editing the specified result.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        $students = Student::where('status', 'active')->get();
        $subjects = Subject::all();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $gradeSchemes = GradeScheme::all();
        
        return view('results.edit', compact('result', 'students', 'subjects', 'academicYears', 'gradeSchemes'));
    }

    /**
     * Update the specified result in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'required',
            'semester_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
        ]);
        
        // Calculate grade based on score
        $grade = $this->calculateGrade($validated['score']);
        $validated['grade'] = $grade;
        
        $result->update($validated);
        
        return redirect()->route('results.index')
            ->with('success', 'Result updated successfully.');
    }

    /**
     * Remove the specified result from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        $result->delete();
        
        return redirect()->route('results.index')
            ->with('success', 'Result deleted successfully.');
    }
    
    /**
     * Calculate the grade based on score.
     * 
     * @param float $score
     * @return string
     */
    private function calculateGrade($score)
    {
        $gradeSchemes = GradeScheme::all();
        
        foreach ($gradeSchemes as $scheme) {
            if ($score >= $scheme->min_score && $score <= $scheme->max_score) {
                return $scheme->grade;
            }
        }
        
        return 'F'; // Default grade if no matching grade scheme found
    }
    
    /**
     * Show the form for bulk creating results.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate(Request $request)
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        // If class_id is provided, pre-select students from that class
        $selectedClassId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');
        $students = collect();
        
        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)
                ->where('status', 'active')
                ->with('user')
                ->get();
        }
        
        return view('results.bulk-create', compact(
            'classes', 
            'subjects', 
            'academicYears', 
            'students',
            'selectedClassId',
            'selectedSubjectId'
        ));
    }
    
    /**
     * Store bulk results in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term' => 'required|in:1,2,3',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $classId = $request->class_id;
        $subjectId = $request->subject_id;
        $academicYearId = $request->academic_year_id;
        $term = $request->term;
        $scores = $request->scores;
        
        $successCount = 0;
        
        foreach ($scores as $studentId => $score) {
            // Skip if no score provided
            if ($score === null || $score === '') {
                continue;
            }
            
            // Calculate grade based on score
            $grade = $this->calculateGrade($score);
            
            // Create or update the result
            Result::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'academic_year_id' => $academicYearId,
                    'term' => $term,
                ],
                [
                    'score' => $score,
                    'grade' => $grade,
                    'class_id' => $classId,
                    'updated_by' => auth()->id(),
                ]
            );
            
            $successCount++;
        }
        
        return redirect()->route('results.index')
            ->with('success', "$successCount results have been recorded successfully.");
    }
    
    /**
     * Generate a semester report for a student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function semesterReport(Request $request)
    {
        // If student_id, academic_year_id, and semester_id are provided, show the report
        if ($request->filled(['student_id', 'academic_year_id', 'semester_id'])) {
            $student = Student::with(['user', 'class', 'program', 'house'])->findOrFail($request->student_id);
            $academicYear = AcademicYear::findOrFail($request->academic_year_id);
            $semesterId = $request->semester_id;
            
            // Get all results for this student in this semester
            $results = Result::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('semester_id', $semesterId)
                ->with(['course', 'semester'])
                ->get();
            
            // Calculate total marks, average, and position
            $totalMarks = $results->sum('score');
            $averageMark = $results->count() > 0 ? round($totalMarks / $results->count(), 2) : 0;
            
            // Get class position
            $classmates = Student::where('class_id', $student->class_id)->pluck('id');
            $classmateAverages = [];
            
            foreach ($classmates as $classmateId) {
                $classmateResults = Result::where('student_id', $classmateId)
                    ->where('academic_year_id', $academicYear->id)
                    ->where('semester_id', $semesterId)
                    ->get();
                
                $classmateTotal = $classmateResults->sum('score');
                $classmateAvg = $classmateResults->count() > 0 ? $classmateTotal / $classmateResults->count() : 0;
                $classmateAverages[$classmateId] = $classmateAvg;
            }
            
            // Sort in descending order
            arsort($classmateAverages);
            $position = array_search($student->id, array_keys($classmateAverages)) + 1;
            
            // Get attendance data (if available)
            $attendance = ['present' => 0, 'total' => 0];
            if (class_exists('\App\Models\Attendance')) {
                $attendance['present'] = \App\Models\Attendance::where('student_id', $student->id)
                    ->where('academic_year_id', $academicYear->id)
                    ->where('semester_id', $semesterId)
                    ->where('status', 'present')
                    ->count();
                    
                $attendance['total'] = \App\Models\Attendance::where('student_id', $student->id)
                    ->where('academic_year_id', $academicYear->id)
                    ->where('semester_id', $semesterId)
                    ->count();
            }
            
            // Determine promotion status based on average
            $promotedTo = '';
            if ($semesterId == 3) { // Only show promotion status for final semester
                if ($averageMark >= 50) {
                    // Logic to determine next class
                    $currentLevel = $student->class->level;
                    if ($currentLevel == 'SHS1') {
                        $promotedTo = 'SHS2';
                    } elseif ($currentLevel == 'SHS2') {
                        $promotedTo = 'SHS3';
                    } elseif ($currentLevel == 'SHS3') {
                        $promotedTo = 'COMPLETED';
                    }
                } else {
                    $promotedTo = $student->class->level; // Repeat current level
                }
            }
            
            // Get semester name
            $semester = \App\Models\Semester::find($semesterId);
            $semesterName = $semester ? $semester->name : 'Semester ' . $semesterId;
            
            return view('results.semester-report', compact(
                'student', 
                'academicYear', 
                'semesterId',
                'semesterName',
                'results', 
                'totalMarks', 
                'averageMark', 
                'position',
                'attendance',
                'promotedTo'
            ));
        }
        
        // Otherwise, show the form to select student and semester
        $students = Student::where('status', 'active')->with('user')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $semesters = \App\Models\Semester::all()->pluck('name', 'id')->toArray();
        
        return view('results.semester-report-form', compact('students', 'academicYears', 'semesters'));
    }
}
