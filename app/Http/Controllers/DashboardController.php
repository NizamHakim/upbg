<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\PertemuanKelas;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    $pertemuan = PertemuanKelas::whereHas('kelas', function ($query) use ($user) {
      $query->whereHas('pengajar', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      });
    })->where('tanggal', '>=', Carbon::now()->format('Y-m-d'))
      ->where('terlaksana', false)
      ->orderBy('tanggal', 'asc')
      ->orderBy('waktu_mulai', 'asc')->first();

    $tes = Tes::whereHas('pengawas', function ($query) use ($user) {
      $query->where('user_id', $user->id);
    })
      ->where('tanggal', '>=', Carbon::now()->format('Y-m-d'))
      ->where('terlaksana', false)
      ->orderBy('tanggal', 'asc')
      ->orderBy('waktu_mulai', 'asc')->first();

    return view('dashboard', [
      'user' => $user,
      'pertemuan' => $pertemuan,
      'tes' => $tes,
    ]);
  }
}
