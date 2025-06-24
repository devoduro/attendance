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
        // Get all centres for random assignment
        $centres = \App\Models\Centre::all();
        
        if ($centres->isEmpty()) {
            $this->command->error('No centres found. Please run CentreSeeder first.');
            return;
        }

        foreach ($studentUsers as $index => $user) {
            // Skip if student profile already exists
            if (Student::where('user_id', $user->id)->exists()) {
                continue;
            }
            
            // Create student profile with all fields including the new attendance fields
            $student = new Student();
            $student->user_id = $user->id;
            $student->enrollment_code = 'STU' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $student->email = $user->email;
            $student->date_of_birth = fake()->dateTimeBetween('-22 years', '-16 years')->format('Y-m-d');
            $student->admission_date = fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d');
            $student->status = 'active';
            
            // New attendance fields
            $student->centre_id = $centres->random()->id;
            $student->school_attending = fake()->randomElement(['Local High School', 'International Academy', 'City Grammar School', 'Metro Secondary School', 'Regional College']);
            
            // Add medical condition for some students (30% chance)
            if (fake()->boolean(30)) {
                $student->medical_condition = fake()->randomElement([
                    'Asthma - requires inhaler', 
                    'Mild allergies to peanuts', 
                    'Needs glasses for reading',
                    'Occasional migraines',
                    'None specified'
                ]);
            }
            
            // Add residential information
            $student->residential_address = fake()->address();
            $student->post_code = fake()->postcode();
            
            // Add parent/guardian information
            $student->guardians_name = fake()->boolean(30) ? fake()->name() : null;
            $student->parent_name = fake()->name();
            $student->parent_address = fake()->boolean(70) ? fake()->address() : $student->residential_address;
            $student->parent_post_code = fake()->boolean(70) ? fake()->postcode() : $student->post_code;
            $student->parent_contact_number1 = fake()->phoneNumber();
            $student->parent_contact_number2 = fake()->boolean(60) ? fake()->phoneNumber() : null;
            $student->parent_email = fake()->safeEmail();
            
            // Add second parent information (50% chance)
            if (fake()->boolean(50)) {
                $student->second_parent_name = fake()->name();
                $student->second_parent_address = fake()->boolean(30) ? fake()->address() : $student->parent_address;
                $student->second_parent_post_code = fake()->boolean(30) ? fake()->postcode() : $student->parent_post_code;
                $student->second_parent_contact_number1 = fake()->phoneNumber();
                $student->second_parent_contact_number2 = fake()->boolean(40) ? fake()->phoneNumber() : null;
                $student->second_parent_email = fake()->safeEmail();
            }
            
            // Add other children in family information (40% chance)
            if (fake()->boolean(40)) {
                $numSiblings = fake()->numberBetween(1, 3);
                $siblings = [];
                
                for ($i = 0; $i < $numSiblings; $i++) {
                    $siblings[] = fake()->name() . ' (' . fake()->numberBetween(5, 18) . ' years old)';
                }
                
                $student->other_children_in_family = implode(', ', $siblings);
            }
            
            $student->save();
        }
        
        $this->command->info('Students seeded successfully');
    }
}
