<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'exam_id',
        'score',
        'grade',
        'remarks',
        'status'
    ];

    /**
     * Get the student that owns the exam record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the exam for this record.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
