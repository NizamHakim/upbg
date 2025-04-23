<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $roles = Role::all();
    $superuser = Role::where('name', 'Superuser')->first();
    $adminPengajaran = Role::where('name', 'Admin Pengajaran')->first();
    $stafPengajar = Role::where('name', 'Staf Pengajar')->first();
    $adminTes = Role::where('name', 'Admin Tes')->first();
    $stafTes = Role::where('name', 'Staf Pengawas Tes')->first();
    $bagianKeuangan = Role::where('name', 'Bagian Keuangan')->first();

    $user001 = User::create([
      'name' => 'user001',
      'nickname' => 'user001',
      'nik' => '1',
      'email' => 'user001@its.ac.id',
      'password' => bcrypt('password'),
      'phone_number' => '000000000000',
    ]);
    $user001->assignRole($roles);
    $user001->update(['current_role_id' => '1']);

    $cahyani = User::create([
      'name' => 'Cahyani Satiya Pratiwi, S.S.',
      'nickname' => 'Yani',
      'nik' => '198512222014042001',
      'email' => 'cahyani@its.ac.id',
      'password' => bcrypt('198512222014042001'),
    ]);
    $cahyani->assignRole($superuser);
    $cahyani->update(['current_role_id' => $superuser->id]);

    $pungky = User::create([
      'name' => 'Pungky Mukti  Wibowo, SS',
      'nickname' => 'Pungky',
      'nik' => '198307032014091002',
      'email' => 'pungky@its.ac.id',
      'password' => bcrypt('198307032014091002'),
    ]);
    $pungky->assignRole($superuser);
    $pungky->update(['current_role_id' => $superuser->id]);

    $tri = User::create([
      'name' => 'Rr. Tri Astuti Saraswati, S.E.',
      'nickname' => 'Tri',
      'nik' => '197511122007012003',
      'email' => 'tari1211_bahasa@its.ac.id',
      'password' => bcrypt('197511122007012003'),
    ]);
    $tri->assignRole($bagianKeuangan);
    $tri->update(['current_role_id' => $bagianKeuangan->id]);

    $anik = User::create([
      'name' => 'Anik Amaliyah, S.Pd',
      'nickname' => 'Anik',
      'nik' => '197308122014092001',
      'email' => 'sachi08_bahasa@its.ac.id',
      'password' => bcrypt('197308122014092001'),
    ]);
    $anik->assignRole($stafPengajar);
    $anik->update(['current_role_id' => $stafPengajar->id]);

    $tika = User::create([
      'name' => 'Anandhayu Tika Kemala, S.Pd.',
      'nickname' => 'Tika',
      'nik' => '1983201822312',
      'email' => 'mala_bahasa@its.ac.id',
      'password' => bcrypt('1983201822312'),
    ]);
    $tika->assignRole([$adminTes, $stafTes]);
    $tika->update(['current_role_id' => $adminTes->id]);

    $dody = User::create([
      'name' => 'Dody Wicaksono, S.T.',
      'nickname' => 'Dody',
      'nik' => '1981201821098',
      'email' => 'dodi_bahasa@its.ac.id',
      'password' => bcrypt('1981201821098'),
    ]);
    $dody->assignRole($superuser);
    $dody->update(['current_role_id' => $superuser->id]);

    $teguh = User::create([
      'name' => 'M. Teguh Prasetiyo, S.Pd, M.Pd',
      'nickname' => 'Teguh',
      'nik' => '1989202441194',
      'email' => 'prasetiyo@its.ac.id',
      'password' => bcrypt('1989202441194'),
    ]);
    $teguh->assignRole([$adminTes, $stafTes]);
    $teguh->update(['current_role_id' => $adminTes->id]);

    $mirfat = User::create([
      'name' => 'Mirfat, S.Pd M.Pd',
      'nickname' => 'Mirfat',
      'nik' => '1982202441195',
      'email' => 'mirfat@its.ac.id',
      'password' => bcrypt('1982202441195'),
    ]);
    $mirfat->assignRole([$adminPengajaran, $stafPengajar]);
    $mirfat->update(['current_role_id' => $adminPengajaran->id]);

    $nafi = User::create([
      'name' => 'Nafilaturifa, S.Hum, M.Hum',
      'nickname' => 'Nafi',
      'nik' => '3515076305950003',
      'email' => 'nafila.turifah@gmail.com',
      'password' => bcrypt('3515076305950003'),
    ]);
    $nafi->assignRole([$adminPengajaran, $stafPengajar]);
    $nafi->update(['current_role_id' => $adminPengajaran->id]);

    $windy = User::create([
      'name' => 'Windy Ardianita Sari, M.Hum',
      'nickname' => 'Windy',
      'nik' => '3578104709930004',
      'email' => 'windyardianita@gmail.com',
      'password' => bcrypt('3578104709930004'),
    ]);
    $windy->assignRole([$adminPengajaran, $stafPengajar]);
    $windy->update(['current_role_id' => $adminPengajaran->id]);

    $meilisa = User::create([
      'name' => 'Meilisa Sindy Astika Ariyanto, S.Pd.',
      'nickname' => 'Meilisa',
      'nik' => '1994202442171',
      'email' => 'meilisa@its.ac.id',
      'password' => bcrypt('1994202442171'),
    ]);
    $meilisa->assignRole([$adminPengajaran, $stafPengajar]);
    $meilisa->update(['current_role_id' => $adminPengajaran->id]);
  }
}
