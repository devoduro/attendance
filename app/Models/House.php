<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
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
        'color',
        'status'
    ];

    /**
     * Get the students belonging to this house.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Custom query scope for active houses
     * Since we don't have a real houses table, this is a placeholder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhere($query, $column, $value)
    {
        // If we're checking for active status, return a query that will count as 0
        if ($column === 'status' && $value === 'active') {
            return $query->whereRaw('1 = 0');
        }
        
        // Otherwise, use the parent method
        return parent::scopeWhere($query, $column, $value);
    }
}
