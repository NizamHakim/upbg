<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
  use HasFactory;
  protected $fillable = [
    'kode',
    'program_id',
    'tipe_id',
    'level_id',
    'ruangan_id',
    'nomor',
    'banyak_pertemuan',
    'tanggal_mulai',
    'group_link',
  ];

  //# Casting and Accessor
  protected function casts(): array
  {
    return [
      'tanggal_mulai' => 'date',
    ];
  }

  public function scopeStatus(Builder $query, string $status): void
  {
    switch ($status) {
      case 'completed':
        $query->whereColumn('progress', '>=', 'banyak_pertemuan');
        break;
      case 'in-progress':
        $query->whereColumn('progress', '<', 'banyak_pertemuan');
        break;
    }
  }

  public function scopeSort(Builder $query, string $sort): void
  {
    switch ($sort) {
      case 'tanggal-mulai-desc':
        $query->orderBy('tanggal_mulai', 'desc');
        break;
      case 'tanggal-mulai-asc':
        $query->orderBy('tanggal_mulai', 'asc');
        break;
      case 'kode-asc':
        $query->orderBy('kode', 'asc');
        break;
      case 'kode-desc':
        $query->orderBy('kode', 'desc');
        break;
    }
  }

  //? Relationship
  public function program()
  {
    return $this->belongsTo(ProgramKelas::class)->withTrashed();
  }

  public function tipe()
  {
    return $this->belongsTo(TipeKelas::class)->withTrashed();
  }

  public function level()
  {
    return $this->belongsTo(LevelKelas::class)->withTrashed();
  }

  public function ruangan()
  {
    return $this->belongsTo(Ruangan::class)->withTrashed();
  }

  public function jadwal()
  {
    return $this->hasMany(JadwalKelas::class)->orderBy('hari', 'asc');
  }

  public function peserta()
  {
    return $this->belongsToMany(Peserta::class, 'peserta_kelas', 'kelas_id', 'peserta_id')->withPivot('aktif')->withTimestamps();
  }

  public function pengajar()
  {
    return $this->belongsToMany(User::class, 'pengajar_kelas', 'kelas_id', 'user_id')->withTimestamps();
  }

  public function pertemuan()
  {
    return $this->hasMany(PertemuanKelas::class)->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
  }
}
