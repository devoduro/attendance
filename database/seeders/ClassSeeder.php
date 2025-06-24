<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get current academic year
        $academicYear = AcademicYear::where('is_current', true)->first();
        $academicYearName = $academicYear ? $academicYear->name : '2024/2025';
        
        // Prempeh College SHS1 Classes (Form 1)
        $shs1Classes = [
            // Science Classes
            ['name' => 'SHS1 Science 1A', 'level' => 'SHS1', 'description' => 'Form 1 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS1 Science 1B', 'level' => 'SHS1', 'description' => 'Form 1 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS1 Science 1C', 'level' => 'SHS1', 'description' => 'Form 1 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS1 Science 1D', 'level' => 'SHS1', 'description' => 'Form 1 Science class (Elective Mathematics)', 'program' => 'Science'],
            
            // General Arts Classes
            ['name' => 'SHS1 Arts 1A', 'level' => 'SHS1', 'description' => 'Form 1 General Arts class (Literature)', 'program' => 'General Arts'],
            ['name' => 'SHS1 Arts 1B', 'level' => 'SHS1', 'description' => 'Form 1 General Arts class (Government)', 'program' => 'General Arts'],
            ['name' => 'SHS1 Arts 1C', 'level' => 'SHS1', 'description' => 'Form 1 General Arts class (Economics)', 'program' => 'General Arts'],
            
            // Business Classes
            ['name' => 'SHS1 Business 1A', 'level' => 'SHS1', 'description' => 'Form 1 Business class (Accounting)', 'program' => 'Business'],
            ['name' => 'SHS1 Business 1B', 'level' => 'SHS1', 'description' => 'Form 1 Business class (Economics)', 'program' => 'Business'],
            
            // Visual Arts Class
            ['name' => 'SHS1 Visual Arts 1A', 'level' => 'SHS1', 'description' => 'Form 1 Visual Arts class', 'program' => 'Visual Arts'],
        ];
        
        // Prempeh College SHS2 Classes (Form 2)
        $shs2Classes = [
            // Science Classes
            ['name' => 'SHS2 Science 2A', 'level' => 'SHS2', 'description' => 'Form 2 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS2 Science 2B', 'level' => 'SHS2', 'description' => 'Form 2 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS2 Science 2C', 'level' => 'SHS2', 'description' => 'Form 2 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS2 Science 2D', 'level' => 'SHS2', 'description' => 'Form 2 Science class (Elective Mathematics)', 'program' => 'Science'],
            
            // General Arts Classes
            ['name' => 'SHS2 Arts 2A', 'level' => 'SHS2', 'description' => 'Form 2 General Arts class (Literature)', 'program' => 'General Arts'],
            ['name' => 'SHS2 Arts 2B', 'level' => 'SHS2', 'description' => 'Form 2 General Arts class (Government)', 'program' => 'General Arts'],
            ['name' => 'SHS2 Arts 2C', 'level' => 'SHS2', 'description' => 'Form 2 General Arts class (Economics)', 'program' => 'General Arts'],
            
            // Business Classes
            ['name' => 'SHS2 Business 2A', 'level' => 'SHS2', 'description' => 'Form 2 Business class (Accounting)', 'program' => 'Business'],
            ['name' => 'SHS2 Business 2B', 'level' => 'SHS2', 'description' => 'Form 2 Business class (Economics)', 'program' => 'Business'],
            
            // Visual Arts Class
            ['name' => 'SHS2 Visual Arts 2A', 'level' => 'SHS2', 'description' => 'Form 2 Visual Arts class', 'program' => 'Visual Arts'],
        ];
        
        // Prempeh College SHS3 Classes (Form 3)
        $shs3Classes = [
            // Science Classes
            ['name' => 'SHS3 Science 3A', 'level' => 'SHS3', 'description' => 'Form 3 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS3 Science 3B', 'level' => 'SHS3', 'description' => 'Form 3 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS3 Science 3C', 'level' => 'SHS3', 'description' => 'Form 3 Science class (Pure Sciences)', 'program' => 'Science'],
            ['name' => 'SHS3 Science 3D', 'level' => 'SHS3', 'description' => 'Form 3 Science class (Elective Mathematics)', 'program' => 'Science'],
            
            // General Arts Classes
            ['name' => 'SHS3 Arts 3A', 'level' => 'SHS3', 'description' => 'Form 3 General Arts class (Literature)', 'program' => 'General Arts'],
            ['name' => 'SHS3 Arts 3B', 'level' => 'SHS3', 'description' => 'Form 3 General Arts class (Government)', 'program' => 'General Arts'],
            ['name' => 'SHS3 Arts 3C', 'level' => 'SHS3', 'description' => 'Form 3 General Arts class (Economics)', 'program' => 'General Arts'],
            
            // Business Classes
            ['name' => 'SHS3 Business 3A', 'level' => 'SHS3', 'description' => 'Form 3 Business class (Accounting)', 'program' => 'Business'],
            ['name' => 'SHS3 Business 3B', 'level' => 'SHS3', 'description' => 'Form 3 Business class (Economics)', 'program' => 'Business'],
            
            // Visual Arts Class
            ['name' => 'SHS3 Visual Arts 3A', 'level' => 'SHS3', 'description' => 'Form 3 Visual Arts class', 'program' => 'Visual Arts'],
        ];

        // Create all SHS1 classes
        foreach ($shs1Classes as $class) {
            SchoolClass::create([
                'name' => $class['name'],
                'level' => $class['level'],
                'academic_year' => $academicYearName,
                'description' => $class['description'] . ' - ' . $class['program'],
                'status' => 'active',
            ]);
        }
        
        // Create all SHS2 classes
        foreach ($shs2Classes as $class) {
            SchoolClass::create([
                'name' => $class['name'],
                'level' => $class['level'],
                'academic_year' => $academicYearName,
                'description' => $class['description'] . ' - ' . $class['program'],
                'status' => 'active',
            ]);
        }
        
        // Create all SHS3 classes
        foreach ($shs3Classes as $class) {
            SchoolClass::create([
                'name' => $class['name'],
                'level' => $class['level'],
                'academic_year' => $academicYearName,
                'description' => $class['description'] . ' - ' . $class['program'],
                'status' => 'active',
            ]);
        }

        // Log completion message
        $this->command->info('Created ' . (count($shs1Classes) + count($shs2Classes) + count($shs3Classes)) . ' Prempeh College classes');
    }
}
