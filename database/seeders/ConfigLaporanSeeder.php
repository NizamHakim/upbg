<?php

namespace Database\Seeders;

use App\Models\ConfigLaporan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigLaporanSeeder extends Seeder
{
  public function run(): void
  {
    ConfigLaporan::create([
      'group' => 'Kop',
      'data' => [
        'Kementrian' => 'KEMENTRIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI',
        'ITS' => 'INSTITUT TEKNOLOGI SEPULUH NOPEMBER',
        'UPBG' => 'UNIT PUSAT BAHASA GLOBAL',
        'Alamat' => 'Kampus ITS, Sukolilo, Surabaya 60111',
        'Kontak' => 'Telepon: 031 5990322 PABX: 1221 Fax: 031 5990322',
        'Website' => 'https://www.its.ac.id/bahasa/ email: bahasa@its.ac.id'
      ]
    ]);

    ConfigLaporan::create([
      'group' => 'Tanda Tangan',
      'data' => [
        'Jabatan' => 'Ka. Unit Pusat Bahasa Global',
        'Nama Kepala UPBG' => 'Cahyani Satiya Pratiwi',
        'NIP Kepala UPBG' => 'NIP 198512222014042001',
      ]
    ]);

    ConfigLaporan::create([
      'group' => 'Kode Laporan',
      'data' => [
        'Laporan Kelas' => 'FM - CLC.AK-04 / REV.06',
        'Laporan Tes' => 'FM - CLC.TS-17 / REV.04',
        'Absen Tes' => 'FM-CLC.TS-02 / Rev.05'
      ]
    ]);
  }
}
