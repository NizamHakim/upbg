<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tes;
use App\Models\KategoriTes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriTesController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaTes', Tes::class);
      $kategoriList = KategoriTes::paginate(self::$pageCount);
      $kategoriTable = view('tes.partials.kategori-table', compact('kategoriList'))->render();
      return response()->json([
        'table' => $kategoriTable,
      ], 200);
    }
    Gate::authorize('kelolaTes', Tes::class);
    $kategoriList = KategoriTes::paginate(self::$pageCount);
    return view('tes.kategori.daftar-kategori', compact('kategoriList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaTes', Tes::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
      ], [
        'nama.required' => 'Nama kategori tes tidak boleh kosong',
        'nama.string' => 'Nama kategori tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $kategori = KategoriTes::create([
        'nama' => $request->nama,
      ]);

      $kategoriList = KategoriTes::paginate(self::$pageCount);
      $kategoriList->setPath(route('kategori-tes.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil ditambahkan"])->render();
      $kategoriTable = view('tes.partials.kategori-table', compact('kategoriList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $kategoriTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah kategori tes'])->render();
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
      Gate::authorize('kelolaTes', Tes::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
      ], [
        'nama.required' => 'Nama kategori tes tidak boleh kosong',
        'nama.string' => 'Nama kategori tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $kategori = KategoriTes::findOrFail($kategoriId);
      $kategori->update([
        'nama' => $request->nama,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil diupdate"])->render();
      $kategoriItem = view('tes.partials.kategori-item', ['kategori' => $kategori])->render();
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
      Gate::authorize('kelolaTes', Tes::class);

      $kategori = KategoriTes::findOrFail($kategoriId);
      if ($request->input('force')) {
        $kategori->forceDelete();
      } else {
        $kategori->delete();
      }

      $kategoriList = KategoriTes::paginate(self::$pageCount);
      $kategoriList->setPath(route('kategori-tes.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Kategori $kategori->nama berhasil dihapus"])->render();
      $kategoriTable = view('tes.partials.kategori-table', compact('kategoriList'))->render();
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
