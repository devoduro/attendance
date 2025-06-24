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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('academic_year');
            $table->integer('term'); // 1, 2, 3
            $table->decimal('class_score', 5, 2)->default(0); // out of 30
            $table->decimal('exam_score', 5, 2)->default(0); // out of 70
            $table->decimal('total_score', 5, 2)->default(0); // class_score + exam_score
            $table->string('grade')->nullable(); // A, B, C, D, E, F
            $table->string('remarks')->nullable();
            $table->timestamp('date_recorded')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate records
            $table->unique(['student_id', 'subject_id', 'academic_year', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
