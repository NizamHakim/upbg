<?php

namespace App\Providers;

use App\Models\Permission;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    Paginator::defaultView('components.pagination');
    Debugbar::disable();
  }
}
