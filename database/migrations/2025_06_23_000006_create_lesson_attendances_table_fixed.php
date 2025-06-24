<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('lesson_schedule_id')->constrained('lesson_schedules')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->time('check_in_time')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();
            
            // Use a shorter name for the unique constraint
            $table->unique(['student_id', 'lesson_schedule_id', 'attendance_date'], 'attendance_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_attendances');
    }
};
