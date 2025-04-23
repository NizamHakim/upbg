<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKelas extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'nama',
    'kode',
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
      default:
        return $this->nama;
    }
  }

  //? Relationship
  public function kelas()
  {
    return $this->hasMany(Kelas::class);
  }
}
