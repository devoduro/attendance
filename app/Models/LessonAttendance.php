<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'lesson_schedule_id',
        'attendance_date',
        'status',
        'check_in_time',
        'remarks',
        'notification_sent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'notification_sent' => 'boolean',
    ];

    /**
     * Get the student that owns the attendance record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lesson schedule that owns the attendance record.
     */
    public function lessonSchedule(): BelongsTo
    {
        return $this->belongsTo(LessonSchedule::class);
    }
}
