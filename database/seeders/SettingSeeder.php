<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'school_name',
                'value' => 'Ghana Secondary Technical School',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_address',
                'value' => 'P.O. Box 123, Accra, Ghana',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_phone',
                'value' => '+233 30 123 4567',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_email',
                'value' => 'info@ghanasecondary.edu.gh',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_website',
                'value' => 'www.ghanasecondary.edu.gh',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_logo',
                'value' => 'logos/school-logo.png',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_motto',
                'value' => 'Knowledge, Integrity, Excellence',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_colors',
                'value' => 'Green and Gold',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'principal_name',
                'value' => 'Dr. Kwame Asamoah',
                'group' => 'administration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'principal_signature',
                'value' => 'signatures/principal-signature.png',
                'group' => 'administration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'academic_year',
                'value' => '2023/2024',
                'group' => 'academics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'current_term',
                'value' => 'First Term',
                'group' => 'academics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'grading_system',
                'value' => 'Standard (A, B+, B, C+, C, D+, D, F)',
                'group' => 'academics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'school_calendar',
                'value' => 'calendars/academic-calendar-2023-2024.pdf',
                'group' => 'academics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'fees_structure',
                'value' => 'documents/fees-structure-2023-2024.pdf',
                'group' => 'finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'payment_methods',
                'value' => 'Bank Transfer, Mobile Money, Cash',
                'group' => 'finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bank_account',
                'value' => 'Ghana National Bank, Account: 1234567890',
                'group' => 'finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
