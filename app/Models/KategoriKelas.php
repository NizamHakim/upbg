<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKelas extends Model
{
  protected $fillable = [
    'nama',
  ];

  public function tipe()
  {
    return $this->hasMany(TipeKelas::class);
  }
}
