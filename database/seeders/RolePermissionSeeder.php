<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Student management
            'view students',
            'create students',
            'edit students',
            'delete students',
            
            // Teacher management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            
            // Centre management
            'view centres',
            'create centres',
            'edit centres',
            'delete centres',
            
            // Department management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Lesson Section management
            'view lesson sections',
            'create lesson sections',
            'edit lesson sections',
            'delete lesson sections',
            
            // Lesson Schedule management
            'view lesson schedules',
            'create lesson schedules',
            'edit lesson schedules',
            'delete lesson schedules',
            'assign students to schedules',
            'remove students from schedules',
            
            // Attendance management
            'view attendance',
            'take attendance',
            'edit attendance',
            'delete attendance',
            'view attendance reports',
            'export attendance reports',
            
            // Report management
            'view reports',
            'create reports',
            'export reports',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->syncPermissions([
            'view students',
            'view teachers',
            'view centres',
            'view departments',
            'view lesson sections',
            'view lesson schedules',
            'view attendance',
            'take attendance',
            'edit attendance',
            'view attendance reports',
            'export attendance reports',
            'view reports',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions([
            'view attendance',
        ]);

        $parentRole = Role::firstOrCreate(['name' => 'parent']);
        $parentRole->syncPermissions([
            'view attendance',
            'view reports',
        ]);
        
        $this->command->info('Roles and permissions created successfully');
    }
}
