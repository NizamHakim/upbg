<?php

namespace App\Policies;

use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RuanganPolicy
{
  public function kelolaRuangan(User $user)
  {
    return $user->currentRole?->hasPermissionTo('kelola-ruangan');
  }
}
