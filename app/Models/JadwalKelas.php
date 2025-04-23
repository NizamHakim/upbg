<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalKelas extends Model
{
  use HasFactory;
  protected $fillable = [
    'kelas_id',
    'hari',
    'waktu_mulai',
    'waktu_selesai',
  ];

  //# Casting and Accessor
  protected function casts(): array
  {
    return [
      'waktu_mulai' => 'datetime:H:i',
      'waktu_selesai' => 'datetime:H:i',
    ];
  }

  public function textFormat(string $format)
  {
    switch ($format) {
      case 'nama-hari':
        $hariArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $hariArr[$this->hari];
      case 'iso-waktu-mulai':
        return $this->waktu_mulai->format('H:i');
      case 'iso-waktu-selesai':
        return $this->waktu_selesai->format('H:i');
    }
  }

  //? Relationship
  public function kelas()
  {
    return $this->belongsTo(Kelas::class);
  }
}
