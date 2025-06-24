<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with(['program'])->get();
        $academicYears = DB::table('academic_years')->get();
        $activeYear = $academicYears->where('is_current', true)->first();
        
        if (!$activeYear) {
            $this->command->error('No active academic year found. Please seed academic years first.');
            return;
        }
        
        // Get semesters for current academic year
        $semesters = DB::table('semesters')
            ->where('academic_year_id', $activeYear->id)
            ->get();
        
        $this->command->info('Seeding results...');
        
        foreach ($students as $student) {
            // Skip students without program
            if (!$student->program_id) continue;
            
            // Get courses for student's program
            $courses = DB::table('courses')
                ->where('program_id', $student->program_id)
                ->get();
                
            foreach ($semesters as $semester) {
                $semesterCourses = $courses->where('semester_id', $semester->id);
                
                foreach ($semesterCourses as $course) {
                    // Generate random score
                    $score = $this->getRandomScore(40, 100);
                    
                    // Determine grade based on score
                    $letterGrade = $this->calculateLetterGrade($score);
                    $gradePoint = $this->calculateGradePoint($letterGrade);
                    $remarks = $this->getRemarkForGrade($letterGrade);
                    
                    // Create result record
                    DB::table('results')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'semester_id' => $semester->id,
                        ],
                        [
                            'academic_year_id' => $activeYear->id,
                            'score' => $score,
                            'grade' => $letterGrade,
                            'grade_point' => $gradePoint,
                            'remark' => $remarks,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
            
            // For students in Form 2 and Form 3, add previous years results
            if ($student->class && ($student->class->level == 'SHS2' || $student->class->level == 'SHS3')) {
                $previousYears = $academicYears->where('is_current', false)->sortByDesc('name');
                $yearsToCreate = $student->class->level == 'SHS2' ? 1 : 2;
                $yearCounter = 0;
                
                foreach ($previousYears as $prevYear) {
                    if ($yearCounter >= $yearsToCreate) {
                        break;
                    }
                    
                    // Find a valid semester for previous year
                    $prevYearSemesters = DB::table('semesters')
                        ->where('academic_year_id', $prevYear->id)
                        ->first();
                    
                    // Skip if no semester exists for previous year
                    if (!$prevYearSemesters) continue;
                    
                    foreach ($courses as $course) {
                        // Generate score for previous years
                        $score = $this->getRandomScore(30, 90);
                        
                        // Grades for previous years
                        $letterGrade = $this->calculateLetterGrade($score);
                        $gradePoint = $this->calculateGradePoint($letterGrade);
                        $remarks = $this->getRemarkForGrade($letterGrade);
                        
                        // Create result record for previous year
                        DB::table('results')->updateOrInsert(
                            [
                                'student_id' => $student->id,
                                'course_id' => $course->id,
                                'academic_year_id' => $prevYear->id,
                            ],
                            [
                                'semester_id' => $prevYearSemesters->id, // Use the first semester from previous year
                                'score' => $score,
                                'grade' => $letterGrade,
                                'grade_point' => $gradePoint,
                                'remark' => $remarks,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                    
                    $yearCounter++;
                }
            }
        }
    }
    
    /**
     * Generate a random score within the specified range.
     */
    private function getRandomScore($min, $max)
    {
        return rand($min, $max);
    }
    
    /**
     * Calculate letter grade based on score
     */
    private function calculateLetterGrade($score): string
    {
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        if ($score >= 40) return 'E';
        return 'F';
    }
    
    /**
     * Calculate grade point based on letter grade
     */
    private function calculateGradePoint($letterGrade): float
    {
        switch ($letterGrade) {
            case 'A': return 4.0;
            case 'B': return 3.0;
            case 'C': return 2.0;
            case 'D': return 1.0;
            case 'E': return 0.5;
            default: return 0.0;
        }
    }
    
    /**
     * Get remark for grade
     */
    private function getRemarkForGrade($grade): string
    {
        switch ($grade) {
            case 'A':
                return 'Distinction';
            case 'B':
                return 'Credit';
            case 'C':
                return 'Pass';
            case 'D':
                return 'Marginal Pass';
            case 'E':
                return 'Borderline Fail';
            default:
                return 'Fail';
        }
    }
}
