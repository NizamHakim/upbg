<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navigation extends Component
{
  public function render(): View
  {
    return view('layouts.navigation');
  }
}
