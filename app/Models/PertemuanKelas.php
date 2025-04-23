<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PertemuanKelas extends Model
{
  protected $fillable = [
    'kelas_id',
    'ruangan_id',
    'pengajar_id',
    'pertemuan_ke',
    'tanggal',
    'waktu_mulai',
    'waktu_selesai',
    'terlaksana',
    'topik',
    'catatan',
  ];

  //# Casting and Accessor
  protected function casts(): array
  {
    return [
      'tanggal' => 'date',
    ];
  }

  protected function waktuMulai(): Attribute
  {
    return Attribute::make(
      get: fn() => Carbon::createFromFormat('Y-m-d H:i:s', $this->tanggal->format('Y-m-d') . ' ' . $this->attributes['waktu_mulai']),
    );
  }

  protected function waktuSelesai(): Attribute
  {
    return Attribute::make(
      get: fn() => Carbon::createFromFormat('Y-m-d H:i:s', $this->tanggal->format('Y-m-d') . ' ' . $this->attributes['waktu_selesai']),
    );
  }

  protected function topik(): Attribute
  {
    return Attribute::make(
      get: fn($value) => nl2br(e($value)),
    );
  }

  protected function catatan(): Attribute
  {
    return Attribute::make(
      get: fn($value) => nl2br(e($value)),
    );
  }

  protected function hadirCount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->presensi->where('hadir', true)->count(),
    );
  }

  public function textFormat(string $format)
  {
    switch ($format) {
      case 'iso-tanggal':
        return $this->tanggal->isoFormat('dddd, D MMMM YYYY');
      case 'iso-waktu-mulai':
        return $this->waktu_mulai->format('H:i');
      case 'iso-waktu-selesai':
        return $this->waktu_selesai->format('H:i');
      case 'status':
        if ($this->terlaksana) {
          return '<p class="font-semibold text-green-600">Terlaksana</p>';
        } else {
          if (now()->isAfter($this->waktu_selesai)) {
            return '<p class="font-semibold text-red-600">Tidak Terlaksana</p>';
          } else {
            return '<p class="text-gray-700">-</p>';
          }
        }
    }
  }

  public function status()
  {
    if ($this->terlaksana) {
      return 'terlaksana';
    } else if (!$this->terlaksana && now()->isBefore($this->waktu_mulai)) {
      return 'belum-terlaksana';
    } else if (!$this->terlaksana && now()->isAfter($this->waktu_mulai) && now()->isBefore($this->waktu_selesai)) {
      return 'sedang-berlangsung';
    } else if (!$this->terlaksana && now()->isAfter($this->waktu_selesai)) {
      return 'tidak-terlaksana';
    }
  }

  //? Relationship
  public function kelas()
  {
    return $this->belongsTo(Kelas::class, 'kelas_id');
  }

  public function ruangan()
  {
    return $this->belongsTo(Ruangan::class, 'ruangan_id')->withTrashed();
  }

  public function pengajar()
  {
    return $this->belongsTo(User::class, 'pengajar_id');
  }

  public function presensi()
  {
    return $this->hasMany(PresensiKelas::class, 'pertemuan_id');
  }
}
