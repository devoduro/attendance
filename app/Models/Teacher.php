<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'teacher_id', // Alias for staff_id in the views
        'qualification',
        'specialization',
        'department_id',
        'date_of_birth',
        'address',
        'phone_number',
        'alternate_phone',
       
        'date_employed',
        'status',
        // 'bio' removed as it doesn't exist in the database table
        // 'profile_image' removed as it doesn't exist in the database table
        'phone', // Alias for phone_number in the views
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_employed' => 'date',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['teacher_id'];
    
    /**
     * Get the teacher's ID (alias for staff_id).
     *
     * @return string
     */
    public function getTeacherIdAttribute()
    {
        return $this->staff_id;
    }
    
    /**
     * Set the teacher's ID (alias for staff_id).
     *
     * @param string $value
     * @return void
     */
    public function setTeacherIdAttribute($value)
    {
        $this->attributes['staff_id'] = $value;
    }
    
    /**
     * Get the teacher's phone (alias for phone_number).
     *
     * @return string|null
     */
    public function getPhoneAttribute()
    {
        return $this->phone_number;
    }
    
    /**
     * Set the teacher's phone (alias for phone_number).
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone_number'] = $value;
    }

    /**
     * Get the user that owns the teacher profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Subjects relationship removed

    // Classes relationship removed

    /**
     * Get the department of the teacher.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the full name of the teacher.
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }
    
    /**
     * Check if the teacher has any related records that would prevent deletion
     *
     * @return bool
     */
    public function hasRelatedRecords()
    {
        // Check if teacher has assigned subjects
        if ($this->subjects()->count() > 0) {
            return true;
        }
        
        // Check if teacher has assigned classes
        if ($this->classes()->count() > 0) {
            return true;
        }
        
        return false;
    }
}
