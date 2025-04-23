<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuanganSeeder extends Seeder
{
  public function run(): void
  {
    Ruangan::create(['kode' => 'R.1', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.2', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.3', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.4', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.5', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.6', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.8', 'kapasitas' => 15]);
    Ruangan::create(['kode' => 'R.BTI', 'kapasitas' => 32]);
    Ruangan::create(['kode' => 'R.International', 'kapasitas' => 30]);
  }
}
