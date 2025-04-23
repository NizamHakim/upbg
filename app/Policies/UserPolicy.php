<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
  public function lihatUser(User $user)
  {
    return $user->currentRole?->hasPermissionTo('lihat-user');
  }

  public function kelolaUserPengajaran(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-user-pengajaran');
  }

  public function kelolaUserTes(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-user-tes');
  }

  public function kelolaUserKeuangan(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-user-keuangan');
  }

  public function kelolaUserSuperuser(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-user-superuser');
  }

  public function resetPassword(User $user)
  {
    return $user->currentRole?->hasPermissionTo('reset-password');
  }

  public function hapusUser(User $user)
  {
    return $user->currentRole?->hasPermissionTo('hapus-user');
  }
}
