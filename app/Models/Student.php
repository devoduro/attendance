<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_code',
        'school_attending',
        'medical_condition',
        'date_of_birth',
     
        'email',
         
        'guardians_name',
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
        'other_children_in_family',
    
       
        'centre_id',
        'admission_date',
        'status', // active, graduated, suspended, etc.
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class that the student belongs to.
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Program relationship removed

    /**
     * Get the academic records for the student.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    /**
     * Get the transcripts for the student.
     */
    public function transcripts()
    {
        return $this->hasMany(Transcript::class);
    }
    
    /**
     * Get the house that the student belongs to.
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }
    
    /**
     * Get the centre that the student belongs to.
     */
    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
    
    /**
     * Get the lesson schedules that the student is enrolled in.
     */
    public function lessonSchedules()
    {
        return $this->belongsToMany(LessonSchedule::class, 'student_lesson_schedules')
            ->withPivot('enrollment_date', 'end_date', 'is_active')
            ->withTimestamps();
    }
    
    /**
     * Get the lesson attendances for the student.
     */
    public function lessonAttendances()
    {
        return $this->hasMany(LessonAttendance::class);
    }

    /**
     * Get the full name of the student.
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }
    
    /**
     * Calculate the age of the student based on date of birth.
     *
     * @return int|null
     */
    public function getAge()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return $this->date_of_birth->age;
    }
    
    /**
     * Calculate the Cumulative Grade Point Average (CGPA) for the student.
     *
     * @return float|null
     */
    public function calculateCGPA()
    {
        $records = $this->academicRecords()->with('result')->get();
        
        if ($records->isEmpty()) {
            return null;
        }
        
        $totalGradePoints = 0;
        $totalCreditHours = 0;
        
        foreach ($records as $record) {
            // Skip records without results or credit hours
            if (!$record->result || !$record->credit_hours) {
                continue;
            }
            
            $gradePoint = $this->getGradePoint($record->result->grade);
            $creditHours = $record->credit_hours;
            
            $totalGradePoints += ($gradePoint * $creditHours);
            $totalCreditHours += $creditHours;
        }
        
        // Avoid division by zero
        if ($totalCreditHours <= 0) {
            return null;
        }
        
        return $totalGradePoints / $totalCreditHours;
    }
    
    /**
     * Convert letter grade to grade point.
     *
     * @param string $grade
     * @return float
     */
    protected function getGradePoint($grade)
    {
        $gradePoints = [
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'E' => 0.0,
            'F' => 0.0,
        ];
        
        return $gradePoints[$grade] ?? 0.0;
    }
    
    /**
     * Get the classification based on the student's CGPA.
     *
     * @return string|null
     */
    public function getClassification()
    {
        $cgpa = $this->calculateCGPA();
        
        if ($cgpa === null) {
            return null;
        }
        
        // Get classification settings from the system
        $classifications = \App\Models\Setting::getValue('classifications', [], 'academic');
        
        // If no classifications are defined, use default classification system
        if (empty($classifications)) {
            if ($cgpa >= 3.6) {
                return 'First Class';
            } elseif ($cgpa >= 3.0) {
                return 'Second Class Upper';
            } elseif ($cgpa >= 2.5) {
                return 'Second Class Lower';
            } elseif ($cgpa >= 2.0) {
                return 'Third Class';
            } elseif ($cgpa >= 1.0) {
                return 'Pass';
            } else {
                return 'Fail';
            }
        }
        
        // Use custom classification system if defined
        foreach ($classifications as $class) {
            if ($cgpa >= $class['min_cgpa'] && $cgpa <= $class['max_cgpa']) {
                return $class['name'];
            }
        }
        
        return 'Unclassified';
    }
    
    /**
     * Check if the student has any related records that would prevent deletion
     *
     * @return bool
     */
    public function hasRelatedRecords()
    {
        // Check if student has academic records
        if ($this->academicRecords()->count() > 0) {
            return true;
        }
        
        // Check if student has transcripts
        if ($this->transcripts()->count() > 0) {
            return true;
        }
        
        // Check if student has exam records
        if ($this->hasMany(StudentExam::class)->count() > 0) {
            return true;
        }
        
        return false;
    }
}
