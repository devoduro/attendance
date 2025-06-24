<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()["\Spatie\Permission\PermissionRegistrar"]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage exams',
            'grade exams',
            'take exams',
            'view results',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage settings',
            'manage teachers',
            'manage students',
            'manage classes',
            'manage subjects',
            'manage programs',
            'view reports',
            'export data'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'manage exams',
            'grade exams',
            'view results'
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo([
            'take exams',
            'view results'
        ]);
        
        // Assign admin role to user with ID 1 (typically the first user/admin)
        $user = \App\Models\User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
