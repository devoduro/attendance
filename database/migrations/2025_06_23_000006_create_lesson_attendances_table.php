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
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_schedule_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent'])->default('absent');
            $table->dateTime('check_in_time')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();
            
            // Ensure a student can only have one attendance record per lesson schedule per date
            $table->unique(['student_id', 'lesson_schedule_id', 'attendance_date']);
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
