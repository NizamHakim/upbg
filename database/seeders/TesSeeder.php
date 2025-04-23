<?php

namespace Database\Seeders;

use App\Models\KategoriTes;
use App\Models\Tes;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Ruangan;
use App\Models\TipeTes;
use Illuminate\Database\Seeder;

use function Illuminate\Log\log;

class TesSeeder extends Seeder
{
  public function run(): void
  {
    $english = KategoriTes::create(['nama' => 'English']);
    $foreign = KategoriTes::create(['nama' => 'Foreign Language']);

    TipeTes::create(['nama' => 'EFL', 'kode' => 'EFL', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'British Council English Score', 'kode' => 'BC', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'ITP', 'kode' => 'ITP', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'TOEIC', 'kode' => 'TOEIC', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'IELTS', 'kode' => 'IELTS', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'Sertifikasi Dosen', 'kode' => 'SERDOS', 'kategori_id' => $english->id]);
    TipeTes::create(['nama' => 'EIC', 'kode' => 'EIC', 'kategori_id' => $foreign->id]);
    TipeTes::create(['nama' => 'Bahasa Jepang', 'kode' => 'JP', 'kategori_id' => $foreign->id]);
    TipeTes::create(['nama' => 'Bahasa Mandarin', 'kode' => 'M', 'kategori_id' => $foreign->id]);
    TipeTes::create(['nama' => 'Bahasa Jerman', 'kode' => 'JE', 'kategori_id' => $foreign->id]);
    TipeTes::create(['nama' => 'Bahasa Arab', 'kode' => 'AR', 'kategori_id' => $foreign->id]);
    TipeTes::create(['nama' => 'Bahasa Prancis', 'kode' => 'F', 'kategori_id' => $foreign->id]);
  }
}
