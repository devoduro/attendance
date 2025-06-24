<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status', // active, inactive, completed
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the classes associated with this academic year.
     */
    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'academic_year', 'name');
    }

    /**
     * Get the results associated with this academic year.
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Get the academic records associated with this academic year.
     */
    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class, 'academic_year', 'name');
    }
}
