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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('enrollment_code')->unique();
          
            
            $table->string('email')->nullable();
           
 
            $table->date('date_of_birth')->nullable();
      
                  $table->date('admission_date')->nullable();
            $table->enum('status', ['active', 'graduated', 'suspended', 'withdrawn'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
