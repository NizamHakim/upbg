<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalKelasFactory extends Factory
{
  public function definition(): array
  {
    $waktu_umum = [
      ['mulai' => '07:30', 'selesai' => '09:00'],
      ['mulai' => '09:00', 'selesai' => '10:30'],
      ['mulai' => '10:30', 'selesai' => '12:00'],
      ['mulai' => '13:00', 'selesai' => '14:30'],
      ['mulai' => '14:30', 'selesai' => '16:00'],
      ['mulai' => '16:00', 'selesai' => '17:30'],
      ['mulai' => '18:30', 'selesai' => '20:00'],
    ];
    $waktu = fake()->randomElement($waktu_umum);

    return [
      'waktu_mulai' => $waktu['mulai'],
      'waktu_selesai' => $waktu['selesai'],
    ];
  }
}
