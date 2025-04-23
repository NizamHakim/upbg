<?php

namespace App\Helpers;

class HariProvider
{
  public static function all()
  {
    return collect([
      (object) ['text' => 'Minggu', 'value' => 0],
      (object) ['text' => 'Senin', 'value' => 1],
      (object) ['text' => 'Selasa', 'value' => 2],
      (object) ['text' => 'Rabu', 'value' => 3],
      (object) ['text' => 'Kamis', 'value' => 4],
      (object) ['text' => 'Jumat', 'value' => 5],
      (object) ['text' => 'Sabtu', 'value' => 6],
    ]);
  }
}
