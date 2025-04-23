<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
  public function create(): View
  {
    return view('auth.login');
  }

  public function store(LoginRequest $request): RedirectResponse
  {
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
  }

  public function update(Request $request): RedirectResponse
  {
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
      'role' => [
        'required',
        Rule::exists('role_user', 'role_id')->where(function (Builder $query) use ($user) {
          $query->where('user_id', $user->id);
        })
      ]
    ]);

    if ($validator->fails()) {
      abort(403);
    }

    $user->update([
      'current_role_id' => $request->role
    ]);

    return redirect()->route('dashboard');
  }

  public function destroy(Request $request): RedirectResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}
