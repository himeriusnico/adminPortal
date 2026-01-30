<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name', 'institution_id'];

  public function institution()
  {
    return $this->belongsTo(Institution::class);
  }

  public function programStudies()
  {
    return $this->hasMany(ProgramStudy::class);
  }

  public function students()
  {
    return $this->hasMany(Student::class);
  }
}
