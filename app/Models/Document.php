<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'institution_id',
        'filename',
        'document_type_id',
        // 'document_type',
        'hash',
        'signature',
        'tx_id',
        'file_path'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
