<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonRescheduleRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'student_id',
        'current_lesson_schedule_id',
        'requested_lesson_section_id',
        'reason',
        'status',
        'admin_notes',
    ];

    /**
     * Get the user who created the reschedule request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student associated with this request.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the current lesson schedule.
     */
    public function currentLessonSchedule()
    {
        return $this->belongsTo(LessonSchedule::class, 'current_lesson_schedule_id');
    }

    /**
     * Get the requested lesson section.
     */
    public function requestedLessonSection()
    {
        return $this->belongsTo(LessonSection::class, 'requested_lesson_section_id');
    }
}
