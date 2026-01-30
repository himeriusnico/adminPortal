<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudy extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name', 'faculty_id', 'university_id'];

  public function faculty()
  {
    return $this->belongsTo(Faculty::class);
  }

  public function institution()
  {
    return $this->belongsTo(Institution::class, 'university_id', 'id');
  }

  public function students()
  {
    return $this->hasMany(Student::class);
  }
}
