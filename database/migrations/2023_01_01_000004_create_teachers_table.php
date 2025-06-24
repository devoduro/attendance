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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('ghana_card_number')->nullable();
            $table->date('date_employed')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
