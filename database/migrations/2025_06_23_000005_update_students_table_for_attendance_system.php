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
            $table->string('school_attending')->nullable()->after('enrollment_code');
            $table->text('medical_condition')->nullable()->after('school_attending');
            $table->string('post_code')->nullable()->after('residential_address');
            
            // Add fields for parent/guardian information
            $table->string('parent_name')->nullable()->after('guardians_name');
            $table->string('parent_address')->nullable()->after('parent_name');
            $table->string('parent_post_code')->nullable()->after('parent_address');
            $table->string('parent_contact_number1')->nullable()->after('parent_post_code');
            $table->string('parent_contact_number2')->nullable()->after('parent_contact_number1');
            $table->string('parent_email')->nullable()->after('parent_contact_number2');
            
            // Add fields for second parent/guardian
            $table->string('second_parent_name')->nullable()->after('parent_email');
            $table->string('second_parent_address')->nullable()->after('second_parent_name');
            $table->string('second_parent_post_code')->nullable()->after('second_parent_address');
            $table->string('second_parent_contact_number1')->nullable()->after('second_parent_post_code');
            $table->string('second_parent_contact_number2')->nullable()->after('second_parent_contact_number1');
            $table->string('second_parent_email')->nullable()->after('second_parent_contact_number2');
            
            // Add field for other children in family
            $table->text('other_children_in_family')->nullable()->after('second_parent_email');
            
            // Add field for centre assignment
            $table->foreignId('centre_id')->nullable()->after('other_children_in_family')->constrained()->nullOnDelete();
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
