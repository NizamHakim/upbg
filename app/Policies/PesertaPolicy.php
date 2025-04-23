<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class PesertaPolicy
{
  public function lihatPeserta(User $user)
  {
    return $user->currentRole?->hasPermissionTo('lihat-peserta');
  }

  public function kelolaPeserta(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-peserta');
  }

  public function buatLaporanPeserta(User $user)
  {
    return $user->currentRole?->hasPermissionTo('buat-laporan-peserta');
  }
}
