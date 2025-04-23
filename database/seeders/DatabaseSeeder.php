<?php

namespace Database\Seeders;

use App\Models\Tes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use App\Helpers\PertemuanKelasGenerator;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call([
      RolePermissionSeeder::class,
      UserSeeder::class,
      PesertaSeeder::class,
      RuanganSeeder::class,
      KelasSeeder::class,
      TesSeeder::class,
      ConfigLaporanSeeder::class,
    ]);
  }
}
