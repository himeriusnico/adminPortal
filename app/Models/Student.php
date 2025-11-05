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
        'faculty_id',          
        'program_study_id',    
        'phone',
        'entry_year',
        'status',
        'graduation_date'      
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

    public function programStudy()
    {
        return $this->belongsTo(ProgramStudy::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
