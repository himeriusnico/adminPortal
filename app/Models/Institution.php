<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'alamat', 'public_key', 'private_key_path', 'ca_cert'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
        
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
