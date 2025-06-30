<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Centre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
        'address',
        'contact_number',
        'email',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the students associated with the centre.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the lesson schedules associated with the centre.
     */
    public function lessonSchedules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class);
    }
    
    /**
     * Get the teachers associated with the centre.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
    
    /**
     * Get the subjects associated with the centre.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
