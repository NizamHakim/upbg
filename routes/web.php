<?php

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\TipeTesController;
use App\Http\Controllers\TipeKelasController;
use App\Http\Controllers\LevelKelasController;
use App\Http\Controllers\KategoriTesController;
use App\Http\Controllers\ProgramKelasController;
use App\Http\Controllers\KategoriKelasController;
use App\Http\Controllers\PresensiKelasController;
use App\Http\Controllers\PertemuanKelasController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
  if (Auth::check()) {
    return redirect()->route('dashboard');
  }
  return redirect()->route('login');
});

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/jadwal/kelas/{pertemuanId}', [JadwalController::class, 'jadwalKelas'])->name('jadwal.kelas');
Route::get('/jadwal/tes/{tesId}', [JadwalController::class, 'jadwalTes'])->name('jadwal.tes');


Route::middleware('auth')->group(function () {
  // Dashboard
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Profile
  Route::get('/akun', [ProfileController::class, 'index'])->name('akun.index');
  Route::post('/akun/update-profile-picture', [ProfileController::class, 'updateProfilePicture'])->name('akun.update-profile-picture');
  Route::patch('/akun/update-nama', [ProfileController::class, 'updateNama'])->name('akun.update-nama');
  Route::patch('/akun/update-nik', [ProfileController::class, 'updateNIK'])->name('akun.update-nik');
  Route::patch('/akun/update-no-hp', [ProfileController::class, 'updateNoHP'])->name('akun.update-no-hp');
  Route::patch('/akun/update-email', [ProfileController::class, 'updateEmail'])->name('akun.update-email');
  Route::patch('/akun/update-password', [ProfileController::class, 'updatePassword'])->name('akun.update-password');

  // User
  Route::get('/user', [UserController::class, 'index'])->name('user.index');
  Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
  Route::post('/user', [UserController::class, 'store'])->name('user.store');
  Route::get('/user/{userId}', [UserController::class, 'detail'])->name('user.detail');
  Route::put('/user/{userId}', [UserController::class, 'update'])->name('user.update');
  Route::delete('/user/{userId}', [UserController::class, 'destroy'])->name('user.destroy');
  Route::patch('/user/{userId}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');

  // Peserta
  Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
  Route::get('/peserta/{pesertaId}', [PesertaController::class, 'detail'])->name('peserta.detail');
  Route::put('/peserta/{pesertaId}', [PesertaController::class, 'update'])->name('peserta.update');
  Route::delete('/peserta/{pesertaId}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

  // Program, Kategori , Tipe, Level Kelas
  Route::get('/program-kelas', [ProgramKelasController::class, 'index'])->name('program-kelas.index');
  Route::post('/program-kelas', [ProgramKelasController::class, 'store'])->name('program-kelas.store');
  Route::put('/program-kelas/{programId}', [ProgramKelasController::class, 'update'])->name('program-kelas.update');
  Route::delete('/program-kelas/{programId}', [ProgramKelasController::class, 'destroy'])->name('program-kelas.destroy');
  Route::get('/program-kelas/deleted', [ProgramKelasController::class, 'indexRestore'])->name('program-kelas.deleted');
  Route::patch('/program-kelas/{programId}/restore', [ProgramKelasController::class, 'restore'])->name('program-kelas.restore');

  Route::get('/kategori-kelas', [KategoriKelasController::class, 'index'])->name('kategori-kelas.index');
  Route::post('/kategori-kelas', [KategoriKelasController::class, 'store'])->name('kategori-kelas.store');
  Route::put('/kategori-kelas/{kategoriId}', [KategoriKelasController::class, 'update'])->name('kategori-kelas.update');
  Route::delete('/kategori-kelas/{kategoriId}', [KategoriKelasController::class, 'destroy'])->name('kategori-kelas.destroy');

  Route::get('/tipe-kelas', [TipeKelasController::class, 'index'])->name('tipe-kelas.index');
  Route::post('/tipe-kelas', [TipeKelasController::class, 'store'])->name('tipe-kelas.store');
  Route::put('/tipe-kelas/{tipeId}', [TipeKelasController::class, 'update'])->name('tipe-kelas.update');
  Route::delete('/tipe-kelas/{tipeId}', [TipeKelasController::class, 'destroy'])->name('tipe-kelas.destroy');
  Route::get('/tipe-kelas/deleted', [TipeKelasController::class, 'indexRestore'])->name('tipe-kelas.deleted');
  Route::patch('/tipe-kelas/{tipeId}/restore', [TipeKelasController::class, 'restore'])->name('tipe-kelas.restore');

  Route::get('/level-kelas', [LevelKelasController::class, 'index'])->name('level-kelas.index');
  Route::post('/level-kelas', [LevelKelasController::class, 'store'])->name('level-kelas.store');
  Route::put('/level-kelas/{levelId}', [LevelKelasController::class, 'update'])->name('level-kelas.update');
  Route::delete('/level-kelas/{levelId}', [LevelKelasController::class, 'destroy'])->name('level-kelas.destroy');
  Route::get('/level-kelas/deleted', [LevelKelasController::class, 'indexRestore'])->name('level-kelas.deleted');
  Route::patch('/level-kelas/{levelId}/restore', [LevelKelasController::class, 'restore'])->name('level-kelas.restore');

  // Tipe, Kategori Tes
  Route::get('/tipe-tes', [TipeTesController::class, 'index'])->name('tipe-tes.index');
  Route::post('/tipe-tes', [TipeTesController::class, 'store'])->name('tipe-tes.store');
  Route::put('/tipe-tes/{tipeId}', [TipeTesController::class, 'update'])->name('tipe-tes.update');
  Route::delete('/tipe-tes/{tipeId}', [TipeTesController::class, 'destroy'])->name('tipe-tes.destroy');
  Route::get('/tipe-tes/deleted', [TipeTesController::class, 'indexRestore'])->name('tipe-tes.deleted');
  Route::patch('/tipe-tes/{tipeId}/restore', [TipeTesController::class, 'restore'])->name('tipe-tes.restore');

  Route::get('/kategori-tes', [KategoriTesController::class, 'index'])->name('kategori-tes.index');
  Route::post('/kategori-tes', [KategoriTesController::class, 'store'])->name('kategori-tes.store');
  Route::put('/kategori-tes/{kategoriId}', [KategoriTesController::class, 'update'])->name('kategori-tes.update');
  Route::delete('/kategori-tes/{kategoriId}', [KategoriTesController::class, 'destroy'])->name('kategori-tes.destroy');

  // Ruangan
  Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
  Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
  Route::put('/ruangan/{ruanganId}', [RuanganController::class, 'update'])->name('ruangan.update');
  Route::delete('/ruangan/{ruanganId}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
  Route::get('/ruangan/deleted', [RuanganController::class, 'indexRestore'])->name('ruangan.deleted');
  Route::patch('/ruangan/{ruanganId}/restore', [RuanganController::class, 'restore'])->name('ruangan.restore');

  // Kelas
  Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
  Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
  Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');

  Route::get('/kelas/{kelasId}', [KelasController::class, 'detailPertemuan'])->name('kelas.detail-pertemuan');
  Route::get('/kelas/{kelasId}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
  Route::put('/kelas/{kelasId}', [KelasController::class, 'update'])->name('kelas.update');
  Route::delete('/kelas/{kelasId}', [KelasController::class, 'destroy'])->name('kelas.destroy');
  Route::get('/kelas/{kelasId}/peserta', [KelasController::class, 'daftarPeserta'])->name('kelas.daftar-peserta');

  Route::get('/kelas/{kelasId}/peserta/tambah', [KelasController::class, 'tambahPeserta'])->name('kelas.tambah-peserta');
  Route::post('/kelas/{kelasId}/peserta', [KelasController::class, 'storePeserta'])->name('kelas.store-peserta');
  Route::patch('/kelas/{kelasId}/peserta/{pesertaId}', [KelasController::class, 'updatePeserta'])->name('kelas.update-peserta');
  Route::delete('/kelas/{kelasId}/peserta/{pesertaId}', [KelasController::class, 'destroyPeserta'])->name('kelas.destroy-peserta');

  // Pertemuan Kelas
  Route::post('/kelas/{kelasId}/pertemuan', [PertemuanKelasController::class, 'store'])->name('pertemuan.store');
  Route::get('/kelas/{kelasId}/pertemuan/{pertemuanId}', [PertemuanKelasController::class, 'detail'])->name('pertemuan.detail');
  Route::get('/kelas/{kelasId}/pertemuan/{pertemuanId}/edit', [PertemuanKelasController::class, 'edit'])->name('pertemuan.edit');
  Route::put('/kelas/{kelasId}/pertemuan/{pertemuanId}', [PertemuanKelasController::class, 'update'])->name('pertemuan.update');
  Route::patch('/kelas/{kelasId}/pertemuan/{pertemuanId}/mulai-pertemuan', [PertemuanKelasController::class, 'mulaiPertemuan'])->name('pertemuan.mulai');
  Route::patch('/kelas/{kelasId}/pertemuan/{pertemuanId}/reschedule-pertemuan', [PertemuanKelasController::class, 'reschedulePertemuan'])->name('pertemuan.reschedule');
  Route::delete('/kelas/{kelasId}/pertemuan/{pertemuanId}', [PertemuanKelasController::class, 'destroy'])->name('pertemuan.destroy');
  Route::patch('/kelas/{kelasId}/pertemuan/{pertemuanId}/topik-catatan', [PertemuanKelasController::class, 'updateTopikCatatan'])->name('pertemuan.update-topik-catatan');

  // Presensi Kelas
  Route::post('/kelas/{kelasId}/pertemuan/{pertemuanId}', [PresensiKelasController::class, 'store'])->name('presensi.store');
  Route::patch('/kelas/{kelasId}/pertemuan/{pertemuanId}/presensi/{presensiId}', [PresensiKelasController::class, 'togglePresensi'])->name('presensi.toggle');
  Route::put('/kelas/{kelasId}/pertemuan/{pertemuanId}/hadir-semua', [PresensiKelasController::class, 'hadirSemua'])->name('presensi.hadir-semua');
  Route::delete('/kelas/{kelasId}/pertemuan/{pertemuanId}/presensi/{presensiId}', [PresensiKelasController::class, 'destroy'])->name('presensi.destroy');

  // Tes
  Route::get('/tes', [TesController::class, 'index'])->name('tes.index');
  Route::get('/tes/create', [TesController::class, 'create'])->name('tes.create');
  Route::post('/tes', [TesController::class, 'store'])->name('tes.store');
  Route::get('/tes/{tesId}', [TesController::class, 'detail'])->name('tes.detail');
  Route::patch('/tes/{tesId}/mulai-tes', [TesController::class, 'mulaiTes'])->name('tes.mulai');
  Route::patch('tes/{tesId}/presensi/{presensiId}', [TesController::class, 'updatePresensi'])->name('tes.presensi');
  Route::get('/tes/{tesId}/edit', [TesController::class, 'edit'])->name('tes.edit');
  Route::put('/tes/{tesId}', [TesController::class, 'updateDetail'])->name('tes.updateDetail');
  Route::delete('/tes/{tesId}', [TesController::class, 'destroy'])->name('tes.destroy');

  Route::get('/tes/{tesId}/peserta', [TesController::class, 'daftarPeserta'])->name('tes.daftar-peserta');
  Route::patch('/tes/{tesId}/peserta/{pesertaId}', [TesController::class, 'updateRuangan'])->name('tes.update-ruangan');
  Route::patch('tes/{tesId}/update-ruangan-batch', [TesController::class, 'updateRuanganBatch'])->name('tes.update-ruangan-batch');
  Route::get('/tes/{tesId}/tambah-peserta', [TesController::class, 'createPeserta'])->name('tes.create-peserta');
  Route::post('/tes/{tesId}/peserta', [TesController::class, 'storePeserta'])->name('tes.store-peserta');
  Route::delete('/tes/{tesId}/peserta/{pesertaId}', [TesController::class, 'destroyPeserta'])->name('tes.destroy-peserta');
  Route::get('/tes/{tesId}/edit-peserta', [TesController::class, 'editPeserta'])->name('tes.edit-peserta');
  Route::get('/tes/{tesId}/download-ruangan', [TesController::class, 'downloadPembagianRuangan'])->name('tes.download-ruangan');

  // Laporan
  Route::get('/laporan-kelas', [LaporanController::class, 'laporanKelas'])->name('laporan.kelas');
  Route::post('/laporan-kelas/export', [LaporanController::class, 'exportKelas'])->name('laporan.export-kelas');
  Route::get('/laporan-departemen', [LaporanController::class, 'laporanDepartemen'])->name('laporan.departemen');
  Route::post('/laporan-departemen/export', [LaporanController::class, 'exportDepartemen'])->name('laporan.export-departemen');
  Route::get('/laporan-tes', [LaporanController::class, 'laporanTes'])->name('laporan.tes');
  Route::post('/laporan-tes/export', [LaporanController::class, 'exportTes'])->name('laporan.export-tes');
  Route::get('/kelola-laporan', [LaporanController::class, 'kelolaLaporan'])->name('laporan.kelola');
  Route::put('/kelola-laporan/{configId}/{itemKey}', [LaporanController::class, 'updateConfig'])->name('laporan.update-config');
  Route::get('/find-departemen', [LaporanController::class, 'findDepartemen'])->name('laporan.find-departemen');
  Route::get('/find-mahasiswa', [LaporanController::class, 'findMahasiswa'])->name('laporan.find-mahasiswa');

  // Dokumentasi
  Route::get('/dokumentasi', function () {
    $permissions = [];
    $perm = Permission::all();
    foreach ($perm as $p) {
      $roles = Role::whereHas('permissions', function (Builder $query) use ($p) {
        $query->where('name', $p->name);
      })->get()->pluck('name')->toArray();
      $permissions[$p->name] = implode(', ', $roles);
    }
    return view('dokumentasi.dokumentasi', compact('permissions'));
  })->name('dokumentasi');

  // Notify
  Route::post('/notify', function (Request $request) {
    $notification = view('components.notification', ['type' => $request->type, 'message' => $request->message])->render();
    return response()->json(['notification' => $notification]);
  });
});

require __DIR__ . '/auth.php';
