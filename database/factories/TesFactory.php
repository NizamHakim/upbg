<?php

namespace Database\Factories;

use App\Helpers\KodeTesGenerator;
use App\Models\TipeTes;
use Illuminate\Database\Eloquent\Factories\Factory;

class TesFactory extends Factory
{
  public function definition(): array
  {
    $waktu_umum = [
      ['mulai' => '08:30', 'selesai' => '11:00'],
      ['mulai' => '09:00', 'selesai' => '11:00'],
      ['mulai' => '13:00', 'selesai' => '15:00'],
    ];

    $tipe = TipeTes::inRandomOrder()->first();
    $nama = "Tes {$tipe->nama} " . fake()->unique()->numberBetween(1, 100);
    $tanggal = today()->addDays(fake()->numberBetween(1, 30));
    $waktu = fake()->randomElement($waktu_umum);

    $kode = KodeTesGenerator::generate($tipe->kode, $nama, $tanggal);

    return [
      'kode' => $kode,
      'tipe_id' => $tipe->id,
      'nama' => $nama,
      'tanggal' => $tanggal,
      'waktu_mulai' => $waktu['mulai'],
      'waktu_selesai' => $waktu['selesai'],
    ];
  }
}
