<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use App\Models\KategoriKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriKelasController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaKelas', Kelas::class);
      $kategoriList = KategoriKelas::paginate(self::$pageCount);
      $kategoriTable = view('kelas.partials.kategori-table', compact('kategoriList'))->render();
      return response()->json([
        'table' => $kategoriTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $kategoriList = KategoriKelas::paginate(self::$pageCount);
    return view('kelas.kategori.daftar-kategori', compact('kategoriList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
      ], [
        'nama.required' => 'Nama kategori kelas tidak boleh kosong',
        'nama.string' => 'Nama kategori kelas tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $kategori = KategoriKelas::create([
        'nama' => $request->nama,
      ]);

      $kategoriList = KategoriKelas::paginate(self::$pageCount);
      $kategoriList->setPath(route('kategori-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil ditambahkan"])->render();
      $kategoriTable = view('kelas.partials.kategori-table', compact('kategoriList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $kategoriTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah kategori kelas'])->render();
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

  public function update(Request $request, $kategoriId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
      ], [
        'nama.required' => 'Nama kategori kelas tidak boleh kosong',
        'nama.string' => 'Nama kategori kelas tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $kategori = KategoriKelas::findOrFail($kategoriId);
      $kategori->update([
        'nama' => $request->nama,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil diupdate"])->render();
      $kategoriItem = view('kelas.partials.kategori-item', ['kategori' => $kategori])->render();
      return response()->json([
        'notification' => $notification,
        'kategoriItem' => $kategoriItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kategori tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah kategori'])->render();
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

  public function destroy($kategoriId, Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $kategori = KategoriKelas::findOrFail($kategoriId);
      if ($request->input('force')) {
        $kategori->forceDelete();
      } else {
        $kategori->delete();
      }

      $kategoriList = KategoriKelas::paginate(self::$pageCount);
      $kategoriList->setPath(route('kategori-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil dihapus"])->render();
      $kategoriTable = view('kelas.partials.kategori-table', compact('kategoriList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $kategoriTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Kategori tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus kategori]'])->render();
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
