<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;

class Tes extends Model
{
  use HasFactory;
  protected $fillable = [
    'kode',
    'tipe_id',
    'nama',
    'tanggal',
    'waktu_mulai',
    'waktu_selesai',
    'terlaksana',
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

  public static function statusOptions(): Collection
  {
    return collect([
      (object) ['text' => 'Concluded', 'value' => 'concluded'],
      (object) ['text' => 'In Progress', 'value' => 'in-progress'],
      (object) ['text' => 'Upcoming', 'value' => 'upcoming'],
    ]);
  }

  public static function sortByOptions(): Collection
  {
    return collect([
      (object) ['text' => 'Tanggal (Terbaru)', 'value' => 'tanggal-desc'],
      (object) ['text' => 'Tanggal (Terlama)', 'value' => 'tanggal-asc'],
      (object) ['text' => 'Kode Tes (A-Z)', 'value' => 'kode-asc'],
      (object) ['text' => 'Kode Tes (Z-A)', 'value' => 'kode-desc'],
    ]);
  }

  public function scopeStatus(Builder $query, string $status): void
  {
    switch ($status) {
      case 'concluded':
        $query->where('terlaksana', true)->whereDate('tanggal', '<=', Carbon::now())->whereTime('waktu_selesai', '<=', Carbon::now());
        break;
      case 'in-progress':
        $query->whereDate('tanggal', '=', Carbon::now())->whereTime('waktu_mulai', '<=', Carbon::now())->whereTime('waktu_selesai', '>=', Carbon::now());
        break;
      case 'upcoming':
        $query->where('terlaksana', false)->whereDate('tanggal', '>=', Carbon::now())->whereTime('waktu_mulai', '>=', Carbon::now());
        break;
    }
  }

  public function scopeSort(Builder $query, string $sort): void
  {
    switch ($sort) {
      case 'tanggal-desc':
        $query->orderBy('tanggal', 'desc')->orderBy('waktu_mulai', 'desc');
        break;
      case 'tanggal-asc':
        $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
        break;
      case 'kode-asc':
        $query->orderBy('kode', 'asc');
        break;
      case 'kode-desc':
        $query->orderBy('kode', 'desc');
        break;
    }
  }

  public function textFormat(string $format)
  {
    switch ($format) {
      case 'iso-tanggal':
        return $this->tanggal->isoFormat('dddd, D MMMM Y');
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
  public function tipe()
  {
    return $this->belongsTo(TipeTes::class, 'tipe_id')->withTrashed();
  }

  public function ruangan()
  {
    return $this->belongsToMany(Ruangan::class, 'ruangan_tes', 'tes_id', 'ruangan_id')->withTrashed()->withTimestamps();
  }

  public function pengawas()
  {
    return $this->belongsToMany(User::class, 'pengawas_tes', 'tes_id', 'user_id')->withTimestamps();
  }

  public function pivotPeserta()
  {
    return $this->hasMany(PesertaTes::class, 'tes_id')->orderBy('ruangan_id')->orderBy('peserta_id');
  }
}
