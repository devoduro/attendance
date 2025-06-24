<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            CentreSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            LessonSectionSeeder::class,
            LessonScheduleSeeder::class,
            StudentLessonScheduleSeeder::class,
            LessonAttendanceSeeder::class,
        ]);
    }
}
