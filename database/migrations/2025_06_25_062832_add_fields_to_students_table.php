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
            // Child information fields
            if (!Schema::hasColumn('students', 'school_attending')) {
                $table->string('school_attending')->nullable();
            }
            if (!Schema::hasColumn('students', 'year')) {
                $table->string('year')->nullable();
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('students', 'post_code')) {
                $table->string('post_code', 20)->nullable();
            }
            if (!Schema::hasColumn('students', 'medical_condition')) {
                $table->text('medical_condition')->nullable();
            }
            
            // Parent/Guardian information fields
            if (!Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_address')) {
                $table->text('parent_address')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_post_code')) {
                $table->string('parent_post_code', 20)->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_contact_number1')) {
                $table->string('parent_contact_number1')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'school_attending',
                'year',
                'address',
                'post_code',
                'medical_condition',
                'parent_name',
                'parent_address',
                'parent_post_code',
                'parent_contact_number1'
            ]);
        });
    }
};
