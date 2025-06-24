<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Centre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get student users
        $studentUsers = User::where('role', 'student')->get();
        
        // Get centres
        $centres = Centre::all();
        
        if ($centres->isEmpty()) {
            $this->command->error('No centres found. Please run the CentreSeeder first.');
            return;
        }
        
        // Create student profiles for each student user
        foreach ($studentUsers as $index => $user) {
            // Skip if student profile already exists
            if (Student::where('user_id', $user->id)->exists()) {
                continue;
            }
            
            // Create student profile
            $student = new Student();
            $student->user_id = $user->id;
            $student->student_id = 'STU' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $student->date_of_birth = fake()->dateTimeBetween('-22 years', '-16 years')->format('Y-m-d');
            $student->address = fake()->address;
            $student->phone_number = $user->phone_number ?? fake()->phoneNumber;
            $student->alternate_phone = fake()->phoneNumber;
            $student->email = $user->email;
            $student->parent_name = fake()->name;
            $student->parent_phone = fake()->phoneNumber;
            $student->parent_email = fake()->email;
            $student->emergency_contact = fake()->name;
            $student->emergency_phone = fake()->phoneNumber;
            $student->admission_date = fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d');
            $student->status = 'active';
            $student->centre_id = $centres->random()->id;
            $student->save();
        }
        
        $this->command->info('Students seeded successfully');
    }
}
