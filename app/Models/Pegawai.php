<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Sesuaikan fillable dengan kolom yang relevan
    protected $fillable = [
        'users_id', 
        'institution_id', 
        'employee_id', 
        'position', 
        'department', 
        'status'
    ];

    public function user()
    {
        // Perhatikan foreign key 'users_id'
        return $this->belongsTo(User::class, 'users_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function signedDocuments()
    {
        return $this->hasMany(Document::class, 'pegawais_id');
    }
}