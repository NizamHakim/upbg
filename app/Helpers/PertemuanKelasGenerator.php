<?php

namespace App\Helpers;

use App\Exceptions\SchedulingConflictException;
use App\Models\Kelas;
use App\Models\PertemuanKelas;
use App\Models\Tes;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;

use function Illuminate\Log\log;

class PertemuanKelasGenerator
{
  // public static function generate(Kelas $kelas): void
  // {
  //   $firstDay = $kelas->tanggal_mulai->dayOfWeek;
  //   $jadwal = $kelas->jadwal;

  //   while ($firstDay !== $jadwal[0]->hari) {
  //     $jadwal->push($jadwal->shift());
  //   }
  //   $jadwal->push($jadwal->shift());

  //   $tanggal = $kelas->tanggal_mulai->copy();
  //   $mod = count($jadwal);

  //   for ($i = 0; $i < $kelas->banyak_pertemuan; $i++) {
  //     $kelas->pertemuan()->create([
  //       'ruangan_id' => $kelas->ruangan_id,
  //       'pertemuan_ke' => $i + 1,
  //       'tanggal' => $tanggal,
  //       'waktu_mulai' => $jadwal[$i % $mod]->waktu_mulai,
  //       'waktu_selesai' => $jadwal[$i % $mod]->waktu_selesai,
  //       'terlaksana' => 0,
  //     ]);
  //     $tanggal->next($jadwal[$i % $mod]->hari);
  //   }
  // }

  // public static function generate(Kelas $kelas): void
  // {
  //   $firstDay = $kelas->tanggal_mulai->dayOfWeek;
  //   $jadwal = $kelas->jadwal;

  //   while ($firstDay !== $jadwal[0]->hari) {
  //     $jadwal->push($jadwal->shift());
  //   }
  //   $jadwal->push($jadwal->shift());

  //   $tanggal = $kelas->tanggal_mulai->copy();
  //   $mod = count($jadwal);

  //   for ($i = 0; $i < $kelas->banyak_pertemuan; $i++) {
  //     $waktuMulai = $jadwal[$i % $mod]->waktu_mulai;
  //     $waktuSelesai = $jadwal[$i % $mod]->waktu_selesai;

  //     // check for schedule conflict
  //     $conflictPertemuan = PertemuanKelas::where('ruangan_id', $kelas->ruangan_id)
  //       ->where('tanggal', $tanggal->format('Y-m-d'))
  //       ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
  //         $query->where('waktu_mulai', '<', $waktuSelesai)
  //           ->where('waktu_selesai', '>', $waktuMulai);
  //       })
  //       ->exists();

  //     $conflictTes = Tes::whereHas('ruangan', function ($query) use ($kelas) {
  //       $query->where('ruangan_id', $kelas->ruangan_id);
  //     })
  //       ->where('tanggal', $tanggal->format('Y-m-d'))
  //       ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
  //         $query->where('waktu_mulai', '<', $waktuSelesai)
  //           ->where('waktu_selesai', '>', $waktuMulai);
  //       })
  //       ->exists();

  //     if ($conflictPertemuan || $conflictTes) {
  //       throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$kelas->ruangan->kode}");
  //     }

  //     $kelas->pertemuan()->create([
  //       'ruangan_id' => $kelas->ruangan_id,
  //       'pertemuan_ke' => $i + 1,
  //       'tanggal' => $tanggal,
  //       'waktu_mulai' => $waktuMulai,
  //       'waktu_selesai' => $waktuSelesai,
  //       'terlaksana' => 0,
  //     ]);
  //     $tanggal->next($jadwal[$i % $mod]->hari);
  //   }
  // }

  public static function generate(Kelas $kelas): void
  {
    $firstDay = $kelas->tanggal_mulai->dayOfWeek;
    $jadwal = $kelas->jadwal;

    while ($firstDay !== $jadwal[0]->hari) {
      $jadwal->push($jadwal->shift());
    }
    $jadwal->push($jadwal->shift());

    $tanggal = $kelas->tanggal_mulai->copy();
    $mod = count($jadwal);

    for ($i = 0; $i < $kelas->banyak_pertemuan; $i++) {
      $waktuMulai = $jadwal[$i % $mod]->waktu_mulai;
      $waktuSelesai = $jadwal[$i % $mod]->waktu_selesai;

      if (SchedulingConflictChecker::checkForConflict($kelas->ruangan_id, $tanggal, $waktuMulai, $waktuSelesai)) {
        throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$kelas->ruangan->kode}");
      }

      $kelas->pertemuan()->create([
        'ruangan_id' => $kelas->ruangan_id,
        'pertemuan_ke' => $i + 1,
        'tanggal' => $tanggal,
        'waktu_mulai' => $waktuMulai,
        'waktu_selesai' => $waktuSelesai,
        'terlaksana' => 0,
      ]);
      $tanggal->next($jadwal[$i % $mod]->hari);
    }
  }
}
