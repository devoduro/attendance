<?php

namespace Database\Seeders;

use App\Models\LessonSection;
use Illuminate\Database\Seeder;

class LessonSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonSections = [
            [
                'name' => 'Morning Session',
                'description' => 'Morning classes from 8:00 AM to 12:00 PM',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Afternoon Session',
                'description' => 'Afternoon classes from 1:00 PM to 5:00 PM',
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Evening Session',
                'description' => 'Evening classes from 6:00 PM to 9:00 PM',
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Weekend Morning',
                'description' => 'Weekend morning classes from 9:00 AM to 12:00 PM',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Weekend Afternoon',
                'description' => 'Weekend afternoon classes from 2:00 PM to 5:00 PM',
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($lessonSections as $section) {
            LessonSection::firstOrCreate(
                ['name' => $section['name']],
                $section
            );
        }
        
        $this->command->info('Lesson sections seeded successfully');
    }
}
