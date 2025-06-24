<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'phone_number' => '1234567890',
            ]
        );
        
        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }
        
        // Create demo users
        $demoUsers = [
            [
                'name' => 'John Smith',
                'email' => 'teacher@attendance.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'status' => 'active',
                'phone_number' => '2345678901',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'student@attendance.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
                'phone_number' => '3456789012',
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'parent@attendance.com',
                'password' => Hash::make('password'),
                'role' => 'parent',
                'status' => 'active',
                'phone_number' => '4567890123',
            ],
        ];
        
        foreach ($demoUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role' => $userData['role'],
                    'status' => $userData['status'],
                    'phone_number' => $userData['phone_number'],
                ]
            );
            
            // Assign role
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }
        
        // Create additional teacher users
        $teacherNames = [
            'Michael Brown' => 'michael.brown@attendance.com',
            'Sarah Wilson' => 'sarah.wilson@attendance.com',
            'David Lee' => 'david.lee@attendance.com',
            'Emily Taylor' => 'emily.taylor@attendance.com',
            'James Anderson' => 'james.anderson@attendance.com',
        ];
        
        $teacherRole = Role::where('name', 'teacher')->first();
        
        foreach ($teacherNames as $name => $email) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'status' => 'active',
                    'phone_number' => '5' . rand(100000000, 999999999),
                ]
            );
            
            if ($teacherRole) {
                $user->assignRole($teacherRole);
            }
        }
        
        // Create additional student users
        $studentNames = [
            'Alex Johnson' => 'alex.johnson@student.com',
            'Emma Williams' => 'emma.williams@student.com',
            'Ryan Davis' => 'ryan.davis@student.com',
            'Olivia Martin' => 'olivia.martin@student.com',
            'Ethan Thompson' => 'ethan.thompson@student.com',
            'Sophia Garcia' => 'sophia.garcia@student.com',
            'Noah Rodriguez' => 'noah.rodriguez@student.com',
            'Ava Martinez' => 'ava.martinez@student.com',
            'William Robinson' => 'william.robinson@student.com',
            'Isabella Clark' => 'isabella.clark@student.com',
        ];
        
        $studentRole = Role::where('name', 'student')->first();
        
        foreach ($studentNames as $name => $email) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => 'active',
                    'phone_number' => '6' . rand(100000000, 999999999),
                ]
            );
            
            if ($studentRole) {
                $user->assignRole($studentRole);
            }
        }
        
        $this->command->info('Users seeded successfully');
    }
}
