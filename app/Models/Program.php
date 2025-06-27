<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Program model - Placeholder class to prevent errors
 * This class doesn't actually interact with any database table
 */
class Program extends Model
{
    use HasFactory;
    
    // Prevent database operations
    protected $table = null;
    
    // Return empty collections for relationships
    public function students()
    {
        return collect([]);
    }
    
    // Static method to handle where calls safely
    public static function where()
    {
        return new class {
            public function orderBy() { return $this; }
            public function get() { return collect([]); }
        };
    }
    
    // Static method to handle find calls safely
    public static function find()
    {
        return null;
    }
}
