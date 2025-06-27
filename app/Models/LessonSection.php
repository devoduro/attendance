<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Get the lesson schedules associated with the section.
     */
    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class);
    }
    
    /**
     * Calculate the duration in minutes between start_time and end_time.
     *
     * @return int
     */
    public function getDurationInMinutes(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        
        $start = $this->start_time;
        $end = $this->end_time;
        
        // If end time is before start time, assume it's the next day
        if ($end < $start) {
            $end = $end->addDay();
        }
        
        return $end->diffInMinutes($start);
    }
}
