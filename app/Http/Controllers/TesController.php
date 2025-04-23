<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tes;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Ruangan;
use App\Models\TipeTes;
use App\Models\PesertaTes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\RuanganTesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Validator;
use App\Helpers\SchedulingConflictChecker;
use App\Exceptions\SchedulingConflictException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TesController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    Gate::authorize('viewList', Tes::class);

    $tipeOptions = TipeTes::withTrashed()->get();
    $ruanganOptions = Ruangan::withTrashed()->get();
    $pengawasOptions = User::role('Staf Pengawas Tes')->get();

    // query in Tes.php model
    $statusOptions = Tes::statusOptions();
    $sortbyOptions = Tes::sortByOptions();

    $tesList = Tes::with(['ruangan']);

    $tesList->when($request->query('tipe'), function ($query) use ($request) {
      return $query->where('tipe_id', $request->query('tipe'));
    });

    $tesList->when($request->query('tanggal'), function ($query) use ($request) {
      $firstDate = Carbon::parse($request->query('tanggal'))->startOfMonth();
      $lastDate = Carbon::parse($request->query('tanggal'))->endOfMonth();
      return $query->whereBetween('tanggal', [$firstDate, $lastDate]);
    });

    $tesList->when($request->query('ruangan'), function ($query) use ($request) {
      return $query->whereHas('ruangan', function ($query) use ($request) {
        $query->where('ruangan_id', $request->query('ruangan'));
      });
    });

    $tesList->when($request->query('status'), function ($query) use ($request) {
      return $query->status($request->query('status'));
    });

    $tesList->when($request->query('order'), function ($query) use ($request) {
      return $query->sort($request->query('order'));
    }, function ($query) {
      return $query->latest();
    });

    if ($request->user()->can('viewAny', Tes::class)) {
      $tesList->when($request->query('pengawas'), function ($query) use ($request) {
        return $query->whereHas('pengawas', function ($query) use ($request) {
          $query->where('user_id', $request->query('pengawas'));
        });
      });
    } else {
      $tesList->whereHas('pengawas', function ($query) {
        $query->where('user_id', Auth::id());
      });
    }

    $tesList = $tesList->paginate(self::$pageCount)->withQueryString();

    $request->flash();

    return view('tes.daftar-tes', compact('tipeOptions', 'ruanganOptions', 'pengawasOptions', 'statusOptions', 'sortbyOptions', 'tesList'));
  }

  public function create(Request $request)
  {
    Gate::authorize('create', Tes::class);

    $tipeOptions = TipeTes::all();
    $ruanganOptions = Ruangan::all();
    $pengawasOptions = User::role('Staf Pengawas Tes')->get();

    return view('tes.tambah-tes', compact('tipeOptions', 'ruanganOptions', 'pengawasOptions'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('create', Tes::class);

      $validator = Validator::make($request->all(), [
        'kode-tes' => 'required|unique:tes,kode',
        'tipe' => 'required|exists:tipe_tes,id',
        'nama' => 'required',
        'tanggal' => 'required|date',
        'waktu-mulai' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|date_format:H:i',
        'ruangan' => 'required|array',
        'ruangan.*' => 'required|exists:ruangan,id',
        'pengawas' => 'required|array',
        'pengawas.*' => 'required|exists:users,id',
      ], [
        'kode-tes.required' => 'Kode tes tidak boleh kosong',
        'kode-tes.unique' => 'Kode tes sudah digunakan',
        'tipe.required' => 'Tipe tes tidak boleh kosong',
        'tipe.exists' => 'Tipe tes tidak valid',
        'nama.required' => 'Nama tes tidak boleh kosong',
        'tanggal.required' => 'Tanggal tes tidak boleh kosong',
        'tanggal.date' => 'Tanggal tes tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tes tidak boleh kosong',
        'waktu-mulai.date_format' => 'Waktu mulai tes tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tes tidak boleh kosong',
        'waktu-selesai.date_format' => 'Waktu selesai tes tidak valid',
        'ruangan.required' => 'Ruangan tes tidak boleh kosong',
        'ruangan.*.required' => 'Ruangan tes tidak boleh kosong',
        'ruangan.*.exists' => 'Ruangan tes tidak valid',
        'pengawas.required' => 'Pengawas tes tidak boleh kosong',
        'pengawas.*.required' => 'Pengawas tes tidak boleh kosong',
        'pengawas.*.exists' => 'Pengawas tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      foreach ($request['ruangan'] as $ruanganId) {
        if (SchedulingConflictChecker::checkForConflict($ruanganId, $request['tanggal'], $request['waktu-mulai'], $request['waktu-selesai'])) {
          $ruangan = Ruangan::findOrFail($ruanganId);
          $tanggal = Carbon::parse($request['tanggal']);
          throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$ruangan->kode}");
        }
      }

      $tes = Tes::create([
        'kode' => $request['kode-tes'],
        'tipe_id' => $request['tipe'],
        'nama' => $request['nama'],
        'tanggal' => $request['tanggal'],
        'waktu_mulai' => $request['waktu-mulai'],
        'waktu_selesai' => $request['waktu-selesai'],
      ]);

      $tes->ruangan()->sync($request['ruangan']);
      $tes->pengawas()->sync($request['pengawas']);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Tes {$tes->kode} berhasil ditambahkan",
      ]);
      return response()->json([
        'redirect' => route('tes.detail', $tes->id),
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambahkan tes'])->render();
      return response()->json([
        'notification' => $notification,
      ], 403);
    } catch (SchedulingConflictException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => $e->getMessage()])->render();
      return response()->json([
        'notification' => $notification,
      ], 409);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function detail($tesId, Request $request)
  {
    if ($request->ajax()) {
      try {
        $tes = Tes::findOrFail($tesId);
        Gate::authorize('view', $tes);

        $tes->load([
          'ruangan',
          'pivotPeserta' => function ($query) use ($request) {
            $query->when($request->query('ruangan'), function ($query) use ($request) {
              if ($request->query('ruangan') == '-1') {
                $query->whereNull('ruangan_id');
              } else {
                $query->where('ruangan_id', $request->query('ruangan'));
              }
            })->with('peserta');

            $query->when($request->query('search'), function ($query) use ($request) {
              $query->whereHas('peserta', function ($query) use ($request) {
                $query->where('nama', 'like', "%{$request->query('search')}%");
              });
            });
          }
        ]);

        return view('tes.partials.presensi-table', compact('tes'));
      } catch (ModelNotFoundException $e) {
        $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
        return response()->json([
          'notification' => $notification,
        ], 404);
      } catch (AuthorizationException $e) {
        $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengakses tes ini'])->render();
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

    $tes = Tes::with([
      'ruangan',
      'pengawas',
      'pivotPeserta' => ['peserta']
    ])->withCount([
      'pivotPeserta as totalCount',
      'pivotPeserta as hadirCount' => function ($query) {
        $query->where('hadir', true);
      }
    ])->findOrFail($tesId);

    Gate::authorize('view', $tes);

    return view('tes.detail.detail-tes', compact('tes'));
  }

  public function updatePresensi($tesId, $presensiId, Request $request)
  {
    try {
      $tes = Tes::findOrFail($tesId);

      Gate::authorize('updatePresensi', $tes);

      $presensi = $tes->pivotPeserta()->findOrFail($presensiId);

      $validator = Validator::make($request->all(), [
        'hadir' => 'required|boolean',
      ]);

      if ($validator->fails()) {
        abort(500);
      }

      $presensi->update([
        'hadir' => $request->hadir,
      ]);

      $tes->loadCount([
        'pivotPeserta as totalCount',
        'pivotPeserta as hadirCount' => function ($query) {
          $query->where('hadir', true);
        }
      ]);

      $btnPresensi = view('components.btn-presensi', ['presensi' => $presensi])->render();
      $hadirCount = view('components.hadir-count', ['hadir' => $tes->hadirCount, 'total' => $tes->totalCount])->render();

      return response()->json([
        'btnPresensi' => $btnPresensi,
        'hadirCount' => $hadirCount,
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes atau peserta tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah status presensi'])->render();
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

  public function edit($tesId)
  {
    Gate::authorize('editDetail', Tes::class);

    $tes = Tes::with(['ruangan', 'pengawas'])->findOrFail($tesId);

    $tipeOptions = TipeTes::all();
    $ruanganOptions = Ruangan::all();
    $pengawasOptions = User::role('Staf Pengawas Tes')->get();

    return view('tes.detail.edit-tes', compact('tes', 'tipeOptions', 'ruanganOptions', 'pengawasOptions'));
  }

  public function updateDetail($tesId, Request $request)
  {
    try {
      Gate::authorize('editDetail', Tes::class);

      $validator = Validator::make($request->all(), [
        'kode-tes' => 'required|unique:tes,kode, ' . $tesId,
        'tipe' => 'required|exists:tipe_tes,id',
        'nama' => 'required',
        'tanggal' => 'required|date',
        'waktu-mulai' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|date_format:H:i',
        'ruangan' => 'required|array',
        'ruangan.*' => 'required|exists:ruangan,id',
        'pengawas' => 'required|array',
        'pengawas.*' => 'required|exists:users,id',
      ], [
        'kode-tes.required' => 'Kode tes tidak boleh kosong',
        'kode-tes.unique' => 'Kode tes sudah digunakan',
        'tipe.required' => 'Tipe tes tidak boleh kosong',
        'tipe.exists' => 'Tipe tes tidak valid',
        'nama.required' => 'Nama tes tidak boleh kosong',
        'tanggal.required' => 'Tanggal tes tidak boleh kosong',
        'tanggal.date' => 'Tanggal tes tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tes tidak boleh kosong',
        'waktu-mulai.date_format' => 'Waktu mulai tes tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tes tidak boleh kosong',
        'waktu-selesai.date_format' => 'Waktu selesai tes tidak valid',
        'ruangan.required' => 'Ruangan tes tidak boleh kosong',
        'ruangan.*.required' => 'Ruangan tes tidak boleh kosong',
        'ruangan.*.exists' => 'Ruangan tes tidak valid',
        'pengawas.required' => 'Pengawas tes tidak boleh kosong',
        'pengawas.*.required' => 'Pengawas tes tidak boleh kosong',
        'pengawas.*.exists' => 'Pengawas tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      foreach ($request['ruangan'] as $ruanganId) {
        if (SchedulingConflictChecker::checkForConflict($ruanganId, $request['tanggal'], $request['waktu-mulai'], $request['waktu-selesai'], null, $tesId)) {
          $ruangan = Ruangan::findOrFail($ruanganId);
          $tanggal = Carbon::parse($request['tanggal']);
          throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$ruangan->kode}");
        }
      }

      $tes = Tes::findOrFail($tesId);

      $tes->update([
        'kode' => $request['kode-tes'],
        'tipe_id' => $request['tipe'],
        'nama' => $request['nama'],
        'tanggal' => $request['tanggal'],
        'waktu_mulai' => $request['waktu-mulai'],
        'waktu_selesai' => $request['waktu-selesai'],
      ]);

      $tes->ruangan()->sync($request['ruangan']);
      $tes->pengawas()->sync($request['pengawas']);
      $tes->pivotPeserta()->whereNotIn('ruangan_id', $request['ruangan'])->update(['ruangan_id' => null]);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Tes {$tes->kode} berhasil diupdate",
      ]);
      return response()->json([
        'redirect' => route('tes.detail', $tes->id),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit tes'])->render();
      return response()->json([
        'notification' => $notification,
      ], 403);
    } catch (SchedulingConflictException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => $e->getMessage()])->render();
      return response()->json([
        'notification' => $notification,
      ], 409);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function destroy($tesId, Request $request)
  {
    try {
      Gate::authorize('delete', Tes::class);

      $tes = Tes::findOrFail($tesId);

      $tes->delete();

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Tes {$tes->kode} berhasil dihapus",
      ]);
      return response()->json([
        'redirect' => route('tes.index'),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus tes'])->render();
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

  public function mulaiTes($tesId, Request $request)
  {
    try {
      $tes = Tes::findOrFail($tesId);

      Gate::authorize('mulaiTes', $tes);

      $tes->update([
        'terlaksana' => true,
      ]);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Tes {$tes->kode} berhasil dimulai",
      ]);
      return response()->json([
        'redirect' => route('tes.detail', $tes->id),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk memulai tes'])->render();
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


  public function daftarPeserta($tesId, Request $request)
  {
    if ($request->ajax()) {
      try {
        $tes = Tes::findOrFail($tesId);
        Gate::authorize('view', $tes);

        $tes->load([
          'pivotPeserta' => function ($query) use ($request) {
            $query->when($request->query('ruangan'), function ($query) use ($request) {
              if ($request->query('ruangan') == '-1') {
                $query->whereNull('ruangan_id');
              } else {
                $query->where('ruangan_id', $request->query('ruangan'));
              }
            })->with(['peserta', 'ruangan']);

            $query->when($request->query('search'), function ($query) use ($request) {
              $query->whereHas('peserta', function ($query) use ($request) {
                $query->where('nama', 'like', "%{$request->query('search')}%");
              });
            });

            $query->orderBy('ruangan_id');
          }
        ]);

        return view('tes.partials.peserta-table', compact('tes'));
      } catch (ModelNotFoundException $e) {
        $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
        return response()->json([
          'notification' => $notification,
        ], 404);
      } catch (AuthorizationException $e) {
        $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengakses tes ini'])->render();
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

    $tes = Tes::with([
      'pivotPeserta' => function ($query) {
        $query->orderBy('ruangan_id');
      },
      'pivotPeserta.peserta',
      'pivotPeserta.ruangan',
    ])->withCount([
      'pivotPeserta as totalCount',
    ])->findOrFail($tesId);

    Gate::authorize('view', $tes);

    $ruanganStatistic = $this->getRuanganStatistic($tes);

    return view('tes.detail.daftar-peserta', compact('tes') + $ruanganStatistic);
  }

  public function updateRuangan($tesId, $pesertaId, Request $request)
  {
    try {
      Gate::authorize('kelolaPeserta', Tes::class);

      Validator::extend('ruanganValid', function ($attribute, $value, $parameters, $validator) {
        if ($value == '-1') return true;
        $ruangan = Ruangan::find($value);
        return $ruangan ? true : false;
      });

      $validator = Validator::make($request->all(), [
        'ruangan' => 'required|ruanganValid',
      ]);

      if ($validator->fails()) {
        abort(500);
      }

      $pesertaTes = PesertaTes::findOrFail($pesertaId);
      $pesertaTes->update([
        'ruangan_id' => $request['ruangan'] == '-1' ? null : $request['ruangan'],
      ]);

      $ruanganStatistic = $this->getRuanganStatistic($pesertaTes->tes);
      $pembagianRuangan = view('tes.partials.pembagian-ruangan', $ruanganStatistic)->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => 'Ruangan peserta berhasil di update'])->render();

      return response()->json([
        'pembagianRuangan' => $pembagianRuangan,
        'notification' => $notification,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah ruangan tes'])->render();
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

  public function updateRuanganBatch($tesId, Request $request)
  {
    try {
      Gate::authorize('kelolaPeserta', Tes::class);

      Validator::extend('ruanganValid', function ($attribute, $value, $parameters, $validator) {
        if ($value == '-1') return true;
        $ruangan = Ruangan::find($value);
        return $ruangan ? true : false;
      });

      $validator = Validator::make($request->all(), [
        'ruangan-src' => 'required|ruanganValid',
        'ruangan-dest' => 'required|ruanganValid',
      ], [
        'ruangan-src.required' => 'Ruangan asal tidak boleh kosong',
        'ruangan-src.exists' => 'Ruangan asal tidak valid',
        'ruangan-dest.required' => 'Ruangan tujuan tidak boleh kosong',
        'ruangan-dest.exists' => 'Ruangan tujuan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tes = Tes::findOrFail($tesId);
      $ruanganSrc = $request['ruangan-src'] == '-1' ? null : $request['ruangan-src'];
      $ruanganDest = $request['ruangan-dest'] == '-1' ? null : $request['ruangan-dest'];

      $tes->pivotPeserta()->where('ruangan_id', $ruanganSrc)->update(['ruangan_id' => $ruanganDest]);
      $tes->load(['pivotPeserta' => ['peserta', 'ruangan']]);

      $ruanganStatistic = $this->getRuanganStatistic($tes);
      $pembagianRuangan = view('tes.partials.pembagian-ruangan', $ruanganStatistic)->render();
      $pesertaTable = view('tes.partials.peserta-table', compact('tes'))->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => 'Ruangan peserta berhasil di update'])->render();

      return response()->json([
        'pembagianRuangan' => $pembagianRuangan,
        'pesertaTable' => $pesertaTable,
        'notification' => $notification,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah ruangan tes'])->render();
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

  public function createPeserta($tesId, Request $request)
  {
    Gate::authorize('kelolaPeserta', Tes::class);

    $tes = Tes::findOrFail($tesId);

    return view('tes.detail.tambah-peserta', compact('tes'));
  }

  public function storePeserta($tesId, Request $request)
  {
    try {
      Gate::authorize('kelolaPeserta', Tes::class);

      Validator::extend('unique_peserta', function ($attribute, $value, $parameters, $validator) {
        $tes = Tes::find($parameters[0]);
        $peserta = Peserta::where('nik', $value)->first();
        if (!$peserta) return true;
        return $tes->pivotPeserta()->where('peserta_id', $peserta->id)->doesntExist();
      });

      $validator = Validator::make($request->all(), [
        'nik' => 'nullable|array',
        'nik.*' => 'required|numeric|unique_peserta:' . $tesId,
        'nama' => 'nullable|array',
        'nama.*' => 'required',
        'occupation' => 'nullable|array',
        'occupation.*' => 'required',
      ], [
        'nik.*.required' => 'NIK / NRP tidak boleh kosong',
        'nik.*.numeric' => 'NIK / NRP harus berupa angka',
        'nik.*.unique_peserta' => 'Peserta sudah terdaftar di tes ini',
        'nama.*.required' => 'Nama tidak boleh kosong',
        'occupation.*.required' => 'Departemen / Occupation tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tes = Tes::findOrFail($tesId);

      for ($i = 0; $i < count($request['nik']); $i++) {
        $peserta = Peserta::firstOrCreate(
          ['nik' => $request['nik'][$i]],
          [
            'nama' => $request['nama'][$i],
            'occupation' => $request['occupation'][$i],
          ]
        );

        $ruanganTes = Ruangan::whereHas('tes', function ($query) use ($tes) {
          $query->where('tes_id', $tes->id);
        })->where('kapasitas', '>', function ($query) use ($tes) {
          $query->selectRaw('count(*)')->from('peserta_tes')->whereColumn('ruangan_id', 'ruangan.id')->where('tes_id', $tes->id);
        })->inRandomOrder()->first();

        $tes->pivotPeserta()->create([
          'peserta_id' => $peserta->id,
          'ruangan_id' => $ruanganTes ? $ruanganTes->id : null,
        ]);
      }

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Peserta berhasil ditambahkan',
      ]);

      return response()->json([
        'redirect' => route('tes.daftar-peserta', $tes->id),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah peserta tes'])->render();
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

  public function destroyPeserta($tesId, $pesertaId, Request $request)
  {
    try {
      Gate::authorize('kelolaPeserta', Tes::class);

      $tes = Tes::findOrFail($tesId);
      $pesertaTes = $tes->pivotPeserta()->findOrFail($pesertaId);

      $pesertaTes->delete();

      $tes->load([
        'ruangan',
        'pengawas',
        'pivotPeserta' => ['peserta']
      ])->loadCount([
        'pivotPeserta as totalCount',
        'pivotPeserta as hadirCount' => function ($query) {
          $query->where('hadir', true);
        }
      ]);

      $ruanganStatistic = $this->getRuanganStatistic($tes);
      $pembagianRuangan = view('tes.partials.pembagian-ruangan', $ruanganStatistic)->render();
      $pesertaTable = view('tes.partials.peserta-table', compact('tes'))->render();
      $totalPeserta = view('tes.partials.total-peserta', ['tes' => $tes])->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => "Peserta {$pesertaTes->peserta->nama} berhasil dihapus"])->render();

      return response()->json([
        'pembagianRuangan' => $pembagianRuangan,
        'pesertaTable' => $pesertaTable,
        'totalPeserta' => $totalPeserta,
        'notification' => $notification,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tes tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah peserta tes'])->render();
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

  public function downloadPembagianRuangan($tesId)
  {
    $tes = Tes::with([
      'pivotPeserta' => function ($query) {
        $query->orderBy('ruangan_id');
      },
      'pivotPeserta.peserta',
      'pivotPeserta.ruangan',
    ])->withCount([
      'pivotPeserta as totalCount',
    ])->findOrFail($tesId);

    return Excel::download(new RuanganTesExport($tes), "Pembagian Ruangan {$tes->nama}.xlsx");
  }

  private function getRuanganStatistic($tes)
  {
    $ruanganTes = Ruangan::whereHas('tes', function ($query) use ($tes) {
      $query->where('tes_id', $tes->id);
    })->withCount([
      'pesertaTes as pesertaCount' => function ($query) use ($tes) {
        $query->where('tes_id', $tes->id);
      }
    ])->orderBy('id')->get();

    $notAssigned = $tes->pivotPeserta->where('ruangan_id', null)->count();

    return compact('ruanganTes', 'notAssigned');
  }
}
