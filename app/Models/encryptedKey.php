<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncryptedKey extends Model
{
  use HasFactory;

  protected $table = 'encrypted_keys';

  protected $fillable = [
    'institution_id',
    'encrypted_private_key',
    'iv',
    'salt',
    'created_by',
  ];

  /**
   * Each encrypted key belongs to one institution.
   */
  public function institution()
  {
    return $this->belongsTo(Institution::class, 'institution_id', 'id');
  }
}
