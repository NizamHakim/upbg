<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use App\Models\LevelKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LevelKelasController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaKelas', Kelas::class);
      $levelList = LevelKelas::paginate(self::$pageCount);
      $levelTable = view('kelas.partials.level-table', ['levelList' => $levelList])->render();
      return response()->json([
        'table' => $levelTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $levelList = LevelKelas::paginate(self::$pageCount);
    return view('kelas.level.daftar-level', compact('levelList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:level_kelas,kode',
      ], [
        'nama.required' => 'Nama level kelas tidak boleh kosong',
        'nama.string' => 'Nama level kelas tidak valid',
        'kode.required' => 'Kode level kelas tidak boleh kosong',
        'kode.string' => 'Kode level kelas tidak valid',
        'kode.unique' => 'Kode level kelas sudah digunakan',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $level = LevelKelas::create([
        'nama' => $request->nama,
        'kode' => $request->kode,
      ]);

      $levelList = LevelKelas::paginate(self::$pageCount);
      $levelList->setPath(route('level-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Level $level->nama ($level->kode) berhasil ditambahkan"])->render();
      $levelTable = view('kelas.partials.level-table', compact('levelList'))->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => "Level berhasil ditambahkan"])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $levelTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah level kelas'])->render();
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

  public function update(Request $request, $levelId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:level_kelas,kode,' . $levelId,
      ], [
        'nama.required' => 'Nama level kelas tidak boleh kosong',
        'nama.string' => 'Nama level kelas tidak valid',
        'kode.required' => 'Kode level kelas tidak boleh kosong',
        'kode.string' => 'Kode level kelas tidak valid',
        'kode.unique' => 'Kode level kelas sudah digunakan',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $level = LevelKelas::findOrFail($levelId);
      $level->update([
        'nama' => $request->nama,
        'kode' => $request->kode,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Level $level->nama ($level->kode) berhasil diupdate"])->render();
      $levelItem = view('kelas.partials.level-item', ['level' => $level])->render();
      return response()->json([
        'notification' => $notification,
        'levelItem' => $levelItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Level tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah level'])->render();
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

  public function destroy($levelId, Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $level = LevelKelas::findOrFail($levelId);
      if ($request->input('force')) {
        $level->forceDelete();
      } else {
        $level->delete();
      }

      $levelList = LevelKelas::paginate(self::$pageCount);
      $levelList->setPath(route('level-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Level $level->nama ($level->kode) berhasil dihapus"])->render();
      $levelTable = view('kelas.partials.level-table', compact('levelList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $levelTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Level tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus level]'])->render();
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
      Gate::authorize('kelolaKelas', Kelas::class);
      $levelList = LevelKelas::onlyTrashed()->paginate(self::$pageCount);
      $levelTable = view('kelas.partials.level-table', ['levelList' => $levelList, 'deleted' => true])->render();
      return response()->json([
        'table' => $levelTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $levelList = LevelKelas::onlyTrashed()->paginate(self::$pageCount);
    return view('kelas.level.deleted-level', compact('levelList'));
  }

  public function restore($levelId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);
      $level = LevelKelas::onlyTrashed()->where('id', $levelId)->firstOrFail();
      $level->restore();

      $levelList = LevelKelas::onlyTrashed()->paginate(self::$pageCount);
      $levelList->setPath(route('level-kelas.deleted'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Level $level->nama ($level->kode) berhasil direstore"])->render();
      $levelTable = view('kelas.partials.level-table', ['levelList' => $levelList, 'deleted' => true])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $levelTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Level tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk merestore level]'])->render();
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
