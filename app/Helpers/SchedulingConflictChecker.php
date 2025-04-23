<?php

namespace App\Helpers;

use App\Models\Tes;
use App\Models\PertemuanKelas;
use Illuminate\Support\Carbon;

class SchedulingConflictChecker
{
  public static function checkForConflict($ruanganId, $tanggal, $waktuMulai, $waktuSelesai, $exceptPertemuanId = null, $exceptTesId = null): bool
  {
    if (!$tanggal instanceof Carbon) {
      $tanggal = Carbon::parse($tanggal);
    }

    $conflictPertemuan = PertemuanKelas::where('ruangan_id', $ruanganId)
      ->where('tanggal', $tanggal->format('Y-m-d'))
      ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
        $query->where('waktu_mulai', '<', $waktuSelesai)
          ->where('waktu_selesai', '>', $waktuMulai);
      });

    $conflictPertemuan = $conflictPertemuan->when($exceptPertemuanId, function ($query) use ($exceptPertemuanId) {
      return $query->where('id', '!=', $exceptPertemuanId);
    })->exists();

    $conflictTes = Tes::whereHas('ruangan', function ($query) use ($ruanganId) {
      $query->where('ruangan_id', $ruanganId);
    })
      ->where('tanggal', $tanggal->format('Y-m-d'))
      ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
        $query->where('waktu_mulai', '<', $waktuSelesai)
          ->where('waktu_selesai', '>', $waktuMulai);
      });

    $conflictTes = $conflictTes->when($exceptTesId, function ($query) use ($exceptTesId) {
      return $query->where('id', '!=', $exceptTesId);
    })->exists();

    return $conflictPertemuan || $conflictTes;
  }
}
