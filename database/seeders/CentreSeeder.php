<?php

namespace Database\Seeders;

use App\Models\Centre;
use Illuminate\Database\Seeder;

class CentreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centres = [
            [
                'name' => 'Main Centre',
                'location' => 'Downtown',
                'address' => '123 Main Street, City Centre',
                'contact_number' => '123-456-7890',
                'email' => 'main@attendancecentre.com',
                'is_active' => true,
            ],
            [
                'name' => 'North Branch',
                'location' => 'North District',
                'address' => '456 North Avenue, North District',
                'contact_number' => '123-456-7891',
                'email' => 'north@attendancecentre.com',
                'is_active' => true,
            ],
            [
                'name' => 'South Branch',
                'location' => 'South District',
                'address' => '789 South Boulevard, South District',
                'contact_number' => '123-456-7892',
                'email' => 'south@attendancecentre.com',
                'is_active' => true,
            ],
            [
                'name' => 'East Branch',
                'location' => 'East District',
                'address' => '101 East Road, East District',
                'contact_number' => '123-456-7893',
                'email' => 'east@attendancecentre.com',
                'is_active' => true,
            ],
            [
                'name' => 'West Branch',
                'location' => 'West District',
                'address' => '202 West Lane, West District',
                'contact_number' => '123-456-7894',
                'email' => 'west@attendancecentre.com',
                'is_active' => true,
            ],
        ];

        foreach ($centres as $centre) {
            Centre::firstOrCreate(
                ['name' => $centre['name']],
                $centre
            );
        }
        
        $this->command->info('Centres seeded successfully');
    }
}
