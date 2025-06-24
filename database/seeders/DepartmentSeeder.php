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
            ['name' => 'Mathematics', 'status' => 'active'],
            ['name' => 'English Language', 'status' => 'active'],
            ['name' => 'Science', 'status' => 'active'],
            ['name' => 'Computer Science', 'status' => 'active'],
            ['name' => 'Languages', 'status' => 'active'],
            ['name' => 'Arts', 'status' => 'active'],
            ['name' => 'Music', 'status' => 'active'],
            ['name' => 'Physical Education', 'status' => 'active'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department['name']], $department);
        }
        
        $this->command->info('Departments seeded successfully');
    }
}
