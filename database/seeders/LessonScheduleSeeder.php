<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\LessonSection;
use App\Models\LessonSchedule;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Database\Seeder;

class LessonScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get centres, lesson sections, teachers and departments
        $centres = Centre::all();
        $lessonSections = LessonSection::all();
        $teachers = Teacher::all();
        $departments = Department::all();
        
        if ($centres->isEmpty() || $lessonSections->isEmpty() || $teachers->isEmpty() || $departments->isEmpty()) {
            $this->command->error('Required data is missing. Please run CentreSeeder, LessonSectionSeeder, TeacherSeeder, and DepartmentSeeder first.');
            return;
        }
        
        // Days of the week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Create lesson schedules
        $count = 0;
        foreach ($centres as $centre) {
            foreach ($departments as $department) {
                foreach ($lessonSections as $section) {
                    // Create 2-3 lesson schedules per department per section per centre
                    $numSchedules = rand(2, 3);
                    
                    for ($i = 0; $i < $numSchedules; $i++) {
                        // Get a random teacher from this department if possible
                        $departmentTeachers = $teachers->where('department_id', $department->id);
                        $teacher = $departmentTeachers->isNotEmpty() 
                            ? $departmentTeachers->random() 
                            : $teachers->random();
                        
                        // Create lesson schedule
                        $schedule = new LessonSchedule();
                        $schedule->centre_id = $centre->id;
                        $schedule->lesson_section_id = $section->id;
                        $schedule->teacher_id = $teacher->id;
                        $schedule->day_of_week = $daysOfWeek[array_rand($daysOfWeek)];
                        $schedule->start_date = fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d');
                        $schedule->end_date = fake()->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d');
                        $schedule->subject = $department->name;
                        $schedule->is_active = true;
                        $schedule->notes = 'Regular ' . $department->name . ' class in ' . $centre->name . ' during ' . $section->name;
                        $schedule->save();
                        
                        $count++;
                    }
                }
            }
        }
        
        $this->command->info("$count lesson schedules seeded successfully");
    }
}
