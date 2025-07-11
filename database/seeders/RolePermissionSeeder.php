<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
  public function run(): void
  {
    $superuser = Role::create(['name' => 'Superuser']);
    $adminPengajaran = Role::create(['name' => 'Admin Pengajaran']);
    $stafPengajar = Role::create(['name' => 'Staf Pengajar']);
    $adminTes = Role::create(['name' => 'Admin Tes']);
    $stafTes = Role::create(['name' => 'Staf Pengawas Tes']);
    $bagianKeuangan = Role::create(['name' => 'Bagian Keuangan']);

    $aksesSemuaKelas = Permission::create(['name' => 'akses-semua-kelas']);
    $lihatDaftarKelas = Permission::create(['name' => 'lihat-daftar-kelas']);
    $lihatDetailKelas = Permission::create(['name' => 'lihat-detail-kelas']);
    $tambahKelas = Permission::create(['name' => 'tambah-kelas']);
    $editKelas = Permission::create(['name' => 'edit-kelas']);
    $hapusKelas = Permission::create(['name' => 'hapus-kelas']);
    $kelolaKelas = Permission::create(['name' => 'kelola-kelas']);
    $lihatDaftarPeserta = Permission::create(['name' => 'lihat-daftar-peserta']);
    $kelolaPesertaKelas = Permission::create(['name' => 'kelola-peserta-kelas']);
    $buatLaporanKelas = Permission::create(['name' => 'buat-laporan-kelas']);

    // Pertemuan related
    $tambahPertemuan = Permission::create(['name' => 'tambah-pertemuan']);
    $mulaiPertemuan = Permission::create(['name' => 'mulai-pertemuan']);
    $editPertemuan = Permission::create(['name' => 'edit-pertemuan']);
    $reschedulePertemuan = Permission::create(['name' => 'reschedule-pertemuan']);
    $hapusPertemuan = Permission::create(['name' => 'hapus-pertemuan']);
    $editTopikCatatan = Permission::create(['name' => 'edit-topik-catatan']);

    // Presensi related
    $tambahPresensiKelas = Permission::create(['name' => 'tambah-presensi-kelas']);
    $ubahPresensiKelas = Permission::create(['name' => 'ubah-presensi-kelas']);
    $hapusPresensiKelas = Permission::create(['name' => 'hapus-presensi-kelas']);

    // Tes related
    $aksesSemuaTes = Permission::create(['name' => 'akses-semua-tes']);
    $lihatDaftarTes = Permission::create(['name' => 'lihat-daftar-tes']);
    $lihatDetailTes = Permission::create(['name' => 'lihat-detail-tes']);
    $tambahTes = Permission::create(['name' => 'tambah-tes']);
    $editTes = Permission::create(['name' => 'edit-tes']);
    $hapusTes = Permission::create(['name' => 'hapus-tes']);
    $mulaiTes = Permission::create(['name' => 'mulai-tes']);
    $kelolaTes = Permission::create(['name' => 'kelola-tes']);
    $updatePresensiTes = Permission::create(['name' => 'update-presensi-tes']);
    $kelolaPesertaTes = Permission::create(['name' => 'kelola-peserta-tes']);
    $buatLaporanTes = Permission::create(['name' => 'buat-laporan-tes']);

    // Ruangan related
    $kelolaRuangan = Permission::create(['name' => 'kelola-ruangan']);

    // User related
    $lihatUser = Permission::create(['name' => 'lihat-user']);
    $kelolaUserPengajaran = Permission::create(['name' => 'kelola-user-pengajaran']);
    $kelolaUserTes = Permission::create(['name' => 'kelola-user-tes']);
    $kelolaUserKeuangan = Permission::create(['name' => 'kelola-user-keuangan']);
    $kelolaUserSuperuser = Permission::create(['name' => 'kelola-user-superuser']);
    $hapusUser = Permission::create(['name' => 'hapus-user']);
    $resetPassword = Permission::create(['name' => 'reset-password']);

    // Peserta related
    $lihatPeserta = Permission::create(['name' => 'lihat-peserta']);
    $kelolaPeserta = Permission::create(['name' => 'kelola-peserta']);
    $buatLaporanPeserta = Permission::create(['name' => 'buat-laporan-peserta']);

    // Laporan related
    $kelolaLaporan = Permission::create(['name' => 'kelola-laporan']);

    // Assign permissions to roles
    $superuser->givePermissionTo([
      $aksesSemuaKelas,
      $lihatDaftarKelas,
      $lihatDetailKelas,
      $tambahKelas,
      $editKelas,
      $hapusKelas,
      $kelolaKelas,
      $lihatDaftarPeserta,
      $kelolaPesertaKelas,
      $tambahPertemuan,
      $mulaiPertemuan,
      $editPertemuan,
      $hapusPertemuan,
      $editTopikCatatan,
      $tambahPresensiKelas,
      $ubahPresensiKelas,
      $hapusPresensiKelas,
      $lihatUser,
      $kelolaUserPengajaran,
      $lihatPeserta,
      $kelolaPeserta,
      $buatLaporanPeserta,
      $aksesSemuaTes,
      $lihatDaftarTes,
      $lihatDetailTes,
      $tambahTes,
      $editTes,
      $hapusTes,
      $mulaiTes,
      $kelolaTes,
      $updatePresensiTes,
      $kelolaPesertaTes,
      $kelolaUserTes,
      $kelolaRuangan,
      $buatLaporanKelas,
      $buatLaporanTes,
      $kelolaLaporan,
      $kelolaUserKeuangan,
      $kelolaUserSuperuser,
      $hapusUser,
      $resetPassword,
    ]);

    $adminPengajaran->givePermissionTo([
      $aksesSemuaKelas,
      $lihatDaftarKelas,
      $lihatDetailKelas,
      $tambahKelas,
      $editKelas,
      $hapusKelas,
      $kelolaKelas,
      $lihatDaftarPeserta,
      $kelolaPesertaKelas,
      $tambahPertemuan,
      $mulaiPertemuan,
      $editPertemuan,
      $hapusPertemuan,
      $editTopikCatatan,
      $tambahPresensiKelas,
      $ubahPresensiKelas,
      $hapusPresensiKelas,
      $lihatUser,
      $kelolaUserPengajaran,
      $lihatPeserta,
      $kelolaPeserta,
      $kelolaRuangan,
    ]);

    $stafPengajar->givePermissionTo([
      $lihatDaftarKelas,
      $lihatDetailKelas,
      $lihatDaftarPeserta,
      $tambahPertemuan,
      $mulaiPertemuan,
      $reschedulePertemuan,
      $hapusPertemuan,
      $editTopikCatatan,
      $tambahPresensiKelas,
      $ubahPresensiKelas,
      $hapusPresensiKelas,
    ]);

    $adminTes->givePermissionTo([
      $aksesSemuaTes,
      $lihatDaftarTes,
      $lihatDetailTes,
      $tambahTes,
      $editTes,
      $hapusTes,
      $mulaiTes,
      $kelolaTes,
      $updatePresensiTes,
      $kelolaPesertaTes,
      $lihatUser,
      $kelolaUserTes,
      $lihatPeserta,
      $kelolaPeserta,
      $kelolaRuangan,
    ]);

    $stafTes->givePermissionTo([
      $lihatDaftarTes,
      $lihatDetailTes,
      $mulaiTes,
      $updatePresensiTes,
    ]);

    $bagianKeuangan->givePermissionTo([
      $buatLaporanKelas,
      $buatLaporanTes,
      $buatLaporanPeserta,
      $kelolaLaporan,
      $lihatUser,
      $kelolaUserKeuangan,
      $lihatPeserta,
      $kelolaPeserta,
    ]);
  }
}
