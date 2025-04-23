<?php

namespace App\Policies;

use App\Models\ConfigLaporan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConfigLaporanPolicy
{
  public function buatLaporan(User $user)
  {
    return $user->currentRole?->hasPermissionTo('buat-laporan');
  }

  public function kelolaLaporan(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-laporan');
  }
}
