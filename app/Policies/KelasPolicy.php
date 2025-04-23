<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
  public function viewAny(User $user)
  {
    return $user->currentRole?->hasPermissionTo('akses-semua-kelas');
  }

  public function viewList(User $user)
  {
    return $user->currentRole?->hasPermissionTo('lihat-daftar-kelas');
  }

  public function view(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }
    return $user->currentRole?->hasPermissionTo('lihat-detail-kelas') && $kelas->pengajar->contains($user);
  }

  public function create(User $user)
  {
    return $user->currentRole?->hasPermissionTo('tambah-kelas');
  }

  public function edit(User $user)
  {
    return $user->currentRole?->hasPermissionTo('edit-kelas');
  }

  public function delete(User $user)
  {
    return $user->currentRole?->hasPermissionTo('hapus-kelas');
  }

  public function kelolaPeserta(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-peserta-kelas');
  }

  public function kelolaKelas(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-kelas');
  }

  public function tambahPertemuan(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('tambah-pertemuan') && $kelas->pengajar->contains($user);
  }

  public function mulaiPertemuan(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('mulai-pertemuan') && $kelas->pengajar->contains($user);
  }

  public function editPertemuan(User $user)
  {
    return $user->currentRole?->hasPermissionTo('edit-pertemuan');
  }

  public function reschedulePertemuan(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('reschedule-pertemuan') && $kelas->pengajar->contains($user);
  }

  public function deletePertemuan(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('hapus-pertemuan') && $kelas->pengajar->contains($user);
  }

  public function editTopikCatatan(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('edit-topik-catatan') && $kelas->pengajar->contains($user);
  }

  public function tambahPresensiKelas(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('tambah-presensi-kelas') && $kelas->pengajar->contains($user);
  }

  public function ubahPresensiKelas(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('ubah-presensi-kelas') && $kelas->pengajar->contains($user);
  }

  public function hapusPresensiKelas(User $user, Kelas $kelas)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      return true;
    }

    return $user->currentRole?->hasPermissionTo('hapus-presensi-kelas') && $kelas->pengajar->contains($user);
  }

  public function buatLaporanKelas(User $user)
  {
    return $user->currentRole?->hasPermissionTo('buat-laporan-kelas');
  }
}
