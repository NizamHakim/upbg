<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeKelas extends Model
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
  public function kelas()
  {
    return $this->hasMany(Kelas::class);
  }

  public function kategori()
  {
    return $this->belongsTo(KategoriKelas::class);
  }
}
