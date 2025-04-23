<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\PresensiKelas;
use App\Models\PertemuanKelas;
use function Illuminate\Log\log;
use Illuminate\Support\Facades\Validator;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PresensiKelasController extends Controller
{
  public function store($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('tambahPresensiKelas', $kelas);

      $validator = Validator::make($request->all(), [
        'peserta' => 'required|exists:peserta,id',
        'hadir' => 'required|boolean',
      ], [
        'peserta.required' => 'Peserta tidak boleh kosong',
        'peserta.exists' => 'Peserta tidak valid',
        'hadir.required' => 'Status kehadiran tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);
      $pertemuan->presensi()->create([
        'peserta_id' => $request->peserta,
        'hadir' => $request->hadir,
      ]);

      $peserta = $kelas->peserta()->findOrFail($request->peserta);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Berhasil menambahkan {$peserta->nama} ke daftar kehadiran",
      ]);

      return response()->json([
        'redirect' => route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Presensi tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah presensi'])->render();
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

  public function togglePresensi($kelasId, $pertemuanId, $presensiId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('ubahPresensiKelas', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);
      $presensi = PresensiKelas::findOrFail($presensiId);

      $validator = Validator::make($request->all(), [
        'hadir' => 'required|boolean',
      ]);

      if ($validator->fails()) {
        abort(500);
      }

      $presensi->update([
        'hadir' => $request->hadir,
      ]);

      $btnPresensi = view('components.btn-presensi', ['presensi' => $presensi])->render();
      $hadirCount = view('components.hadir-count', ['hadir' => $pertemuan->hadirCount, 'total' => $pertemuan->presensi()->count()])->render();

      return response()->json([
        'btnPresensi' => $btnPresensi,
        'hadirCount' => $hadirCount,
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Presensi tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah status kehadiran'])->render();
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

  public function hadirSemua($kelasId, $pertemuanId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('ubahPresensiKelas', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);
      $pertemuan->presensi()->update([
        'hadir' => true,
      ]);

      $hadirCount = view('components.hadir-count', ['hadir' => $pertemuan->hadirCount, 'total' => $pertemuan->presensi()->count()])->render();
      $presensiTable = view('kelas.partials.presensi-table', ['kelas' => $kelas, 'pertemuan' => $pertemuan])->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => 'Berhasil mengubah data kehadiran'])->render();

      return response()->json([
        'hadirCount' => $hadirCount,
        'presensiTable' => $presensiTable,
        'notification' => $notification,
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Presensi tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah status kehadiran'])->render();
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

  public function destroy($kelasId, $pertemuanId, $presensiId, Request $request)
  {
    try {
      $kelas = Kelas::findOrFail($kelasId);

      Gate::authorize('hapusPresensiKelas', $kelas);

      $pertemuan = PertemuanKelas::findOrFail($pertemuanId);
      $presensi = PresensiKelas::findOrFail($presensiId);

      $presensi->delete();

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Berhasil menghapus {$presensi->peserta->nama} dari daftar kehadiran",
      ]);

      return response()->json([
        'redirect' => route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]),
      ]);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Presensi tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus status kehadiran'])->render();
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
