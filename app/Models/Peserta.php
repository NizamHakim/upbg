<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
  use HasFactory;
  protected $table = 'peserta';
  protected $fillable = [
    'nik',
    'nama',
    'occupation',
  ];

  //# Casting and Accessor
  public function textFormat(string $format)
  {
    switch ($format) {
      case 'option':
        return "{$this->nama} ({$this->nik})";
    }
  }

  //? Relationship
  public function kelas()
  {
    return $this->belongsToMany(Kelas::class, 'peserta_kelas', 'peserta_id', 'kelas_id')->withPivot('aktif')->withTimestamps();
  }

  public function presensiKelas()
  {
    return $this->hasMany(PresensiKelas::class, 'peserta_id');
  }

  public function pivotTes()
  {
    return $this->hasMany(PesertaTes::class, 'peserta_id');
  }
}
