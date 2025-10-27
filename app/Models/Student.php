<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    // Sesuaikan fillable dengan kolom yang relevan
    protected $fillable = [
        'user_id', 
        'institution_id', 
        'student_id', 
        'phone',
        'program_study', 
        'faculty', 
        'entry_year', 
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}