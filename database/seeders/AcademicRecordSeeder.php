<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with(['program', 'class'])->get();
        $subjects = Subject::where('status', 'active')->get();
        $teachers = Teacher::all();
        $academicYears = DB::table('academic_years')->get();
        
        // Get the current active academic year
        $activeYear = $academicYears->where('is_current', true)->first();
        
        if (!$activeYear) {
            $this->command->error('No active academic year found. Please seed academic years first.');
            return;
        }
        
        // Terms (1, 2, 3)
        $terms = [1, 2, 3];
        
        $this->command->info('Seeding academic records...');
        
        // For each student
        foreach ($students as $student) {
            if (!$student->class_id) {
                continue; // Skip students without a class
            }
            
            // Get subjects relevant to the student's program
            $relevantSubjects = $subjects->where(function($subject) use ($student) {
                // Include core subjects and program-specific subjects
                return $subject->is_core || 
                       ($subject->program_id && $subject->program_id == $student->program_id);
            })->take(8); // Limit to 8 subjects per student
            
            foreach ($relevantSubjects as $subject) {
                // Assign a random teacher
                $teacher = $teachers->random();
                
                // For each term
                foreach ($terms as $term) {
                    // Generate scores
                    $class_score = rand(15, 30); // Out of 30
                    $exam_score = rand(40, 70);  // Out of 70
                    $total_score = $class_score + $exam_score;
                    $grade = $this->calculateGrade($total_score);
                    
                    DB::table('academic_records')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'academic_year' => $activeYear->name,
                            'term' => $term,
                        ],
                        [
                            'teacher_id' => $teacher->id,
                            'class_id' => $student->class_id,
                            'class_score' => $class_score,
                            'exam_score' => $exam_score,
                            'total_score' => $total_score,
                            'grade' => $grade,
                            'remarks' => $this->getRemarkForGrade($grade),
                            'date_recorded' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
            
            // If student is in SHS2 or SHS3, create previous academic records
            if ($student->class && ($student->class->level == 'SHS2' || $student->class->level == 'SHS3')) {
                $previousYearName = $this->getPreviousAcademicYear($activeYear->name);
                
                // Find or create a previous academic year record
                $previousYear = $academicYears->where('name', $previousYearName)->first();
                if (!$previousYear) {
                    // Skip if we can't find the previous academic year
                    continue;
                }
                
                // Get the previous class level based on current class
                $previousLevel = $student->class->level == 'SHS2' ? 'SHS1' : 'SHS2';
                $previousClass = SchoolClass::where('level', $previousLevel)
                    ->first();
                
                if (!$previousClass) {
                    // Skip if we can't find a matching previous class
                    continue;
                }
                
                foreach ($relevantSubjects as $subject) {
                    $teacher = $teachers->random();
                    
                    foreach ($terms as $term) {
                        // Generate scores for previous year (slightly lower on average)
                        $class_score = rand(12, 28); // Out of 30
                        $exam_score = rand(35, 65);  // Out of 70
                        $total_score = $class_score + $exam_score;
                        $grade = $this->calculateGrade($total_score);
                        
                        DB::table('academic_records')->updateOrInsert(
                            [
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'academic_year' => $previousYear->name,
                                'term' => $term,
                            ],
                            [
                                'teacher_id' => $teacher->id,
                                'class_id' => $previousClass->id,
                                'class_score' => $class_score,
                                'exam_score' => $exam_score,
                                'total_score' => $total_score,
                                'grade' => $grade,
                                'remarks' => $this->getRemarkForGrade($grade),
                                'date_recorded' => now()->subYear(),
                                'created_at' => now()->subYear(),
                                'updated_at' => now()->subYear(),
                            ]
                        );
                    }
                }
                
                // For SHS3 students, create records for 2 years ago as well
                if ($student->class->level == 'SHS3') {
                    $twoYearsAgoName = $this->getPreviousAcademicYear($previousYearName);
                    $twoYearsAgo = $academicYears->where('name', $twoYearsAgoName)->first();
                    
                    if (!$twoYearsAgo) {
                        continue;
                    }
                    
                    $twoYearsAgoClass = SchoolClass::where('level', 'SHS1')
                        ->first();
                    
                    if (!$twoYearsAgoClass) {
                        continue;
                    }
                    
                    foreach ($relevantSubjects as $subject) {
                        $teacher = $teachers->random();
                        
                        foreach ($terms as $term) {
                            // Generate scores for 2 years ago (even lower on average - first year)
                            $class_score = rand(10, 25); // Out of 30
                            $exam_score = rand(30, 60);  // Out of 70
                            $total_score = $class_score + $exam_score;
                            $grade = $this->calculateGrade($total_score);
                            
                            DB::table('academic_records')->updateOrInsert(
                                [
                                    'student_id' => $student->id,
                                    'subject_id' => $subject->id,
                                    'academic_year' => $twoYearsAgo->name,
                                    'term' => $term,
                                ],
                                [
                                    'teacher_id' => $teacher->id,
                                    'class_id' => $twoYearsAgoClass->id,
                                    'class_score' => $class_score,
                                    'exam_score' => $exam_score,
                                    'total_score' => $total_score,
                                    'grade' => $grade,
                                    'remarks' => $this->getRemarkForGrade($grade),
                                    'date_recorded' => now()->subYears(2),
                                    'created_at' => now()->subYears(2),
                                    'updated_at' => now()->subYears(2),
                                ]
                            );
                        }
                    }
                }
            }
        }
        
        $this->command->info('Academic records seeded successfully!');
    }
    
    /**
     * Calculate grade based on total score
     */
    private function calculateGrade($score): string
    {
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        if ($score >= 40) return 'E';
        return 'F';
    }
    
    /**
     * Get remark based on grade
     */
    private function getRemarkForGrade($grade): string
    {
        switch ($grade) {
            case 'A': return 'Excellent';
            case 'B': return 'Very Good';
            case 'C': return 'Good';
            case 'D': return 'Credit';
            case 'E': return 'Pass';
            default: return 'Fail';
        }
    }
    
    /**
     * Get the previous academic year string
     */
    private function getPreviousAcademicYear($currentYear): string
    {
        // Format is typically '2024/2025'
        $years = explode('/', $currentYear);
        if (count($years) == 2) {
            $prevStartYear = (int)$years[0] - 1;
            $prevEndYear = (int)$years[1] - 1;
            return $prevStartYear . '/' . $prevEndYear;
        }
        return $currentYear; // Return original if format is unexpected
    }
}
