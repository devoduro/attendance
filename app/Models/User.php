<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, teacher, student
        'profile_photo',
        'phone_number',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if user is a student
     */
    public function isStudent()
    {
        return $this->hasRole('student');
    }

    /**
     * Check if the user has any related records that would prevent deletion
     *
     * @return bool
     */
    public function hasRelatedRecords()
    {
        if ($this->isTeacher() && $this->teacherProfile) {
            return $this->teacherProfile->hasRelatedRecords();
        }
        
        if ($this->isStudent() && $this->studentProfile) {
            return $this->studentProfile->hasRelatedRecords();
        }
        
        return false;
    }

    /**
     * Get the student profile associated with the user.
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }
}
