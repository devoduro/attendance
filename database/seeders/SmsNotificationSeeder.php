<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('user')->get();
        $adminUser = User::where('role', 'admin')->first();
        $sentById = $adminUser ? $adminUser->id : null;
        
        $this->command->info('Seeding SMS notifications...');
        
        // Create different types of notifications for different students
        foreach ($students as $index => $student) {
            if (!$student->user) continue;
            
            $phoneNumber = $student->phone ?? $student->mobile_phone ?? '0550123456';
            
            // Create some attendance notifications
            if ($index % 3 == 0) {
                DB::table('sms_notifications')->insert([
                    'recipient_type' => 'student',
                    'recipient_id' => $student->id,
                    'phone_number' => $phoneNumber,
                    'message' => "Dear parent/guardian, {$student->user->name} was absent from school today. Please contact the school administration for more information.",
                    'status' => 'sent',
                    'sent_by' => $sentById,
                    'sent_at' => now()->subDays(rand(1, 30)),
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }
            
            // Create academic performance notifications for all students
            DB::table('sms_notifications')->insert([
                'recipient_type' => 'student',
                'recipient_id' => $student->id,
                'phone_number' => $phoneNumber,
                'message' => "Dear parent/guardian, End of term results for {$student->user->name} are now available. Please login to the parent portal to view them.",
                'status' => 'sent',
                'sent_by' => $sentById,
                'sent_at' => now()->subDays(rand(5, 20)),
                'created_at' => now()->subDays(rand(5, 20)),
                'updated_at' => now()->subDays(rand(5, 20)),
            ]);
            
            // Create fee payment reminders for some students
            if ($index % 2 == 0) {
                DB::table('sms_notifications')->insert([
                    'recipient_type' => 'parent',
                    'recipient_id' => $student->id,
                    'phone_number' => $phoneNumber,
                    'message' => "Dear parent/guardian, This is a reminder that {$student->user->name}'s school fees for the current term are due in one week. Please ensure timely payment.",
                    'status' => 'sent',
                    'sent_by' => $sentById,
                    'sent_at' => now()->subDays(rand(3, 15)),
                    'created_at' => now()->subDays(rand(3, 15)),
                    'updated_at' => now()->subDays(rand(3, 15)),
                ]);
            }
            
            // Create event notifications for all students
            DB::table('sms_notifications')->insert([
                'recipient_type' => 'parent',
                'recipient_id' => $student->id,
                'phone_number' => $phoneNumber,
                'message' => "Dear parent/guardian, We are pleased to invite you to our annual Parent-Teacher Conference on " . now()->addDays(14)->format('l, F j, Y') . " from 9:00 AM to 3:00 PM.",
                'status' => 'sent',
                'sent_by' => $sentById,
                'sent_at' => now()->subDays(rand(1, 10)),
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now()->subDays(rand(1, 10)),
            ]);
            
            // Create behavior notifications for some students
            if ($index % 5 == 0) {
                DB::table('sms_notifications')->insert([
                    'recipient_type' => 'student',
                    'recipient_id' => $student->id,
                    'phone_number' => $phoneNumber,
                    'message' => "Dear parent/guardian, We would like to commend {$student->user->name} for excellent behavior and participation in the recent science fair.",
                    'status' => 'sent',
                    'sent_by' => $sentById,
                    'sent_at' => now()->subDays(rand(5, 25)),
                    'created_at' => now()->subDays(rand(5, 25)),
                    'updated_at' => now()->subDays(rand(5, 25)),
                ]);
            }
        }
    }
}
