<?php

namespace Database\Factories;

use App\Models\Ruangan;
use App\Models\TipeKelas;
use App\Models\LevelKelas;
use App\Models\ProgramKelas;
use App\Helpers\KodeKelasGenerator;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
  public function definition(): array
  {
    $program = ProgramKelas::inRandomOrder()->first();
    $tipe = TipeKelas::inRandomOrder()->first();
    $level = ($tipe->kode == 'C') ? null : LevelKelas::inRandomOrder()->first();
    $ruangan = Ruangan::inRandomOrder()->first();
    $nomor = strval(fake()->numberBetween(1, 10));
    $banyak_pertemuan = fake()->randomElement([16, 24]);
    $tanggal_mulai = today();
    $group_link = 'https://chat.whatsapp.com';

    $kode = KodeKelasGenerator::generate($program->kode, $tipe->kode, $nomor, $level?->kode, $banyak_pertemuan, $tanggal_mulai);

    return [
      'kode' => $kode,
      'program_id' => $program->id,
      'tipe_id' => $tipe->id,
      'level_id' => $level?->id,
      'ruangan_id' => $ruangan->id,
      'nomor' => $nomor,
      'banyak_pertemuan' => $banyak_pertemuan,
      'tanggal_mulai' => $tanggal_mulai,
      'group_link' => $group_link,
    ];
  }
}
