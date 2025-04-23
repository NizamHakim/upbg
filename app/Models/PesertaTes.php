<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTes extends Model
{
  protected $fillable = [
    'tes_id',
    'peserta_id',
    'ruangan_id',
    'hadir',
  ];

  public function tes()
  {
    return $this->belongsTo(Tes::class, 'tes_id');
  }

  public function peserta()
  {
    return $this->belongsTo(Peserta::class, 'peserta_id');
  }

  public function ruangan()
  {
    return $this->belongsTo(Ruangan::class, 'ruangan_id');
  }
}
