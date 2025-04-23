<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeTes extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'nama',
    'kode',
    'kategori_id',
  ];

  //# Casting and Accessor
  public function scopeAktif($query)
  {
    return $query->where('status', 1);
  }

  public function textFormat(string $format)
  {
    switch ($format) {
      case 'option':
        return "{$this->nama} ({$this->kode})";
    }
  }

  //? Relationship
  public function tes()
  {
    return $this->hasMany(Tes::class, 'tipe_id');
  }

  public function kategori()
  {
    return $this->belongsTo(KategoriTes::class);
  }
}
