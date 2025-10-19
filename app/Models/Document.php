<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'pegawais_id',
        'institution_id',
        'filename',
        'document_type',
        'hash',
        'signature',
        'tx_id',
        'file_path',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawais_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}