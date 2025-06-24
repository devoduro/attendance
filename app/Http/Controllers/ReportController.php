<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\Result;
use App\Models\Subject;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of available reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.index');
    }
    
    /**
     * Display student performance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function studentPerformance(Request $request)
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        
        $students = collect([]);
        $selectedClass = null;
        $selectedYear = null;
        $semester = null;
        
        if ($request->filled(['class_id', 'academic_year', 'semester_id'])) {
            $selectedClass = SchoolClass::find($request->class_id);
            $selectedYear = $request->academic_year;
            $semester = $request->semester_id;
            
            $students = Student::where('class_id', $request->class_id)
                ->where('status', 'active')
                ->with(['results' => function($query) use ($request) {
                    $query->where('academic_year', $request->academic_year)
                          ->where('semester_id', $request->semester_id);
                }])
                ->get();
        }
        
        return view('reports.student-performance', compact(
            'classes', 
            'academicYears', 
            'students', 
            'selectedClass', 
            'selectedYear',
            'semester'
        ));
    }
    
    /**
     * Display class performance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function classPerformance(Request $request)
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        $subjects = Subject::all();
        
        $classStats = [];
        $selectedClass = null;
        $selectedYear = null;
        $semester = null;
        
        if ($request->filled(['class_id', 'academic_year', 'semester_id'])) {
            $selectedClass = SchoolClass::find($request->class_id);
            $selectedYear = $request->academic_year;
            $semester = $request->semester_id;
            
            $results = Result::where('academic_year', $request->academic_year)
                ->where('semester_id', $request->semester_id)
                ->whereHas('student', function($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                })
                ->get();
                
            $subjects = $results->pluck('subject_id')->unique();
            
            foreach ($subjects as $subjectId) {
                $subjectResults = $results->where('subject_id', $subjectId);
                $subjectName = Subject::find($subjectId)->name;
                
                $classStats[] = [
                    'subject_name' => $subjectName,
                    'average_score' => round($subjectResults->avg('score'), 2),
                    'highest_score' => $subjectResults->max('score'),
                    'lowest_score' => $subjectResults->min('score'),
                    'pass_count' => $subjectResults->where('score', '>=', 40)->count(),
                    'fail_count' => $subjectResults->where('score', '<', 40)->count(),
                    'total_count' => $subjectResults->count()
                ];
            }
        }
        
        return view('reports.class-performance', compact(
            'classes', 
            'academicYears', 
            'classStats', 
            'selectedClass', 
            'selectedYear',
            'semester'
        ));
    }
    
    /**
     * Display programme statistics report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function programmeStatistics(Request $request)
    {
        $programmes = Program::all();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        
        $stats = [];
        $selectedYear = null;
        
        if ($request->filled('academic_year')) {
            $selectedYear = $request->academic_year;
            
            foreach ($programmes as $programme) {
                $studentCount = Student::whereHas('class.program', function($query) use ($programme) {
                    $query->where('id', $programme->id);
                })->count();
                
                $passRate = 0;
                $averageScore = 0;
                
                if ($studentCount > 0) {
                    $results = Result::where('academic_year', $request->academic_year)
                        ->whereHas('student.class.program', function($query) use ($programme) {
                            $query->where('id', $programme->id);
                        })
                        ->get();
                        
                    if ($results->count() > 0) {
                        $passRate = round(($results->where('score', '>=', 40)->count() / $results->count()) * 100, 2);
                        $averageScore = round($results->avg('score'), 2);
                    }
                }
                
                $stats[] = [
                    'programme' => $programme,
                    'student_count' => $studentCount,
                    'pass_rate' => $passRate,
                    'average_score' => $averageScore
                ];
            }
        }
        
        return view('reports.programme-statistics', compact(
            'programmes', 
            'academicYears', 
            'stats', 
            'selectedYear'
        ));
    }
    
    /**
     * Generate and display a custom report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customReport(Request $request)
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        $subjects = Subject::all();
        
        $reportData = [];
        
        if ($request->filled('report_type')) {
            // Process custom report based on report_type
            switch ($request->report_type) {
                case 'grade_distribution':
                    $reportData = $this->generateGradeDistributionReport($request);
                    break;
                case 'subject_comparison':
                    $reportData = $this->generateSubjectComparisonReport($request);
                    break;
                case 'year_over_year':
                    $reportData = $this->generateYearOverYearReport($request);
                    break;
                // Add more custom report types as needed
            }
        }
        
        return view('reports.custom', compact(
            'classes', 
            'academicYears', 
            'subjects',
            'reportData'
        ));
    }
    
    /**
     * Generate grade distribution report data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function generateGradeDistributionReport(Request $request)
    {
        // Implementation of grade distribution report
        return [];
    }
    
    /**
     * Generate subject comparison report data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function generateSubjectComparisonReport(Request $request)
    {
        // Implementation of subject comparison report
        return [];
    }
    
    /**
     * Generate year over year performance report data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function generateYearOverYearReport(Request $request)
    {
        // Implementation of year over year report
        return [];
    }
    
    /**
     * Display detailed report for a specific subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function subjectReport(Subject $subject)
    {
        $subject->load(['teachers.user', 'classes', 'programs']);
        
        // Calculate averages and distribution by grades
        $academicRecords = $subject->academicRecords()->whereNotNull('grade')->get();
        
        // Performance metrics
        $averageGrade = $academicRecords->avg('grade') ?: 0;
        $highestGrade = $academicRecords->max('grade') ?: 0;
        $lowestGrade = $academicRecords->min('grade') ?: 0;
        $passingGrade = 50; // Assuming 50% is passing grade
        $totalGraded = $academicRecords->count();
        $totalPassed = $academicRecords->where('grade', '>=', $passingGrade)->count();
        $passRate = $totalGraded > 0 ? round(($totalPassed / $totalGraded) * 100) : 0;
        
        // Grade distribution for chart
        $gradeDistribution = [
            'A' => $academicRecords->whereBetween('grade', [80, 100])->count(),
            'B' => $academicRecords->whereBetween('grade', [70, 79.99])->count(),
            'C' => $academicRecords->whereBetween('grade', [60, 69.99])->count(),
            'D' => $academicRecords->whereBetween('grade', [50, 59.99])->count(),
            'F' => $academicRecords->where('grade', '<', 50)->count(),
        ];
        
        // Performance by class
        $classPerfData = [];
        foreach ($subject->classes as $class) {
            $classRecords = $academicRecords->where('class_id', $class->id);
            if ($classRecords->count() > 0) {
                $classPerfData[] = [
                    'class_name' => $class->name,
                    'average_grade' => round($classRecords->avg('grade'), 1) ?: 0,
                    'pass_rate' => $classRecords->count() > 0 ? 
                        round(($classRecords->where('grade', '>=', $passingGrade)->count() / $classRecords->count()) * 100) : 0,
                    'student_count' => $classRecords->count()
                ];
            }
        }
        
        // Get academic years for filtering
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        return view('reports.subject', compact(
            'subject',
            'averageGrade',
            'highestGrade',
            'lowestGrade',
            'passRate',
            'totalGraded',
            'totalPassed',
            'gradeDistribution',
            'classPerfData',
            'academicYears'
        ));
    }
    
    /**
     * Display GPA distribution across programs and academic years.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function gpaDistribution(Request $request)
    {
        $programs = Program::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        $selectedProgram = null;
        $selectedYear = null;
        $gpaData = [];
        
        if ($request->filled(['program_id', 'academic_year'])) {
            $selectedProgram = Program::find($request->program_id);
            $selectedYear = $request->academic_year;
            
            // Get students in the selected program
            $students = Student::where('program_id', $request->program_id)
                ->where('status', 'active')
                ->get();
                
            // Calculate GPA distribution
            $gpaRanges = [
                '3.5-4.0' => 0,
                '3.0-3.49' => 0,
                '2.5-2.99' => 0,
                '2.0-2.49' => 0,
                '1.0-1.99' => 0,
                '0.0-0.99' => 0,
            ];
            
            foreach ($students as $student) {
                // Calculate student's GPA for the selected academic year
                $gpa = $this->calculateStudentGPA($student->id, $selectedYear);
                
                // Increment the appropriate GPA range
                if ($gpa >= 3.5) {
                    $gpaRanges['3.5-4.0']++;
                } elseif ($gpa >= 3.0) {
                    $gpaRanges['3.0-3.49']++;
                } elseif ($gpa >= 2.5) {
                    $gpaRanges['2.5-2.99']++;
                } elseif ($gpa >= 2.0) {
                    $gpaRanges['2.0-2.49']++;
                } elseif ($gpa >= 1.0) {
                    $gpaRanges['1.0-1.99']++;
                } else {
                    $gpaRanges['0.0-0.99']++;
                }
            }
            
            $gpaData = $gpaRanges;
        }
        
        return view('reports.gpa-distribution', compact(
            'programs',
            'academicYears',
            'selectedProgram',
            'selectedYear',
            'gpaData'
        ));
    }
    
    /**
     * Calculate a student's GPA for a specific academic year.
     *
     * @param  int  $studentId
     * @param  string  $academicYear
     * @return float
     */
    private function calculateStudentGPA($studentId, $academicYear)
    {
        $results = Result::where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->get();
            
        if ($results->isEmpty()) {
            return 0;
        }
        
        $totalPoints = 0;
        $totalCredits = 0;
        
        foreach ($results as $result) {
            // Get subject credit hours (default to 1 if not set)
            $creditHours = $result->subject->credit_hours ?? 1;
            
            // Convert percentage grade to GPA points
            $gpaPoints = $this->scoreToGPA($result->score);
            
            $totalPoints += ($gpaPoints * $creditHours);
            $totalCredits += $creditHours;
        }
        
        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
    }
    
    /**
     * Convert a percentage score to GPA points.
     *
     * @param  float  $score
     * @return float
     */
    private function scoreToGPA($score)
    {
        if ($score >= 80) return 4.0;
        if ($score >= 75) return 3.5;
        if ($score >= 70) return 3.0;
        if ($score >= 65) return 2.5;
        if ($score >= 60) return 2.0;
        if ($score >= 55) return 1.5;
        if ($score >= 50) return 1.0;
        return 0.0;
    }
    
    /**
     * Display classification distribution report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function classificationDistribution(Request $request)
    {
        $programs = Program::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        $selectedProgram = null;
        $selectedYear = null;
        $classificationData = [];
        
        if ($request->filled(['program_id', 'academic_year'])) {
            $selectedProgram = Program::find($request->program_id);
            $selectedYear = $request->academic_year;
            
            // Get students in the selected program
            $students = Student::where('program_id', $request->program_id)
                ->with(['academicRecords' => function($query) use ($request) {
                    $query->where('academic_year', $request->academic_year);
                }])
                ->get();
                
            // Classification categories
            $classifications = [
                'First Class' => 0,
                'Second Class Upper' => 0,
                'Second Class Lower' => 0,
                'Third Class' => 0,
                'Pass' => 0,
                'Fail' => 0
            ];
            
            // Count students in each classification
            foreach ($students as $student) {
                $gpa = $this->calculateStudentGPA($student, $request->academic_year);
                
                if ($gpa >= 3.6) {
                    $classifications['First Class']++;
                } elseif ($gpa >= 3.0) {
                    $classifications['Second Class Upper']++;
                } elseif ($gpa >= 2.5) {
                    $classifications['Second Class Lower']++;
                } elseif ($gpa >= 2.0) {
                    $classifications['Third Class']++;
                } elseif ($gpa >= 1.0) {
                    $classifications['Pass']++;
                } else {
                    $classifications['Fail']++;
                }
            }
            
            $classificationData = [
                'totalStudents' => $students->count(),
                'classifications' => $classifications
            ];
        }
        
        return view('reports.classification-distribution', compact(
            'programs',
            'academicYears',
            'selectedProgram',
            'selectedYear',
            'classificationData'
        ));
    }
    
    /**
     * Display semester comparison report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function semesterComparison(Request $request)
    {
        $programs = Program::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $semesters = ['First Semester', 'Second Semester']; // Adjust based on your system's semesters
        
        $selectedProgram = null;
        $selectedYear = null;
        $comparisonData = [];
        
        if ($request->filled(['program_id', 'academic_year'])) {
            $selectedProgram = Program::find($request->program_id);
            $selectedYear = $request->academic_year;
            
            // Get results for both semesters
            $firstSemesterResults = Result::where('program_id', $request->program_id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', 'First Semester')
                ->get();
                
            $secondSemesterResults = Result::where('program_id', $request->program_id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', 'Second Semester')
                ->get();
            
            // Calculate metrics for first semester
            $firstSemesterMetrics = [
                'totalResults' => $firstSemesterResults->count(),
                'averageScore' => round($firstSemesterResults->avg('score') ?? 0, 2),
                'highestScore' => $firstSemesterResults->max('score') ?? 0,
                'lowestScore' => $firstSemesterResults->min('score') ?? 0,
                'passRate' => $firstSemesterResults->count() > 0 ? 
                    round(($firstSemesterResults->where('score', '>=', 50)->count() / $firstSemesterResults->count()) * 100, 2) : 0
            ];
            
            // Calculate metrics for second semester
            $secondSemesterMetrics = [
                'totalResults' => $secondSemesterResults->count(),
                'averageScore' => round($secondSemesterResults->avg('score') ?? 0, 2),
                'highestScore' => $secondSemesterResults->max('score') ?? 0,
                'lowestScore' => $secondSemesterResults->min('score') ?? 0,
                'passRate' => $secondSemesterResults->count() > 0 ? 
                    round(($secondSemesterResults->where('score', '>=', 50)->count() / $secondSemesterResults->count()) * 100, 2) : 0
            ];
            
            // Get subjects for comparison
            $subjects = Subject::whereHas('results', function($query) use ($request) {
                $query->where('program_id', $request->program_id)
                      ->where('academic_year', $request->academic_year);
            })->get();
            
            // Compare subject performance across semesters
            $subjectComparison = [];
            foreach ($subjects as $subject) {
                $firstSemAvg = $firstSemesterResults->where('subject_id', $subject->id)->avg('score') ?? 0;
                $secondSemAvg = $secondSemesterResults->where('subject_id', $subject->id)->avg('score') ?? 0;
                
                $subjectComparison[] = [
                    'subject_name' => $subject->name,
                    'first_semester_avg' => round($firstSemAvg, 2),
                    'second_semester_avg' => round($secondSemAvg, 2),
                    'difference' => round($secondSemAvg - $firstSemAvg, 2)
                ];
            }
            
            $comparisonData = [
                'firstSemester' => $firstSemesterMetrics,
                'secondSemester' => $secondSemesterMetrics,
                'subjectComparison' => $subjectComparison
            ];
        }
        
        return view('reports.semester-comparison', compact(
            'programs',
            'academicYears',
            'semesters',
            'selectedProgram',
            'selectedYear',
            'comparisonData'
        ));
    }
    
    /**
     * Display course performance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function coursePerformance(Request $request)
    {
        $subjects = Subject::where('status', 'active')->get();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        
        $selectedSubject = null;
        $selectedYear = null;
        $performanceData = [];
        
        if ($request->filled(['subject_id', 'academic_year'])) {
            $selectedSubject = Subject::find($request->subject_id);
            $selectedYear = $request->academic_year;
            
            // Get results for the selected subject and academic year
            $results = Result::where('subject_id', $request->subject_id)
                ->where('academic_year', $request->academic_year)
                ->with(['student', 'class'])
                ->get();
                
            // Calculate performance metrics
            $totalStudents = $results->count();
            $averageScore = $results->avg('score') ?? 0;
            $highestScore = $results->max('score') ?? 0;
            $lowestScore = $results->min('score') ?? 0;
            $passingGrade = 50; // Assuming 50% is passing grade
            $passCount = $results->where('score', '>=', $passingGrade)->count();
            $passRate = $totalStudents > 0 ? round(($passCount / $totalStudents) * 100, 2) : 0;
            
            // Grade distribution
            $gradeDistribution = [
                'A' => $results->whereBetween('score', [80, 100])->count(),
                'B' => $results->whereBetween('score', [70, 79.99])->count(),
                'C' => $results->whereBetween('score', [60, 69.99])->count(),
                'D' => $results->whereBetween('score', [50, 59.99])->count(),
                'F' => $results->where('score', '<', 50)->count(),
            ];
            
            // Performance by class
            $classPerfData = [];
            $classes = $results->pluck('class')->unique();
            
            foreach ($classes as $class) {
                if ($class) {
                    $classResults = $results->where('class_id', $class->id);
                    if ($classResults->count() > 0) {
                        $classPerfData[] = [
                            'class_name' => $class->name,
                            'average_score' => round($classResults->avg('score'), 1) ?? 0,
                            'pass_rate' => $classResults->count() > 0 ? 
                                round(($classResults->where('score', '>=', $passingGrade)->count() / $classResults->count()) * 100, 2) : 0,
                            'student_count' => $classResults->count()
                        ];
                    }
                }
            }
            
            $performanceData = [
                'totalStudents' => $totalStudents,
                'averageScore' => round($averageScore, 2),
                'highestScore' => $highestScore,
                'lowestScore' => $lowestScore,
                'passRate' => $passRate,
                'gradeDistribution' => $gradeDistribution,
                'classPerfData' => $classPerfData
            ];
        }
        
        return view('reports.course-performance', compact(
            'subjects',
            'academicYears',
            'selectedSubject',
            'selectedYear',
            'performanceData'
        ));
    }
}
