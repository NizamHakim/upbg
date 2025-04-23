<?php

namespace App\Http\Controllers;

use App\Exceptions\SchedulingConflictException;
use Exception;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\Ruangan;
use App\Models\TipeKelas;
use App\Models\LevelKelas;
use App\Models\ProgramKelas;
use Illuminate\Http\Request;
use App\Helpers\HariProvider;
use App\Models\PertemuanKelas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Helpers\PertemuanKelasGenerator;
use App\Models\JadwalKelas;
use App\Models\Tes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Illuminate\Log\log;

class KelasController extends Controller
{
  public function index(Request $request)
  {
    Gate::authorize('viewList', Kelas::class);

    $programOptions = ProgramKelas::withTrashed()->get();
    $tipeOptions = TipeKelas::withTrashed()->get();
    $levelOptions = LevelKelas::withTrashed()->get();
    $ruanganOptions = Ruangan::withTrashed()->get();
    $pengajarOptions = User::role('Staf Pengajar')->get();

    // query in Kelas.php model
    $statusOptions = collect([
      ['text' => 'Completed', 'value' => 'completed'],
      ['text' => 'In Progress', 'value' => 'in-progress'],
    ]);

    // query in Kelas.php model
    $sortbyOptions = collect([
      ['text' => 'Tanggal Mulai (Terbaru)', 'value' => 'tanggal-mulai-desc'],
      ['text' => 'Tanggal Mulai (Terlama)', 'value' => 'tanggal-mulai-asc'],
      ['text' => 'Kode Kelas (A-Z)', 'value' => 'kode-asc'],
      ['text' => 'Kode Kelas (Z-A)', 'value' => 'kode-desc'],
    ]);

    $progress = PertemuanKelas::select('kelas_id', DB::raw('SUM(terlaksana=1) as progress'))->groupBy('kelas_id');
    $kelasList = Kelas::with(['ruangan', 'jadwal']);
    $kelasList->joinSub($progress, 'progress', function ($join) {
      $join->on('kelas.id', '=', 'progress.kelas_id');
    });

    $kelasList->when($request->query('program'), function ($query) use ($request) {
      return $query->where('program_id', $request->query('program'));
    });

    $kelasList->when($request->query('tipe'), function ($query) use ($request) {
      return $query->where('tipe_id', $request->query('tipe'));
    });

    $kelasList->when($request->query('level'), function ($query) use ($request) {
      return $query->where('level_id', $request->query('level'));
    });

    $kelasList->when($request->query('banyak-pertemuan'), function ($query) use ($request) {
      return $query->where('banyak_pertemuan', $request->query('banyak-pertemuan'));
    });

    $kelasList->when($request->query('tanggal-mulai'), function ($query) use ($request) {
      $firstDate = Carbon::parse($request->query('tanggal-mulai'))->startOfMonth();
      $lastDate = Carbon::parse($request->query('tanggal-mulai'))->endOfMonth();
      return $query->whereBetween('tanggal_mulai', [$firstDate, $lastDate]);
    });

    $kelasList->when($request->query('ruangan'), function ($query) use ($request) {
      return $query->where('ruangan_id', $request->query('ruangan'));
    });

    $kelasList->when($request->query('status'), function ($query) use ($request) {
      return $query->status($request->query('status'));
    });

    $kelasList->when($request->query('order'), function ($query) use ($request) {
      return $query->sort($request->query('order'));
    }, function ($query) {
      return $query->latest();
    });

    $kelasList->when($request->query('kode'), function ($query) use ($request) {
      return $query->where('kode', 'like', '%' . $request->query('kode') . '%');
    });

    if ($request->user()->currentRole?->hasPermissionTo('akses-semua-kelas')) {
      $kelasList->when($request->query('pengajar'), function ($query) use ($request) {
        return $query->whereHas('pengajar', function ($query) use ($request) {
          $query->where('user_id', $request->query('pengajar'));
        });
      });
    } else {
      $kelasList->whereHas('pengajar', function ($query) {
        $query->where('user_id', Auth::id());
      });
    }

    $kelasList = $kelasList->paginate(20)->withQueryString();

    $request->flash();

    return view('kelas.daftar-kelas', compact('kelasList', 'programOptions', 'tipeOptions', 'levelOptions', 'ruanganOptions', 'pengajarOptions', 'statusOptions', 'sortbyOptions'));
  }

  public function create()
  {
    Gate::authorize('create', Kelas::class);

    $programOptions = ProgramKelas::all();
    $tipeOptions = TipeKelas::all();
    $levelOptions = LevelKelas::all();
    $ruanganOptions = Ruangan::all();
    $pengajarOptions = User::role('Staf Pengajar')->get();
    $hariOptions = HariProvider::all();

    return view('kelas.tambah-kelas', compact('programOptions', 'tipeOptions', 'levelOptions', 'ruanganOptions', 'pengajarOptions', 'hariOptions'));
  }

  // public function store(Request $request)
  // {
  //   Validator::extend('jadwal_include_tanggal_mulai', function ($attribute, $value, $parameters, $validator) {
  //     $tanggalMulai = Carbon::parse($parameters[0]);
  //     $hariList = $value;

  //     $result = false;
  //     foreach ($hariList as $hari) {
  //       if ($tanggalMulai->dayOfWeek == $hari) {
  //         $result = true;
  //         break;
  //       }
  //     }
  //     return $result;
  //   });

  //   try {
  //     Gate::authorize('create', Kelas::class);

  //     $validator = Validator::make($request->all(), [
  //       'kode-kelas' => 'required|unique:kelas,kode',
  //       'program' => 'required|exists:program_kelas,id',
  //       'tipe' => 'required|exists:tipe_kelas,id',
  //       'nomor' => 'required|numeric',
  //       'level' => 'nullable|exists:level_kelas,id',
  //       'banyak-pertemuan' => 'required|numeric',
  //       'tanggal-mulai' => 'required|date',
  //       'ruangan' => 'required|exists:ruangan,id',
  //       'group-link' => 'nullable|url:https',
  //       'hari' => 'required|array|jadwal_include_tanggal_mulai:' . $request['tanggal-mulai'],
  //       'hari.*' => 'required|numeric',
  //       'waktu-mulai' => 'required|array',
  //       'waktu-mulai.*' => 'required|date_format:H:i',
  //       'waktu-selesai' => 'required|array',
  //       'waktu-selesai.*' => 'required|date_format:H:i',
  //       'pengajar' => 'required|array',
  //       'pengajar.*' => 'required|exists:users,id',
  //       'nik' => 'nullable|array',
  //       'nik.*' => 'required|numeric',
  //       'nama' => 'nullable|array',
  //       'nama.*' => 'required',
  //       'occupation' => 'nullable|array',
  //       'occupation.*' => 'required',
  //     ], [
  //       'kode-kelas.required' => 'Kode kelas tidak boleh kosong',
  //       'kode-kelas.unique' => 'Kode kelas sudah digunakan',
  //       'program.required' => 'Program tidak boleh kosong',
  //       'program.exists' => 'Program tidak valid',
  //       'tipe.required' => 'Tipe tidak boleh kosong',
  //       'tipe.exists' => 'Tipe tidak valid',
  //       'nomor.required' => 'Nomor kelas tidak boleh kosong',
  //       'nomor.numeric' => 'Nomor kelas harus berupa angka',
  //       'level.exists' => 'Level tidak valid',
  //       'banyak-pertemuan.required' => 'Banyak pertemuan tidak boleh kosong',
  //       'banyak-pertemuan.numeric' => 'Banyak pertemuan harus berupa angka',
  //       'tanggal-mulai.required' => 'Tanggal mulai tidak boleh kosong',
  //       'tanggal-mulai.date' => 'Tanggal mulai tidak valid',
  //       'ruangan.required' => 'Ruangan tidak boleh kosong',
  //       'ruangan.exists' => 'Ruangan tidak valid',
  //       'group-link.url' => 'Link harus dalam format https',
  //       'hari.required' => 'Hari tidak boleh kosong',
  //       'hari.jadwal_include_tanggal_mulai' => 'Salah satu jadwal harus mencakup hari tanggal mulai',
  //       'hari.*.required' => 'Hari tidak boleh kosong',
  //       'hari.*.numeric' => 'Hari tidak valid',
  //       'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
  //       'waktu-mulai.*.required' => 'Waktu mulai tidak boleh kosong',
  //       'waktu-mulai.*.date_format' => 'Waktu mulai tidak valid',
  //       'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
  //       'waktu-selesai.*.required' => 'Waktu selesai tidak boleh kosong',
  //       'pengajar.required' => 'Pengajar tidak boleh kosong',
  //       'pengajar.*.required' => 'Pengajar tidak boleh kosong',
  //       'pengajar.*.exists' => 'Pengajar tidak valid',
  //       'nik.*.required' => 'NIK / NRP tidak boleh kosong',
  //       'nik.*.numeric' => 'NIK / NRP harus berupa angka',
  //       'nama.*.required' => 'Nama tidak boleh kosong',
  //       'occupation.*.required' => 'Departemen / Occupation tidak boleh kosong',
  //     ]);

  //     if ($validator->fails()) {
  //       return response($validator->errors(), 422);
  //     }

  //     $kelas = Kelas::create([
  //       'kode' => $request['kode-kelas'],
  //       'program_id' => $request['program'],
  //       'tipe_id' => $request['tipe'],
  //       'nomor' => $request['nomor'],
  //       'level_id' => $request['level'],
  //       'banyak_pertemuan' => $request['banyak-pertemuan'],
  //       'tanggal_mulai' => $request['tanggal-mulai'],
  //       'ruangan_id' => $request['ruangan'],
  //       'group_link' => $request['group-link'],
  //     ]);

  //     for ($i = 0; $i < count($request['hari']); $i++) {
  //       $kelas->jadwal()->create([
  //         'hari' => $request['hari'][$i],
  //         'waktu_mulai' => $request['waktu-mulai'][$i],
  //         'waktu_selesai' => $request['waktu-selesai'][$i],
  //       ]);
  //     }

  //     PertemuanKelasGenerator::generate($kelas);

  //     $kelas->pengajar()->sync($request['pengajar']);

  //     for ($i = 0; $i < count($request['nik']); $i++) {
  //       $peserta = Peserta::firstOrCreate(
  //         ['nik' => $request['nik'][$i]],
  //         [
  //           'nama' => $request['nama'][$i],
  //           'occupation' => $request['occupation'][$i],
  //         ]
  //       );
  //       $kelas->peserta()->attach($peserta->id);
  //     }

  //     $request->session()->flash('notification', [
  //       'type' => 'success',
  //       'message' => "Kelas $kelas->kode berhasil ditambahkan"
  //     ]);

  //     return response()->json([
  //       'redirect' => route('kelas.detail-pertemuan', ['kelasId' => $kelas->id])
  //     ], 200);
  //   } catch (ModelNotFoundException $e) {
  //     $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
  //     return response()->json([
  //       'notification' => $notification,
  //     ], 404);
  //   } catch (AuthorizationException $e) {
  //     $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah kelas'])->render();
  //     return response()->json([
  //       'notification' => $notification,
  //     ], 403);
  //   } catch (Exception $e) {
  //     $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
  //     return response()->json([
  //       'notification' => $notification,
  //     ], 500);
  //   }
  // }

  public function store(Request $request)
  {
    Validator::extend('jadwal_include_tanggal_mulai', function ($attribute, $value, $parameters, $validator) {
      $tanggalMulai = Carbon::parse($parameters[0]);
      $hariList = $value;

      $result = false;
      foreach ($hariList as $hari) {
        if ($tanggalMulai->dayOfWeek == $hari) {
          $result = true;
          break;
        }
      }
      return $result;
    });

    try {
      Gate::authorize('create', Kelas::class);

      $validator = Validator::make($request->all(), [
        'kode-kelas' => 'required|unique:kelas,kode',
        'program' => 'required|exists:program_kelas,id',
        'tipe' => 'required|exists:tipe_kelas,id',
        'nomor' => 'required|numeric',
        'level' => 'nullable|exists:level_kelas,id',
        'banyak-pertemuan' => 'required|numeric',
        'tanggal-mulai' => 'required|date',
        'ruangan' => 'required|exists:ruangan,id',
        'group-link' => 'nullable|url:https',
        'hari' => 'required|array|jadwal_include_tanggal_mulai:' . $request['tanggal-mulai'],
        'hari.*' => 'required|numeric',
        'waktu-mulai' => 'required|array',
        'waktu-mulai.*' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|array',
        'waktu-selesai.*' => 'required|date_format:H:i',
        'pengajar' => 'required|array',
        'pengajar.*' => 'required|exists:users,id',
        'nik' => 'nullable|array',
        'nik.*' => 'required|numeric',
        'nama' => 'nullable|array',
        'nama.*' => 'required',
        'occupation' => 'nullable|array',
        'occupation.*' => 'required',
      ], [
        'kode-kelas.required' => 'Kode kelas tidak boleh kosong',
        'kode-kelas.unique' => 'Kode kelas sudah digunakan',
        'program.required' => 'Program tidak boleh kosong',
        'program.exists' => 'Program tidak valid',
        'tipe.required' => 'Tipe tidak boleh kosong',
        'tipe.exists' => 'Tipe tidak valid',
        'nomor.required' => 'Nomor kelas tidak boleh kosong',
        'nomor.numeric' => 'Nomor kelas harus berupa angka',
        'level.exists' => 'Level tidak valid',
        'banyak-pertemuan.required' => 'Banyak pertemuan tidak boleh kosong',
        'banyak-pertemuan.numeric' => 'Banyak pertemuan harus berupa angka',
        'tanggal-mulai.required' => 'Tanggal mulai tidak boleh kosong',
        'tanggal-mulai.date' => 'Tanggal mulai tidak valid',
        'ruangan.required' => 'Ruangan tidak boleh kosong',
        'ruangan.exists' => 'Ruangan tidak valid',
        'group-link.url' => 'Link harus dalam format https',
        'hari.required' => 'Hari tidak boleh kosong',
        'hari.jadwal_include_tanggal_mulai' => 'Salah satu jadwal harus mencakup hari tanggal mulai',
        'hari.*.required' => 'Hari tidak boleh kosong',
        'hari.*.numeric' => 'Hari tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.*.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.*.date_format' => 'Waktu mulai tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
        'waktu-selesai.*.required' => 'Waktu selesai tidak boleh kosong',
        'pengajar.required' => 'Pengajar tidak boleh kosong',
        'pengajar.*.required' => 'Pengajar tidak boleh kosong',
        'pengajar.*.exists' => 'Pengajar tidak valid',
        'nik.*.required' => 'NIK / NRP tidak boleh kosong',
        'nik.*.numeric' => 'NIK / NRP harus berupa angka',
        'nama.*.required' => 'Nama tidak boleh kosong',
        'occupation.*.required' => 'Departemen / Occupation tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      DB::transaction(function () use ($request, &$kelas) {
        $kelas = Kelas::create([
          'kode' => $request['kode-kelas'],
          'program_id' => $request['program'],
          'tipe_id' => $request['tipe'],
          'nomor' => $request['nomor'],
          'level_id' => $request['level'],
          'banyak_pertemuan' => $request['banyak-pertemuan'],
          'tanggal_mulai' => $request['tanggal-mulai'],
          'ruangan_id' => $request['ruangan'],
          'group_link' => $request['group-link'],
        ]);

        for ($i = 0; $i < count($request['hari']); $i++) {
          $kelas->jadwal()->create([
            'hari' => $request['hari'][$i],
            'waktu_mulai' => $request['waktu-mulai'][$i],
            'waktu_selesai' => $request['waktu-selesai'][$i],
          ]);
        }

        PertemuanKelasGenerator::generate($kelas);

        $kelas->pengajar()->sync($request['pengajar']);

        for ($i = 0; $i < count($request['nik']); $i++) {
          $peserta = Peserta::firstOrCreate(
            ['nik' => $request['nik'][$i]],
            [
              'nama' => $request['nama'][$i],
              'occupation' => $request['occupation'][$i],
            ]
          );
          $kelas->peserta()->attach($peserta->id);
        }
      });

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Kelas $kelas->kode berhasil ditambahkan"
      ]);

      return response()->json([
        'redirect' => route('kelas.detail-pertemuan', ['kelasId' => $kelas->id])
      ], 200);
      // 
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah kelas'])->render();
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

  public function detailPertemuan($kelasId, Request $request)
  {
    $kelas = Kelas::with([
      'jadwal',
      'pengajar',
      'pertemuan' => ['ruangan']
    ])->where('id', $kelasId)->firstOrFail();

    Gate::authorize('view', $kelas);

    $kelas->progress = $kelas->pertemuan->where('terlaksana', true)->count();
    $ruanganOptions = Ruangan::all();

    return view('kelas.detail.daftar-pertemuan', [
      'kelas' => $kelas,
      'ruanganOptions' => $ruanganOptions,
    ]);
  }

  public function edit($kelasId, Request $request)
  {
    Gate::authorize('edit', Kelas::class);

    $kelas = Kelas::with(['jadwal', 'pengajar'])->findOrFail($kelasId);

    $programOptions = ProgramKelas::all();
    $tipeOptions = TipeKelas::all();
    $levelOptions = LevelKelas::all();
    $ruanganOptions = Ruangan::all();
    $pengajarOptions = User::role('Staf Pengajar')->get();
    $hariOptions = HariProvider::all();

    return view('kelas.detail.edit-kelas', compact('kelas', 'programOptions', 'tipeOptions', 'levelOptions', 'ruanganOptions', 'pengajarOptions', 'hariOptions'));
  }

  public function update($kelasId, Request $request)
  {
    try {
      Gate::authorize('edit', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);

      $validator = Validator::make($request->all(), [
        'kode-kelas' => 'required|unique:kelas,kode,' . $kelas->id,
        'program' => 'required|exists:program_kelas,id',
        'tipe' => 'required|exists:tipe_kelas,id',
        'nomor' => 'required|numeric',
        'level' => 'nullable|exists:level_kelas,id',
        'banyak-pertemuan' => 'required|numeric',
        'tanggal-mulai' => 'required|date',
        'ruangan' => 'required|exists:ruangan,id',
        'group-link' => 'nullable|url:https',
        'hari' => 'required|array',
        'hari.*' => 'required|numeric',
        'waktu-mulai' => 'required|array',
        'waktu-mulai.*' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|array',
        'waktu-selesai.*' => 'required|date_format:H:i',
        'pengajar' => 'required|array',
        'pengajar.*' => 'required|exists:users,id',
      ], [
        'kode-kelas.required' => 'Kode kelas tidak boleh kosong',
        'kode-kelas.unique' => 'Kode kelas sudah digunakan',
        'program.required' => 'Program tidak boleh kosong',
        'program.exists' => 'Program tidak valid',
        'tipe.required' => 'Tipe tidak boleh kosong',
        'tipe.exists' => 'Tipe tidak valid',
        'nomor.required' => 'Nomor kelas tidak boleh kosong',
        'nomor.numeric' => 'Nomor kelas harus berupa angka',
        'level.exists' => 'Level tidak valid',
        'banyak-pertemuan.required' => 'Banyak pertemuan tidak boleh kosong',
        'banyak-pertemuan.numeric' => 'Banyak pertemuan harus berupa angka',
        'tanggal-mulai.required' => 'Tanggal mulai tidak boleh kosong',
        'tanggal-mulai.date' => 'Tanggal mulai tidak valid',
        'ruangan.required' => 'Ruangan tidak boleh kosong',
        'ruangan.exists' => 'Ruangan tidak valid',
        'group-link.url' => 'Link harus dalam format https',
        'hari.required' => 'Hari tidak boleh kosong',
        'hari.*.required' => 'Hari tidak boleh kosong',
        'hari.*.numeric' => 'Hari tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.*.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.*.date_format' => 'Waktu mulai tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
        'waktu-selesai.*.required' => 'Waktu selesai tidak boleh kosong',
        'pengajar.required' => 'Pengajar tidak boleh kosong',
        'pengajar.*.required' => 'Pengajar tidak boleh kosong',
        'pengajar.*.exists' => 'Pengajar tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $kelas->update([
        'kode' => $request['kode-kelas'],
        'program_id' => $request['program'],
        'tipe_id' => $request['tipe'],
        'nomor' => $request['nomor'],
        'level_id' => $request['level'],
        'banyak_pertemuan' => $request['banyak-pertemuan'],
        'tanggal_mulai' => $request['tanggal-mulai'],
        'ruangan_id' => $request['ruangan'],
        'group_link' => $request['group-link'],
      ]);

      $kelas->jadwal()->delete();
      for ($i = 0; $i < count($request['hari']); $i++) {
        $kelas->jadwal()->create([
          'hari' => $request['hari'][$i],
          'waktu_mulai' => $request['waktu-mulai'][$i],
          'waktu_selesai' => $request['waktu-selesai'][$i],
        ]);
      }

      $kelas->pengajar()->sync($request['pengajar']);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Kelas $kelas->kode berhasil diupdate"
      ]);
      return response()->json([
        'redirect' => route('kelas.detail-pertemuan', ['kelasId' => $kelas->id])
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit kelas'])->render();
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

  public function destroy($kelasId, Request $request)
  {
    try {
      Gate::authorize('delete', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);
      $kelas->delete();

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Kelas $kelas->kode berhasil dihapus"
      ]);

      return response()->json([
        'redirect' => route('kelas.index')
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus kelas'])->render();
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

  public function daftarPeserta($kelasId, Request $request)
  {
    $kelas = Kelas::findOrFail($kelasId);

    Gate::authorize('view', $kelas);

    $pesertaList = $kelas->peserta()->paginate(20);

    return view('kelas.detail.daftar-peserta', compact('kelas', 'pesertaList'));
  }

  public function tambahPeserta($kelasId, Request $request)
  {
    Gate::authorize('kelola-peserta', Kelas::class);

    $kelas = Kelas::findOrFail($kelasId);

    return view('kelas.detail.tambah-peserta', compact('kelas'));
  }

  public function storePeserta($kelasId, Request $request)
  {
    try {
      Gate::authorize('kelola-peserta', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);

      Validator::extend('unique_peserta', function ($attribute, $value, $parameters, $validator) {
        $kelas = Kelas::find($parameters[0]);
        $peserta = Peserta::where('nik', $value)->first();
        if (!$peserta) return true;
        return $kelas->peserta()->where('peserta_id', $peserta->id)->count() == 0;
      });

      $validator = Validator::make($request->all(), [
        'nik' => 'nullable|array',
        'nik.*' => 'required|numeric|unique_peserta:' . $kelas->id,
        'nama' => 'nullable|array',
        'nama.*' => 'required',
        'occupation' => 'nullable|array',
        'occupation.*' => 'required',
      ], [
        'nik.*.required' => 'NIK / NRP tidak boleh kosong',
        'nik.*.numeric' => 'NIK / NRP harus berupa angka',
        'nik.*.unique_peserta' => 'Peserta sudah terdaftar di kelas ini',
        'nama.*.required' => 'Nama tidak boleh kosong',
        'occupation.*.required' => 'Departemen / Occupation tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      for ($i = 0; $i < count($request['nik']); $i++) {
        $peserta = Peserta::firstOrCreate(
          ['nik' => $request['nik'][$i]],
          [
            'nama' => $request['nama'][$i],
            'occupation' => $request['occupation'][$i],
          ]
        );
        $kelas->peserta()->attach($peserta->id);
      }

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Data peserta berhasil ditambahkan'
      ]);

      return response()->json([
        'redirect' => route('kelas.daftar-peserta', ['kelasId' => $kelas->id])
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah peserta'])->render();
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

  public function updatePeserta($kelasId, $pesertaId, Request $request)
  {
    try {
      Gate::authorize('kelola-peserta', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);
      $peserta = $kelas->peserta()->findOrFail($pesertaId);

      if ($request->has('status-peserta')) {
        $kelas->peserta()->updateExistingPivot($peserta->id, ['aktif' => 1]);
      } else {
        $kelas->peserta()->updateExistingPivot($peserta->id, ['aktif' => 0]);
      }

      $notification = view('components.notification', ['type' => 'success', 'message' => 'Data peserta berhasil diubah'])->render();
      $statusPeserta = view('kelas.partials.status-peserta', ['aktif' => $request->has('status-peserta')])->render();

      return response()->json([
        'notification' => $notification,
        'statusPeserta' => $statusPeserta,
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit peserta'])->render();
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

  public function destroyPeserta($kelasId, $pesertaId, Request $request)
  {
    try {
      Gate::authorize('kelola-peserta', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);
      $peserta = $kelas->peserta()->findOrFail($pesertaId);

      $kelas->pertemuan()->whereHas('presensi', function ($query) use ($peserta) {
        $query->where('peserta_id', $peserta->id);
      })->delete();

      $kelas->peserta()->detach($peserta->id);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Peserta $peserta->nama berhasil dihapus dari kelas $kelas->kode"
      ]);

      return response()->json([
        'redirect' => route('kelas.daftar-peserta', ['kelasId' => $kelas->id])
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah peserta'])->render();
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
}
