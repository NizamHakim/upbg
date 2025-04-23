<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTes extends Model
{
  protected $fillable = [
    'nama',
  ];

  public function tipe()
  {
    return $this->hasMany(TipeTes::class);
  }
}
