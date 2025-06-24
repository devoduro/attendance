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
            // Add fields for medical condition and school attending
            if (!Schema::hasColumn('students', 'school_attending')) {
                $table->string('school_attending')->nullable();
            }
            if (!Schema::hasColumn('students', 'medical_condition')) {
                $table->text('medical_condition')->nullable();
            }
            if (!Schema::hasColumn('students', 'residential_address')) {
                $table->string('residential_address')->nullable();
            }
            if (!Schema::hasColumn('students', 'post_code')) {
                $table->string('post_code')->nullable();
            }
            
            // Add fields for parent/guardian information
            if (!Schema::hasColumn('students', 'guardians_name')) {
                $table->string('guardians_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_address')) {
                $table->string('parent_address')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_post_code')) {
                $table->string('parent_post_code')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_contact_number1')) {
                $table->string('parent_contact_number1')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_contact_number2')) {
                $table->string('parent_contact_number2')->nullable();
            }
            if (!Schema::hasColumn('students', 'parent_email')) {
                $table->string('parent_email')->nullable();
            }
            
            // Add fields for second parent/guardian
            if (!Schema::hasColumn('students', 'second_parent_name')) {
                $table->string('second_parent_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'second_parent_address')) {
                $table->string('second_parent_address')->nullable();
            }
            if (!Schema::hasColumn('students', 'second_parent_post_code')) {
                $table->string('second_parent_post_code')->nullable();
            }
            if (!Schema::hasColumn('students', 'second_parent_contact_number1')) {
                $table->string('second_parent_contact_number1')->nullable();
            }
            if (!Schema::hasColumn('students', 'second_parent_contact_number2')) {
                $table->string('second_parent_contact_number2')->nullable();
            }
            if (!Schema::hasColumn('students', 'second_parent_email')) {
                $table->string('second_parent_email')->nullable();
            }
            
            // Add field for other children in family
            if (!Schema::hasColumn('students', 'other_children_in_family')) {
                $table->text('other_children_in_family')->nullable();
            }
            
            // Add field for centre assignment
            if (!Schema::hasColumn('students', 'centre_id')) {
                $table->foreignId('centre_id')->nullable()->constrained()->nullOnDelete();
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
                'medical_condition',
                'post_code',
                'parent_name',
                'parent_address',
                'parent_post_code',
                'parent_contact_number1',
                'parent_contact_number2',
                'parent_email',
                'second_parent_name',
                'second_parent_address',
                'second_parent_post_code',
                'second_parent_contact_number1',
                'second_parent_contact_number2',
                'second_parent_email',
                'other_children_in_family'
            ]);
            
            $table->dropForeign(['centre_id']);
            $table->dropColumn('centre_id');
        });
    }
};
