<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function pegawai()
    {
        // Perhatikan foreign key 'users_id' sesuai skema Anda
        return $this->hasOne(Pegawai::class, 'users_id');
    }
}
