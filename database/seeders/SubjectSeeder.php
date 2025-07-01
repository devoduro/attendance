<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active centres
        $centres = Centre::where('is_active', true)->get();
        
        if ($centres->isEmpty()) {
            // If no centres exist, create at least one subject with a default centre ID
            Subject::create([
                'centre_id' => 1,
                'name' => 'Mathematics',
                'code' => 'MATH101',
                'status' => 'active',
            ]);
        } else {
            // Create subjects for each centre
            foreach ($centres as $centre) {
                // Mathematics
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'Mathematics',
                    'code' => 'MATH' . $centre->id . '101',
                    'status' => 'active',
                ]);
                
                // English
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'English',
                    'code' => 'ENG' . $centre->id . '101',
                    'status' => 'active',
                ]);
                
                // Science
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'Science',
                    'code' => 'SCI' . $centre->id . '101',
                    'status' => 'active',
                ]);
                
                // History
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'History',
                    'code' => 'HIST' . $centre->id . '101',
                    'status' => 'active',
                ]);
                
                // Computer Science
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'Computer Science',
                    'code' => 'CS' . $centre->id . '101',
                    'status' => 'active',
                ]);
                
                // Art
                Subject::create([
                    'centre_id' => $centre->id,
                    'name' => 'Art',
                    'code' => 'ART' . $centre->id . '101',
                    'status' => 'inactive', // Example of an inactive subject
                ]);
            }
        }
    }
}
