<?php

namespace App\Http\Controllers;

use App\Models\KategoriKelas;
use Exception;
use App\Models\Kelas;
use App\Models\TipeKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipeKelasController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaKelas', Kelas::class);
      $tipeList = TipeKelas::with('kategori')->paginate(self::$pageCount);
      $tipeTable = view('kelas.partials.tipe-table', ['tipeList' => $tipeList])->render();
      return response()->json([
        'table' => $tipeTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $tipeList = TipeKelas::with('kategori')->paginate(self::$pageCount);
    $kategoriList = KategoriKelas::all();
    return view('kelas.tipe.daftar-tipe', compact('tipeList', 'kategoriList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:tipe_kelas,kode',
        'kategori' => 'nullable|exists:kategori_kelas,id',
      ], [
        'nama.required' => 'Nama tipe kelas tidak boleh kosong',
        'nama.string' => 'Nama tipe kelas tidak valid',
        'kode.required' => 'Kode tipe kelas tidak boleh kosong',
        'kode.string' => 'Kode tipe kelas tidak valid',
        'kode.unique' => 'Kode tipe kelas sudah digunakan',
        'kategori.exists' => 'Kategori tipe kelas tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tipe = TipeKelas::create([
        'nama' => $request->nama,
        'kode' => $request->kode,
        'kategori_id' => $request->kategori,
      ]);

      $tipeList = TipeKelas::with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil ditambahkan"])->render();
      $tipeTable = view('kelas.partials.tipe-table', compact('tipeList'))->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe berhasil ditambahkan"])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $tipeTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah tipe kelas'])->render();
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

  public function update(Request $request, $tipeId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:tipe_kelas,kode,' . $tipeId,
        'kategori' => 'nullable|exists:kategori_kelas,id',
      ], [
        'nama.required' => 'Nama tipe kelas tidak boleh kosong',
        'nama.string' => 'Nama tipe kelas tidak valid',
        'kode.required' => 'Kode tipe kelas tidak boleh kosong',
        'kode.string' => 'Kode tipe kelas tidak valid',
        'kode.unique' => 'Kode tipe kelas sudah digunakan',
        'kategori.exists' => 'Kategori tipe kelas tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tipe = TipeKelas::findOrFail($tipeId);
      $tipe->update([
        'nama' => $request->nama,
        'kode' => $request->kode,
        'kategori_id' => $request->kategori,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil diupdate"])->render();
      $tipeItem = view('kelas.partials.tipe-item', ['tipe' => $tipe])->render();
      return response()->json([
        'notification' => $notification,
        'tipeItem' => $tipeItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tipe tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah tipe'])->render();
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

  public function destroy($tipeId, Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $tipe = TipeKelas::findOrFail($tipeId);
      if ($request->input('force')) {
        $tipe->forceDelete();
      } else {
        $tipe->delete();
      }

      $tipeList = TipeKelas::with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil dihapus"])->render();
      $tipeTable = view('kelas.partials.tipe-table', compact('tipeList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $tipeTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tipe tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus tipe]'])->render();
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
      $tipeList = TipeKelas::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
      $tipeTable = view('kelas.partials.tipe-table', ['tipeList' => $tipeList, 'deleted' => true])->render();
      return response()->json([
        'table' => $tipeTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $tipeList = TipeKelas::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
    return view('kelas.tipe.deleted-tipe', compact('tipeList'));
  }

  public function restore($tipeId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);
      $tipe = TipeKelas::onlyTrashed()->where('id', $tipeId)->firstOrFail();
      $tipe->restore();

      $tipeList = TipeKelas::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-kelas.deleted'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil direstore"])->render();
      $tipeTable = view('kelas.partials.tipe-table', ['tipeList' => $tipeList, 'deleted' => true])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $tipeTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Tipe tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk merestore tipe]'])->render();
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
