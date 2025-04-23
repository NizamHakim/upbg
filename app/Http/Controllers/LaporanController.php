<?php

namespace App\Http\Controllers;

use App\Exports\DepartemenExport;
use Exception;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Exports\KelasExport;
use App\Exports\TesExport;
use Illuminate\Http\Request;
use App\Models\ConfigLaporan;
use App\Models\PertemuanKelas;
use App\Models\Tes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class LaporanController extends Controller
{
  public function laporanKelas(Request $request)
  {
    if ($request->ajax()) {
      $kelasList = Kelas::query();

      $firstDate = Carbon::parse($request->query('tanggal-mulai'));
      $lastDate = Carbon::parse($request->query('tanggal-akhir'));
      $includeTidakTerlaksana = $request->query('include-tidak-terlaksana');

      $kelasList->when($firstDate && $lastDate, function ($query) use ($firstDate, $lastDate) {
        return $query->whereHas('pertemuan', function ($query) use ($firstDate, $lastDate) {
          $query->whereBetween('tanggal', [$firstDate, $lastDate]);
        });
      });

      $kelasList->when(!$includeTidakTerlaksana, function ($query) {
        return $query->whereHas('pertemuan', function ($query) {
          $query->where('terlaksana', true);
        });
      });

      $kelasList = $kelasList->get();
      $table = view('laporan.partials.kelas-result', compact('kelasList', 'firstDate', 'lastDate', 'includeTidakTerlaksana'))->render();

      return response()->json([
        'table' => $table,
      ], 200);
    }

    Gate::authorize('buatLaporanKelas', Kelas::class);
    return view('laporan.bulanan-kelas');
  }

  public function exportKelas(Request $request)
  {
    $firstDate = Carbon::parse($request['tanggal-mulai']);
    $lastDate = Carbon::parse($request['tanggal-akhir']);
    $title = 'Laporan Kelas ' . $firstDate->format('d-m-Y') . '_' . $lastDate->format('d-m-Y');
    return Excel::download(new KelasExport($request), $title . '.xlsx');
  }

  public function laporanDepartemen(Request $request)
  {
    if ($request->ajax()) {
      $pesertaList = Peserta::query();

      $pesertaList->when($request->query('departemen'), function ($query) use ($request) {
        return $query->where('occupation', 'like', '%' . $request->query('departemen') . '%');
      });

      $pesertaList->when($request->query('mahasiswa'), function ($query) use ($request) {
        return $query->where('id', $request->query('mahasiswa'));
      });

      $pesertaList->with([
        'kelas' => function ($query) {
          $query->withCount(['pertemuan as progress' => function ($query) {
            $query->where('terlaksana', true);
          }]);
        },
        'pivotTes.tes',
      ]);
      $pesertaList = $pesertaList->get();

      foreach ($pesertaList as $peserta) {
        foreach ($peserta->kelas as $kelas) {
          $kehadiran = PertemuanKelas::where('kelas_id', $kelas->id)->where('terlaksana', true)->whereHas('presensi', function ($query) use ($peserta) {
            $query->where('peserta_id', $peserta->id)->where('hadir', true);
          })->count();
          $kelas->kehadiran = $kehadiran;
          $kelas->percentage = $kelas->progress > 0 ? ($kehadiran / $kelas->progress) * 100 : 0;
        }
      }

      $table = view('laporan.partials.departemen-result', [
        'pesertaList' => $pesertaList,
        'departemen' => $request->query('departemen'),
        'mahasiswa' => $request->query('mahasiswa'),
      ])->render();

      return response()->json([
        'table' => $table,
        'pesertaList' => $pesertaList,
      ], 200);
    }

    return view('laporan.departemen');
  }

  public function exportDepartemen(Request $request)
  {
    $departemen = $request->departemen;
    $mahasiswa = $request->mahasiswa;
    if ($departemen) {
      $title = 'Laporan Departemen ' . $departemen;
    } else {
      $mahasiswa = Peserta::findOrFail($mahasiswa)->nama;
      $title = 'Laporan Mahasiswa ' . $mahasiswa;
    }
    return Excel::download(new DepartemenExport($request), $title . '.xlsx');
  }

  public function laporanTes(Request $request)
  {
    if ($request->ajax()) {
      $tesList = Tes::query();

      $firstDate = Carbon::parse($request->query('tanggal-mulai'));
      $lastDate = Carbon::parse($request->query('tanggal-akhir'));
      $includeTidakTerlaksana = $request->query('include-tidak-terlaksana');

      $tesList->when($firstDate && $lastDate, function ($query) use ($firstDate, $lastDate) {
        return $query->whereBetween('tanggal', [$firstDate, $lastDate]);
      });

      $tesList->when(!$includeTidakTerlaksana, function ($query) {
        $query->where('terlaksana', true);
      });

      $tesList = $tesList->get();
      $table = view('laporan.partials.tes-result', compact('tesList', 'firstDate', 'lastDate', 'includeTidakTerlaksana'))->render();

      return response()->json([
        'table' => $table,
      ], 200);
    }

    Gate::authorize('buatLaporanTes', Tes::class);
    return view('laporan.bulanan-tes');
  }

  public function exportTes(Request $request)
  {
    $firstDate = Carbon::parse($request['tanggal-mulai']);
    $lastDate = Carbon::parse($request['tanggal-akhir']);
    $title = 'Laporan Tes ' . $firstDate->format('d-m-Y') . '_' . $lastDate->format('d-m-Y');
    return Excel::download(new TesExport($request), $title . '.xlsx');
  }

  public function kelolaLaporan()
  {
    Gate::authorize('kelola-laporan', ConfigLaporan::class);
    $configList = ConfigLaporan::all();
    return view('laporan.kelola-laporan', compact('configList'));
  }

  public function updateConfig($configid, $itemKey, Request $request)
  {
    try {
      Gate::authorize('kelola-laporan', ConfigLaporan::class);
      $config = ConfigLaporan::findOrFail($configid);
      $data = $config->data;
      $data[$itemKey] = $request['item-value'];
      $config->data = $data;
      $config->save();

      $notification = view('components.notification', ['type' => 'success', 'message' => 'Data berhasil diubah'])->render();
      $laporanItem = view('laporan.partials.kelola-laporan-item', ['key' => $itemKey, 'value' => $request['item-value']])->render();
      return response()->json([
        'notification' => $notification,
        'laporanItem' => $laporanItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Data tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah laporan'])->render();
      return response()->json([
        'notification' => $notification,
      ], 403);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function findDepartemen(Request $request)
  {
    $departemen = $request->query('departemen');
    $departemenList = Peserta::select('occupation')->where('occupation', 'like', '%' . $departemen . '%')->distinct()->limit(20)->get();
    return response()->json([
      'departemenList' => $departemenList,
    ], 200);
  }

  public function findMahasiswa(Request $request)
  {
    $mahasiswa = $request->query('mahasiswa');
    $mahasiswaList = Peserta::where('nama', 'like', '%' . $mahasiswa . '%')->limit(20)->get();
    foreach ($mahasiswaList as $mahasiswa) {
      $mahasiswa->nama = $mahasiswa->nama . ' (' . $mahasiswa->nik . ')';
    }
    return response()->json([
      'mahasiswaList' => $mahasiswaList,
    ], 200);
  }
}
