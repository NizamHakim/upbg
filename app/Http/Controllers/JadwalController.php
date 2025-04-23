<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Models\PertemuanKelas;
use App\Models\Tes;
use Illuminate\Support\Carbon;

use function Illuminate\Log\log;

class JadwalController extends Controller
{
  public function index(Request $request)
  {
    $date = today();
    if ($request->query('date')) {
      $date = Carbon::createFromFormat('Y-m-d', $request->query('date'));
    }

    $hariList = [];
    for ($i = 0; $i < 7; $i++) {
      $hariList[] = $date->copy()->startOfWeek()->addDays($i);
    }


    $sesiList = [
      ['start' => '07:30', 'end' => '09:00'],
      ['start' => '09:00', 'end' => '10:30'],
      ['start' => '10:30', 'end' => '12:00'],
      ['start' => '13:00', 'end' => '14:30'],
      ['start' => '14:30', 'end' => '16:00'],
      ['start' => '16:00', 'end' => '17:30'],
      ['start' => '18:30', 'end' => '20:00'],
    ];

    $ruanganList = Ruangan::all();

    $jadwalList = [];
    foreach ($hariList as $hari) {
      foreach ($sesiList as $sesi) {
        foreach ($ruanganList as $ruangan) {
          $pertemuanKelas = PertemuanKelas::with(['kelas', 'ruangan', 'kelas.pengajar'])
            ->where('tanggal', $hari)
            ->where(function ($query) use ($sesi) {
              $query->where('waktu_mulai', '<', $sesi['end'])->where('waktu_selesai', '>', $sesi['start']);
            })
            ->whereHas('ruangan', function ($query) use ($ruangan) {
              $query->where('ruangan_id', $ruangan->id);
            })
            ->get();
          $jadwalList[$hari->isoFormat('dddd, D MMMM YYYY')][$sesi['start'] . ' - ' . $sesi['end']][$ruangan->kode]['kelas'] = $pertemuanKelas;

          $tes = Tes::with(['ruangan', 'pengawas'])
            ->where('tanggal', $hari)
            ->where(function ($query) use ($sesi) {
              $query->where('waktu_mulai', '<', $sesi['end'])->where('waktu_selesai', '>', $sesi['start']);
            })
            ->whereHas('ruangan', function ($query) use ($ruangan) {
              $query->where('ruangan_id', $ruangan->id);
            })
            ->get();
          $jadwalList[$hari->isoFormat('dddd, D MMMM YYYY')][$sesi['start'] . ' - ' . $sesi['end']][$ruangan->kode]['tes'] = $tes;
        }
      }
    }
    $start = $date->copy()->startOfWeek()->isoFormat('dddd, D MMMM YYYY');
    $end = $date->copy()->endOfWeek()->isoFormat('dddd, D MMMM YYYY');

    $pertemuanToday = PertemuanKelas::with(['kelas', 'ruangan', 'kelas.pengajar'])
      ->where('tanggal', $date->format('Y-m-d'))
      ->orderBy('waktu_mulai')
      ->get();
    $tesToday = Tes::with(['ruangan', 'pengawas'])
      ->where('tanggal', $date->format('Y-m-d'))
      ->orderBy('waktu_mulai')
      ->get();
    $jadwalToday = [
      'kelas' => $pertemuanToday,
      'tes' => $tesToday,
    ];
    $today = $date->isoFormat('dddd, D MMMM YYYY');

    if ($request->ajax()) {
      return response()->json([
        'table' => view('jadwal.partials.jadwal-table', compact('jadwalList', 'ruanganList'))->render(),
        'desktop' => view('jadwal.partials.tanggal-desktop', compact('start', 'end'))->render(),
        'mobile' => view('jadwal.partials.jadwal-mobile', compact('jadwalToday', 'today'))->render(),
      ], 200);
    }

    return view('jadwal.jadwal', compact('ruanganList', 'jadwalList', 'ruanganList', 'start', 'end', 'jadwalToday', 'today'));
  }

  public function jadwalKelas($pertemuanId)
  {
    $kelas = Kelas::whereHas('pertemuan', function ($query) use ($pertemuanId) {
      $query->where('id', $pertemuanId);
    })->first();

    return view('jadwal.detail-jadwal-kelas', compact('kelas'))->render();
  }

  public function jadwalTes($tesId)
  {
    $tes = Tes::with(['ruangan', 'pengawas'])->find($tesId);

    return view('jadwal.detail-jadwal-tes', compact('tes'))->render();
  }
}
