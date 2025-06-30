<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'centre_id',
        'lesson_section_id',
        'teacher_id',
        'subject_id',
        'day_of_week',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the centre that owns the lesson schedule.
     */
    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }

    /**
     * Get the lesson section that owns the lesson schedule.
     */
    public function lessonSection(): BelongsTo
    {
        return $this->belongsTo(LessonSection::class);
    }

    /**
     * Get the teacher that owns the lesson schedule.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    
    /**
     * Get the subject that owns the lesson schedule.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the students associated with the lesson schedule.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_lesson_schedules')
            ->withPivot('enrollment_date', 'end_date', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the attendances for the lesson schedule.
     */
    public function lessonAttendances(): HasMany
    {
        return $this->hasMany(LessonAttendance::class);
    }
}
