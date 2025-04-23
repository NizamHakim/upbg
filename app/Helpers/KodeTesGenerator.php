<?php

namespace App\Helpers;

class KodeTesGenerator
{
  public static function generate($tipe, $nama, $tanggal): string
  {
    return "{$tipe}/{$nama}/" . self::toRoman($tanggal->month) . "/{$tanggal->year}";
  }

  private static function toRoman(int $month): string
  {
    $array = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    return $array[$month - 1];
  }
}
