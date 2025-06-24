<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYears = [
            [
                'name' => '2024/2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-07-31',
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2023/2024',
                'start_date' => '2023-09-01',
                'end_date' => '2024-07-31',
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2021/2022',
                'start_date' => '2021-09-01',
                'end_date' => '2022-07-31',
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($academicYears as $year) {
            DB::table('academic_years')->updateOrInsert(
                ['name' => $year['name']],
                $year
            );
        }
    }
}
