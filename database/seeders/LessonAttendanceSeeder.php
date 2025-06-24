<?php

namespace Database\Seeders;

use App\Models\LessonAttendance;
use App\Models\LessonSchedule;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get student-lesson schedule relationships
        $studentSchedules = DB::table('student_lesson_schedule')
            ->select('student_id', 'lesson_schedule_id')
            ->get();
        
        if ($studentSchedules->isEmpty()) {
            $this->command->error('No student-lesson schedule assignments found. Please run StudentLessonScheduleSeeder first.');
            return;
        }
        
        // Get all students and lesson schedules for reference
        $students = Student::all()->keyBy('id');
        $lessonSchedules = LessonSchedule::all()->keyBy('id');
        
        // Attendance statuses
        $statuses = ['present', 'absent', 'late', 'excused'];
        $statusWeights = [70, 10, 15, 5]; // Probability weights for each status
        
        // Track attendance records
        $attendanceCount = 0;
        
        // Generate attendance records for the past 30 days
        $pastDays = 30;
        $today = now();
        
        foreach ($studentSchedules as $relation) {
            $studentId = $relation->student_id;
            $scheduleId = $relation->lesson_schedule_id;
            
            // Skip if student or schedule doesn't exist
            if (!isset($students[$studentId]) || !isset($lessonSchedules[$scheduleId])) {
                continue;
            }
            
            $schedule = $lessonSchedules[$scheduleId];
            
            // Generate attendance for past days
            for ($i = $pastDays; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                
                // Only create attendance for days matching the schedule's day of week
                // and if the date is between start_date and end_date
                if (
                    $date->format('l') !== $schedule->day_of_week ||
                    $date->format('Y-m-d') < $schedule->start_date ||
                    ($schedule->end_date && $date->format('Y-m-d') > $schedule->end_date)
                ) {
                    continue;
                }
                
                // Check if attendance record already exists
                $exists = LessonAttendance::where('student_id', $studentId)
                    ->where('lesson_schedule_id', $scheduleId)
                    ->where('attendance_date', $date->format('Y-m-d'))
                    ->exists();
                
                if ($exists) {
                    continue;
                }
                
                // Determine status based on weighted probability
                $status = $this->getRandomWeightedElement($statuses, $statusWeights);
                
                // Create attendance record
                $attendance = new LessonAttendance();
                $attendance->student_id = $studentId;
                $attendance->lesson_schedule_id = $scheduleId;
                $attendance->attendance_date = $date->format('Y-m-d');
                $attendance->status = $status;
                
                // Add check-in time if present or late
                if ($status === 'present' || $status === 'late') {
                    $baseTime = $schedule->lessonSection->start_time;
                    $baseHour = (int)substr($baseTime, 0, 2);
                    $baseMinute = (int)substr($baseTime, 3, 2);
                    
                    // For 'late' status, add 5-20 minutes
                    $minutesToAdd = $status === 'late' ? rand(5, 20) : rand(-5, 5);
                    
                    $checkInTime = \Carbon\Carbon::createFromTime($baseHour, $baseMinute)
                        ->addMinutes($minutesToAdd)
                        ->format('H:i:s');
                    
                    $attendance->check_in_time = $checkInTime;
                }
                
                // Add remarks for some records
                if (rand(1, 10) <= 3) { // 30% chance of having remarks
                    $remarks = [
                        'present' => ['Good participation in class', 'Actively engaged in discussion', 'Completed all exercises'],
                        'absent' => ['No notification received', 'Will need to catch up on missed work', 'Parent called to explain absence'],
                        'late' => ['Traffic delay reported', 'Had to complete assignment for another class', 'Bus was late'],
                        'excused' => ['Medical appointment', 'Family emergency', 'Approved school activity']
                    ];
                    
                    $attendance->remarks = $remarks[$status][array_rand($remarks[$status])];
                }
                
                // Set notification flag
                $attendance->notification_sent = rand(0, 1) === 1;
                
                $attendance->save();
                $attendanceCount++;
            }
        }
        
        $this->command->info("$attendanceCount attendance records generated successfully");
    }
    
    /**
     * Get a random element from an array with weighted probabilities.
     *
     * @param array $elements
     * @param array $weights
     * @return mixed
     */
    private function getRandomWeightedElement(array $elements, array $weights)
    {
        $totalWeight = array_sum($weights);
        $randomWeight = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($elements as $index => $element) {
            $currentWeight += $weights[$index];
            if ($randomWeight <= $currentWeight) {
                return $element;
            }
        }
        
        return $elements[0]; // Fallback
    }
}
