<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tes;
use App\Models\TipeTes;
use App\Models\KategoriTes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TipeTesController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaTes', Tes::class);
      $tipeList = TipeTes::with('kategori')->paginate(self::$pageCount);
      $tipeTable = view('tes.partials.tipe-table', ['tipeList' => $tipeList])->render();
      return response()->json([
        'table' => $tipeTable,
      ], 200);
    }
    Gate::authorize('kelolaTes', Tes::class);
    $tipeList = TipeTes::with('kategori')->paginate(self::$pageCount);
    $kategoriList = KategoriTes::all();
    return view('tes.tipe.daftar-tipe', compact('tipeList', 'kategoriList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaTes', Tes::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:tipe_tes,kode',
        'kategori' => 'nullable|exists:kategori_tes,id',
      ], [
        'nama.required' => 'Nama tipe tes tidak boleh kosong',
        'nama.string' => 'Nama tipe tes tidak valid',
        'kode.required' => 'Kode tipe tes tidak boleh kosong',
        'kode.string' => 'Kode tipe tes tidak valid',
        'kode.unique' => 'Kode tipe tes sudah digunakan',
        'kategori.exists' => 'Kategori tipe tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tipe = TipeTes::create([
        'nama' => $request->nama,
        'kode' => $request->kode,
        'kategori_id' => $request->kategori,
      ]);

      $tipeList = TipeTes::with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-tes.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil ditambahkan"])->render();
      $tipeTable = view('tes.partials.tipe-table', compact('tipeList'))->render();
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe berhasil ditambahkan"])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $tipeTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah tipe tes'])->render();
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
      Gate::authorize('kelolaTes', Tes::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:tipe_tes,kode,' . $tipeId,
        'kategori' => 'nullable|exists:kategori_tes,id',
      ], [
        'nama.required' => 'Nama tipe tes tidak boleh kosong',
        'nama.string' => 'Nama tipe tes tidak valid',
        'kode.required' => 'Kode tipe tes tidak boleh kosong',
        'kode.string' => 'Kode tipe tes tidak valid',
        'kode.unique' => 'Kode tipe tes sudah digunakan',
        'kategori.exists' => 'Kategori tipe tes tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $tipe = TipeTes::findOrFail($tipeId);
      $tipe->update([
        'nama' => $request->nama,
        'kode' => $request->kode,
        'kategori_id' => $request->kategori,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil diupdate"])->render();
      $tipeItem = view('tes.partials.tipe-item', ['tipe' => $tipe])->render();
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
      Gate::authorize('kelolaTes', Tes::class);

      $tipe = TipeTes::findOrFail($tipeId);
      if ($request->input('force')) {
        $tipe->forceDelete();
      } else {
        $tipe->delete();
      }

      $tipeList = TipeTes::with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-tes.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil dihapus"])->render();
      $tipeTable = view('tes.partials.tipe-table', compact('tipeList'))->render();
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
      Gate::authorize('kelolaTes', Tes::class);
      $tipeList = TipeTes::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
      $tipeTable = view('tes.partials.tipe-table', ['tipeList' => $tipeList, 'deleted' => true])->render();
      return response()->json([
        'table' => $tipeTable,
      ], 200);
    }
    Gate::authorize('kelolaTes', Tes::class);
    $tipeList = TipeTes::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
    return view('tes.tipe.deleted-tipe', compact('tipeList'));
  }

  public function restore($tipeId)
  {
    try {
      Gate::authorize('kelolaTes', Tes::class);
      $tipe = TipeTes::onlyTrashed()->where('id', $tipeId)->firstOrFail();
      $tipe->restore();

      $tipeList = TipeTes::onlyTrashed()->with('kategori')->paginate(self::$pageCount);
      $tipeList->setPath(route('tipe-tes.deleted'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Tipe $tipe->nama ($tipe->kode) berhasil direstore"])->render();
      $tipeTable = view('tes.partials.tipe-table', ['tipeList' => $tipeList, 'deleted' => true])->render();
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
