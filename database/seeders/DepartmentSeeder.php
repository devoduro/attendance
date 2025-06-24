<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Mathematics', 'description' => 'Mathematics department for all math subjects'],
            ['name' => 'English Language', 'description' => 'English language and literature department'],
            ['name' => 'Science', 'description' => 'Science department including physics, chemistry and biology'],
            ['name' => 'Computer Science', 'description' => 'Computer science and programming department'],
            ['name' => 'Languages', 'description' => 'Foreign languages department'],
            ['name' => 'Arts', 'description' => 'Arts and creative subjects department'],
            ['name' => 'Music', 'description' => 'Music and performance arts department'],
            ['name' => 'Physical Education', 'description' => 'Physical education and sports department'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate($department);
        }
        
        $this->command->info('Departments seeded successfully');
    }
}
