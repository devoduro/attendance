<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'semester_id',
        'total_attendance',
        'attendance_percentage',
        'status'
    ];

    /**
     * Get the student that owns the academic record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the academic year of the record.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
