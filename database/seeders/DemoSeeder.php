<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds for demo data.
     */
    public function run(): void
    {
        $this->call([
            SchoolClassSeeder::class,
            ProgramSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            AcademicYearSeeder::class,
            SemesterSeeder::class,
            CourseSeeder::class,
            AcademicRecordSeeder::class,
            ResultSeeder::class,
            GradeSchemeSeeder::class,
            ClassificationSeeder::class,
            TranscriptSeeder::class,
            SmsNotificationSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
