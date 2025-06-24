<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\LessonSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentLessonScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get students and lesson schedules
        $students = Student::all();
        $lessonSchedules = LessonSchedule::all();
        
        if ($students->isEmpty() || $lessonSchedules->isEmpty()) {
            $this->command->error('Required data is missing. Please run StudentSeeder and LessonScheduleSeeder first.');
            return;
        }
        
        // Track assignments to avoid duplicates
        $assignmentCount = 0;
        $assignments = [];
        
        // Assign students to lesson schedules
        foreach ($students as $student) {
            // Assign each student to 3-5 lesson schedules
            $numAssignments = rand(3, 5);
            
            // Prioritize schedules at the student's centre
            $centreSchedules = $lessonSchedules->where('centre_id', $student->centre_id);
            $otherSchedules = $lessonSchedules->where('centre_id', '!=', $student->centre_id);
            
            // Shuffle the collections to randomize selection
            $centreSchedules = $centreSchedules->shuffle();
            $otherSchedules = $otherSchedules->shuffle();
            
            // Combined collection prioritizing centre schedules
            $prioritizedSchedules = $centreSchedules->merge($otherSchedules);
            
            $assignedCount = 0;
            foreach ($prioritizedSchedules as $schedule) {
                // Check if we've already assigned this student to this schedule
                $key = $student->id . '-' . $schedule->id;
                if (isset($assignments[$key])) {
                    continue;
                }
                
                // Create the assignment
                DB::table('student_lesson_schedule')->insert([
                    'student_id' => $student->id,
                    'lesson_schedule_id' => $schedule->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $assignments[$key] = true;
                $assignmentCount++;
                $assignedCount++;
                
                // Stop once we've assigned enough schedules
                if ($assignedCount >= $numAssignments) {
                    break;
                }
            }
        }
        
        $this->command->info("$assignmentCount student-lesson schedule assignments created successfully");
    }
}
