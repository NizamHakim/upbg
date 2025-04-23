<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\TipeKelas;
use App\Models\LevelKelas;
use App\Models\JadwalKelas;
use App\Models\ProgramKelas;
use Illuminate\Database\Seeder;
use App\Helpers\PertemuanKelasGenerator;
use App\Models\KategoriKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
  public function run(): void
  {
    ProgramKelas::create(['nama' => 'Reguler', 'kode' => 'REG']);
    ProgramKelas::create(['nama' => 'Special Class', 'kode' => 'SP']);
    ProgramKelas::create(['nama' => 'IKOMA', 'kode' => 'IKOMA.REG']);

    $english = KategoriKelas::create(['nama' => 'English']);
    $foreign = KategoriKelas::create(['nama' => 'Foreign Language']);

    TipeKelas::create(['nama' => 'General English', 'kode' => 'GE', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'General English Apps', 'kode' => 'GE.Apps', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'TOEFL Preparation', 'kode' => 'TP', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'TOEIC Preparation', 'kode' => 'TOEIC', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'IELTS Preparation', 'kode' => 'IELTS', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'Duolingo Preparation', 'kode' => 'DUOLINGO', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'Conversation', 'kode' => 'C', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'Academic Writing', 'kode' => 'AW', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'Kids Class', 'kode' => 'K', 'kategori_id' => $english->id]);
    TipeKelas::create(['nama' => 'Bahasa Indonesia untuk Penutur Asing', 'kode' => 'BIPA', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'Japanese', 'kode' => 'JP', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'Mandarin', 'kode' => 'M', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'German', 'kode' => 'JE', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'Arabic', 'kode' => 'AR', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'French', 'kode' => 'F', 'kategori_id' => $foreign->id]);
    TipeKelas::create(['nama' => 'Korean', 'kode' => 'KR', 'kategori_id' => $foreign->id]);

    LevelKelas::create(['nama' => 'Beginner', 'kode' => 'B']);
    LevelKelas::create(['nama' => 'Elementary', 'kode' => 'E']);
    LevelKelas::create(['nama' => 'Intermediate', 'kode' => 'I']);
    LevelKelas::create(['nama' => 'Advanced', 'kode' => 'A']);
    LevelKelas::create(['nama' => 'BIS 1', 'kode' => 'BIS1']);
    LevelKelas::create(['nama' => 'BIS 2', 'kode' => 'BIS2']);
    LevelKelas::create(['nama' => 'BIS 3', 'kode' => 'BIS3']);
    LevelKelas::create(['nama' => 'BIAP 1', 'kode' => 'BIAP1']);
    LevelKelas::create(['nama' => 'BIAP 2', 'kode' => 'BIAP2']);
    LevelKelas::create(['nama' => 'BIAP 3', 'kode' => 'BIAP3']);
    LevelKelas::create(['nama' => '1', 'kode' => '1']);
    LevelKelas::create(['nama' => '2', 'kode' => '2']);
    LevelKelas::create(['nama' => '3', 'kode' => '3']);

    //# Create kelas then generate 1 jadwal sesuai tanggal mulai
    //# Coin flip, if true generate +1 jadwal else continue
  }
}
