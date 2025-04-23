<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RuanganController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaRuangan', Ruangan::class);
      $ruanganList = Ruangan::paginate(self::$pageCount);
      $ruanganTable = view('ruangan.partials.ruangan-table', compact('ruanganList'))->render();
      return response()->json([
        'table' => $ruanganTable,
      ], 200);
    }
    Gate::authorize('kelolaRuangan', Ruangan::class);
    $ruanganList = Ruangan::paginate(self::$pageCount);
    return view('ruangan.daftar-ruangan', compact('ruanganList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaRuangan', Ruangan::class);

      $validator = Validator::make($request->all(), [
        'kode' => 'required|string|unique:ruangan,kode',
        'kapasitas' => 'required|integer',
      ], [
        'kode.required' => 'Kode ruangan kelas tidak boleh kosong',
        'kode.string' => 'Kode ruangan kelas tidak valid',
        'kode.unique' => 'Kode ruangan kelas sudah digunakan',
        'kapasitas.required' => 'Kapasitas ruangan tidak boleh kosong',
        'kapasitas.integer' => 'Kapasitas ruangan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $ruangan = Ruangan::create([
        'kode' => $request->kode,
        'kapasitas' => $request->kapasitas,
      ]);

      $ruanganList = Ruangan::paginate(self::$pageCount);
      $ruanganList->setPath(route('ruangan.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Ruangan $ruangan->kode berhasil ditambahkan"])->render();
      $ruanganTable = view('ruangan.partials.ruangan-table', compact('ruanganList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $ruanganTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah ruangan'])->render();
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

  public function update(Request $request, $ruanganId)
  {
    try {
      Gate::authorize('kelolaRuangan', Ruangan::class);

      $validator = Validator::make($request->all(), [
        'kode' => 'required|string|unique:ruangan,kode,' . $ruanganId,
        'kapasitas' => 'required|integer',
      ], [
        'kode.required' => 'Kode ruangan kelas tidak boleh kosong',
        'kode.string' => 'Kode ruangan kelas tidak valid',
        'kode.unique' => 'Kode ruangan kelas sudah digunakan',
        'kapasitas.required' => 'Kapasitas ruangan tidak boleh kosong',
        'kapasitas.integer' => 'Kapasitas ruangan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $ruangan = Ruangan::findOrFail($ruanganId);
      $ruangan->update([
        'kode' => $request->kode,
        'kapasitas' => $request->kapasitas,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Ruangan $ruangan->kode berhasil diupdate"])->render();
      $ruanganItem = view('ruangan.partials.ruangan-item', ['ruangan' => $ruangan])->render();
      return response()->json([
        'notification' => $notification,
        'ruanganItem' => $ruanganItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Ruangan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah ruangan'])->render();
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

  public function destroy($ruanganId, Request $request)
  {
    try {
      Gate::authorize('kelolaRuangan', Ruangan::class);

      $ruangan = Ruangan::findOrFail($ruanganId);
      if ($request->input('force')) {
        $ruangan->forceDelete();
      } else {
        $ruangan->delete();
      }

      $ruanganList = Ruangan::paginate(self::$pageCount);
      $ruanganList->setPath(route('ruangan.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Ruangan $ruangan->kode berhasil dihapus"])->render();
      $ruanganTable = view('ruangan.partials.ruangan-table', compact('ruanganList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $ruanganTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Ruangan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus ruangan]'])->render();
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

  public function indexRestore(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaRuangan', Ruangan::class);
      $ruanganList = Ruangan::onlyTrashed()->paginate(self::$pageCount);
      $ruanganTable = view('ruangan.partials.ruangan-table', ['ruanganList' => $ruanganList, 'deleted' => true])->render();
      return response()->json([
        'table' => $ruanganTable,
      ], 200);
    }
    Gate::authorize('kelolaRuangan', Ruangan::class);
    $ruanganList = Ruangan::onlyTrashed()->paginate(self::$pageCount);
    return view('ruangan.deleted-ruangan', compact('ruanganList'));
  }

  public function restore($ruanganId)
  {
    try {
      Gate::authorize('kelolaRuangan', Ruangan::class);
      $ruangan = Ruangan::onlyTrashed()->where('id', $ruanganId)->firstOrFail();
      $ruangan->restore();

      $ruanganList = Ruangan::onlyTrashed()->paginate(self::$pageCount);
      $ruanganList->setPath(route('ruangan.deleted'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Ruangan $ruangan->kode berhasil direstore"])->render();
      $ruanganTable = view('ruangan.partials.ruangan-table', ['ruanganList' => $ruanganList, 'deleted' => true])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $ruanganTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Ruangan tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk merestore ruangan]'])->render();
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
