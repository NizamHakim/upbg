<?php

namespace App\Helpers;

class KodeKelasGenerator
{
  public static function generate($program, $tipe, $nomor, $level = null, $banyak_pertemuan, $tanggal_mulai): string
  {
    if ($level) {
      return "{$program}/{$tipe}.{$nomor}/{$level}/{$banyak_pertemuan}/" . self::toRoman($tanggal_mulai->month) . "/{$tanggal_mulai->year}";
    } else {
      return "{$program}/{$tipe}.{$nomor}/{$banyak_pertemuan}/" . self::toRoman($tanggal_mulai->month) . "/{$tanggal_mulai->year}";
    }
  }

  private static function toRoman(int $month): string
  {
    $array = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    return $array[$month - 1];
  }
}
