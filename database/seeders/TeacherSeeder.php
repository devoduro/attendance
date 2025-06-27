<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teacher users
        $teacherUsers = User::where('role', 'teacher')->get();
        
        // Get departments
        $departments = Department::all();
        
        if ($departments->isEmpty()) {
            $this->command->error('No departments found. Please run the DepartmentSeeder first.');
            return;
        }
        
        // Qualifications and specializations
        $qualifications = [
            'PhD in Education',
            'Master of Education',
            'Bachelor of Education',
            'Master of Arts in Teaching',
            'Bachelor of Science',
            'Master of Science',
            'Teaching Certificate',
            'Postgraduate Diploma in Education'
        ];
        
        // Create teacher profiles for each teacher user
        foreach ($teacherUsers as $index => $user) {
            // Skip if teacher profile already exists
            if (Teacher::where('user_id', $user->id)->exists()) {
                continue;
            }
            
            // Create teacher profile
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->staff_id = 'TCH' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $teacher->qualification = $qualifications[array_rand($qualifications)];
            $teacher->specialization = $departments->random()->name;
            $teacher->department_id = $departments->random()->id;
            $teacher->date_of_birth = fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d');
            $teacher->address = fake()->address;
            $teacher->phone_number = $user->phone_number ?? fake()->phoneNumber;
            $teacher->alternate_phone = fake()->phoneNumber;
            $teacher->date_employed = fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d');
            $teacher->status = 'active';
            $teacher->save();
        }
        
        $this->command->info('Teachers seeded successfully');
    }
}
