<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the current active academic year
        $activeYear = DB::table('academic_years')->where('is_current', true)->first();

        if (!$activeYear) {
            $this->command->error('No active academic year found. Please seed academic years first.');
            return;
        }

        // Create default semesters
        $semesters = [
            [
                'name' => 'First Semester',
                'description' => 'First semester of the academic year',
                'start_date' => now()->startOfYear(),
                'end_date' => now()->startOfYear()->addMonths(4),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Second Semester',
                'description' => 'Second semester of the academic year',
                'start_date' => now()->startOfYear()->addMonths(4),
                'end_date' => now()->startOfYear()->addMonths(8),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Third Semester',
                'description' => 'Third semester of the academic year',
                'start_date' => now()->startOfYear()->addMonths(8),
                'end_date' => now()->endOfYear(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        foreach ($semesters as $semester) {
            DB::table('semesters')->updateOrInsert(
                ['name' => $semester['name'], 'academic_year_id' => $activeYear->id],
                array_merge($semester, ['academic_year_id' => $activeYear->id])
            );
        }
    }
}
