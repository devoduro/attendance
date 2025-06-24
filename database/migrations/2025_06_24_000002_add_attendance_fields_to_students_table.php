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
        Schema::table('students', function (Blueprint $table) {
            // Add fields for attendance system
            if (!Schema::hasColumn('students', 'centre_id')) {
                $table->foreignId('centre_id')->nullable()->constrained()->nullOnDelete();
            }
            
            if (!Schema::hasColumn('students', 'school_attending')) {
                $table->string('school_attending')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'medical_condition')) {
                $table->text('medical_condition')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'parent_contact_number1')) {
                $table->string('parent_contact_number1')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'parent_email')) {
                $table->string('parent_email')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove fields added for attendance system
            $table->dropColumn([
                'centre_id',
                'school_attending',
                'medical_condition',
                'parent_name',
                'parent_contact_number1',
                'parent_email'
            ]);
        });
    }
};
