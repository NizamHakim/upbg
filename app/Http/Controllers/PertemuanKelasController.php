<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Helpers\HariProvider;
use App\Models\PertemuanKelas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SchedulingConflictChecker;
use App\Exceptions\SchedulingConflictException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PertemuanKelasController extends Controller
{
  public function store($kelasId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('tambahPertemuan', $kelas);

      $validator = Validator::make($request->all(), [
        'tanggal' => 'required|date',
        'waktu-mulai' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|date_format:H:i',
        'ruangan' => 'required|exists:ruangan,id',
      ], [
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'tanggal.date' => 'Tanggal tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.date_format' => 'Waktu mulai tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
        'waktu-selesai.date_format' => 'Waktu selesai tidak valid',
        'ruangan.required' => 'Ruangan tidak boleh kosong',
        'ruangan.exists' => 'Ruangan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      if (SchedulingConflictChecker::checkForConflict($request['ruangan'], $request['tanggal'], $request['waktu-mulai'], $request['waktu-selesai'])) {
        $ruangan = Ruangan::findOrFail($request['ruangan']);
        $tanggal = Carbon::parse($request['tanggal']);
        throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$ruangan->kode}");
      }

      $kelas->pertemuan()->create([
        'pertemuan_ke' => $kelas->pertemuan()->count() + 1,
        'tanggal' => $request['tanggal'],
        'waktu_mulai' => $request['waktu-mulai'],
        'waktu_selesai' => $request['waktu-selesai'],
        'ruangan_id' => $request['ruangan'],
      ]);

      $this->reorder($kelas);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Pertemuan berhasil ditambahkan',
      ]);

      return response()->json([
        'redirect' => route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kelas tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah pertemuan'])->render();
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

  public function detail($kelasId, $pertemuanId, Request $request)
  {
    $kelas = Kelas::with(['pengajar'])->findOrFail($kelasId);

    Gate::authorize('view', $kelas);

    $pertemuan = PertemuanKelas::with([
      'ruangan',
      'presensi' => ['peserta']
    ])->findOrFail($pertemuanId);

    $kelas->load([
      'peserta' => function ($query) use ($pertemuan) {
        $query->whereNotIn('peserta_id', $pertemuan->presensi->pluck('peserta_id'));
      }
    ]);

    $ruanganOptions = Ruangan::all();
    $hariOptions = HariProvider::all();

    return view('kelas.detail.pertemuan.detail-pertemuan', compact('kelas', 'pertemuan', 'ruanganOptions', 'hariOptions'));
  }

  public function edit($kelasId, $pertemuanId, Request $request)
  {
    Gate::authorize('editPertemuan', Kelas::class);

    $kelas = Kelas::findOrFail($kelasId);
    $pertemuan = PertemuanKelas::with(['ruangan'])->findOrFail($pertemuanId);

    $ruanganOptions = Ruangan::all();

    return view('kelas.detail.pertemuan.edit-pertemuan', compact('kelas', 'pertemuan', 'ruanganOptions'));
  }

  public function update($kelasId, $pertemuanId, Request $request)
  {
    try {
      Gate::authorize('editPertemuan', Kelas::class);

      $kelas = Kelas::findOrFail($kelasId);
      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);

      $validator = Validator::make($request->all(), [
        'terlaksana' => 'required|boolean',
        'pengajar' => 'nullable|exists:pengajar_kelas,user_id',
        'tanggal' => 'required|date',
        'waktu-mulai' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|date_format:H:i',
        'ruangan' => 'required|exists:ruangan,id',
      ], [
        'terlaksana.required' => 'Status terlaksana tidak boleh kosong',
        'terlaksana.boolean' => 'Status terlaksana tidak valid',
        'pengajar.exists' => 'Pengajar tidak valid',
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'tanggal.date' => 'Tanggal tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.date_format' => 'Waktu mulai tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
        'waktu-selesai.date_format' => 'Waktu selesai tidak valid',
        'ruangan.required' => 'Ruangan tidak boleh kosong',
        'ruangan.exists' => 'Ruangan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      if (SchedulingConflictChecker::checkForConflict($request['ruangan'], $request['tanggal'], $request['waktu-mulai'], $request['waktu-selesai'], $pertemuanId)) {
        $ruangan = Ruangan::findOrFail($request['ruangan']);
        $tanggal = Carbon::parse($request['tanggal']);
        throw new SchedulingConflictException("Terdapat scheduling conflict pada {$tanggal->isoFormat('dddd, D MMMM Y')} di ruangan {$ruangan->kode}");
      }

      if ($request['terlaksana'] == 0) {
        $pertemuan->presensi()->delete();
      } else if ($pertemuan->terlaksana == 0 && $request['terlaksana'] == 1) {
        $this->generatePresensi($kelas, $pertemuan);
      }

      $pertemuan->update([
        'terlaksana' => $request['terlaksana'],
        'pengajar_id' => $request['pengajar'],
        'tanggal' => $request['tanggal'],
        'waktu_mulai' => $request['waktu-mulai'],
        'waktu_selesai' => $request['waktu-selesai'],
        'ruangan_id' => $request['ruangan'],
      ]);

      $this->reorder($kelas);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Pertemuan berhasil diupdate',
      ]);

      return response()->json([
        'redirect' => route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Pertemuan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit pertemuan'])->render();
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

  public function mulaiPertemuan($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);
      Gate::authorize('mulaiPertemuan', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);

      $validator = Validator::make($request->all(), [
        'pengajar' => 'required|exists:pengajar_kelas,user_id',
      ], [
        'pengajar.required' => 'Pengajar tidak boleh kosong',
        'pengajar.exists' => 'Pengajar tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $pertemuan->update([
        'terlaksana' => true,
        'pengajar_id' => $request['pengajar'],
      ]);

      $this->generatePresensi($kelas, $pertemuan);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Pertemuan berhasil dimulai',
      ]);

      return response()->json([
        'redirect' => route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Pertemuan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk memulai pertemuan'])->render();
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

  public function reschedulePertemuan($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('reschedulePertemuan', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);

      $validator = Validator::make($request->all(), [
        'tanggal' => 'required|date',
        'waktu-mulai' => 'required|date_format:H:i',
        'waktu-selesai' => 'required|date_format:H:i',
        'ruangan' => 'required|exists:ruangan,id',
      ], [
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'tanggal.date' => 'Tanggal tidak valid',
        'waktu-mulai.required' => 'Waktu mulai tidak boleh kosong',
        'waktu-mulai.date_format' => 'Waktu mulai tidak valid',
        'waktu-selesai.required' => 'Waktu selesai tidak boleh kosong',
        'waktu-selesai.date_format' => 'Waktu selesai tidak valid',
        'ruangan.required' => 'Ruangan tidak boleh kosong',
        'ruangan.exists' => 'Ruangan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $pertemuan->update([
        'tanggal' => $request['tanggal'],
        'waktu_mulai' => $request['waktu-mulai'],
        'waktu_selesai' => $request['waktu-selesai'],
        'ruangan_id' => $request['ruangan'],
      ]);

      $this->reorder($kelas);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Pertemuan berhasil direschedule',
      ]);

      return response()->json([
        'redirect' => route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Pertemuan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mereschedule pertemuan'])->render();
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

  public function destroy($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('deletePertemuan', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);
      $pertemuan->delete();

      $this->reorder($kelas);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => 'Pertemuan berhasil dihapus',
      ]);

      return response()->json([
        'redirect' => route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Pertemuan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus pertemuan'])->render();
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

  public function updateTopikCatatan($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('editTopikCatatan', $kelas);

      $validator = Validator::make($request->all(), [
        'topik' => 'nullable|string',
        'catatan' => 'nullable|string',
      ], [
        'topik.string' => 'Topik tidak valid',
        'catatan.string' => 'Catatan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);

      $pertemuan->update([
        'topik' => $request->topik,
        'catatan' => $request->catatan,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => 'Topik dan catatan berhasil diubah'])->render();
      return response()->json([
        'notification' => $notification,
        'topikCatatan' => view('kelas.partials.topik-catatan', ['pertemuan' => $pertemuan])->render(),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Pertemuan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit topik dan catatan'])->render();
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

  private function reorder($kelas)
  {
    $pertemuanList = $kelas->pertemuan()->get();
    $pertemuanList->each(function ($pertemuan, $index) {
      $pertemuan->update(['pertemuan_ke' => $index + 1]);
    });
  }

  private function generatePresensi($kelas, $pertemuan)
  {
    $peserta = $kelas->peserta()->wherePivot('aktif', 1)->get();

    $pertemuan->presensi()->createMany(
      $peserta->map(function ($peserta) {
        return ['peserta_id' => $peserta->id];
      })->toArray()
    );
  }
}
