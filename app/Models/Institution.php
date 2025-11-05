<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'alamat', 'public_key', 'ca_cert'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function programStudies()
    {
        return $this->hasMany(ProgramStudy::class, 'university_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
