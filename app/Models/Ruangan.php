<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
  use SoftDeletes;
  protected $table = 'ruangan';
  protected $fillable = [
    'kode',
    'kapasitas',
  ];

  //# Casting and Accessor
  public function textFormat(string $format)
  {
    switch ($format) {
      case 'option':
        return "{$this->kode} ({$this->kapasitas} orang)";
    }
  }

  //? Relationship
  public function kelas()
  {
    return $this->hasMany(Kelas::class);
  }

  public function pertemuan()
  {
    return $this->hasMany(PertemuanKelas::class);
  }

  public function tes()
  {
    return $this->belongsToMany(Tes::class, 'ruangan_tes', 'ruangan_id', 'tes_id')->withTimestamps();
  }

  public function pesertaTes()
  {
    return $this->hasMany(PesertaTes::class);
  }
}
