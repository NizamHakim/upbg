<?php

namespace App\Policies;

use App\Models\Tes;
use App\Models\User;

class TesPolicy
{
  public function viewAny(User $user)
  {
    return $user->currentRole?->hasPermissionTo('akses-semua-tes');
  }

  public function viewList(User $user)
  {
    return $user->currentRole?->hasPermissionTo('lihat-daftar-tes');
  }

  public function view(User $user, Tes $tes)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-tes')) {
      return true;
    }
    return $user->currentRole?->hasPermissionTo('lihat-detail-tes') && $tes->pengawas->contains($user);
  }

  public function create(User $user)
  {
    return $user->currentRole?->hasPermissionTo('tambah-tes');
  }

  public function delete(User $user)
  {
    return $user->currentRole?->hasPermissionTo('hapus-tes');
  }

  public function mulaiTes(User $user, Tes $tes)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-tes')) {
      return true;
    }
    return $user->currentRole?->hasPermissionTo('mulai-tes') && $tes->pengawas->contains($user);
  }

  public function editDetail(User $user)
  {
    return $user->currentRole?->hasPermissionTo('edit-tes');
  }

  public function updatePresensi(User $user, Tes $tes)
  {
    if ($user->currentRole?->hasPermissionTo('akses-semua-tes')) {
      return true;
    }
    return $user->currentRole?->hasPermissionTo('update-presensi-tes') && $tes->pengawas->contains($user);
  }

  public function kelolaPeserta(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-peserta-tes');
  }

  public function kelolaTes(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-tes');
  }

  public function buatLaporanTes(User $user)
  {
    return $user->currentRole?->hasPermissionTo('buat-laporan-tes');
  }
}
