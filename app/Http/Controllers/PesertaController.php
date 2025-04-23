<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PesertaController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    Gate::authorize('lihatPeserta', Peserta::class);
    $pesertaList = Peserta::query();

    $pesertaList->when($request->query('search'), function ($query) use ($request) {
      return $query
        ->where('nama', 'like', '%' . $request->query('search') . '%')
        ->orWhere('nik', 'like', '%' . $request->query('search') . '%')
        ->orWhere('occupation', 'like', '%' . $request->query('search') . '%');
    });

    $pesertaList = $pesertaList->paginate(self::$pageCount)->withQueryString();
    $request->flash();

    return view('peserta.daftar-peserta', compact('pesertaList'));
  }

  public function detail($pesertaId, Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('lihatPeserta', Peserta::class);
      if ($request->query('kelas')) {
        $peserta = Peserta::findOrFail($pesertaId);
        $historiKelas = $peserta->kelas()->paginate($perPage = 10, $columns = ['*'], $pageName = 'kelas')->withQueryString();
        $historiKelasTable = view('peserta.partials.histori-kelas', compact('historiKelas'))->render();
        return response()->json([
          'table' => $historiKelasTable,
        ], 200);
      } else if ($request->query('tes')) {
        $peserta = Peserta::findOrFail($pesertaId);
        $historiTes = $peserta->pivotTes()->with('tes')->paginate($perPage = 10, $columns = ['*'], $pageName = 'tes')->withQueryString();
        $historiTesTable = view('peserta.partials.histori-tes', compact('historiTes'))->render();
        return response()->json([
          'table' => $historiTesTable,
        ], 200);
      }
    }
    Gate::authorize('lihatPeserta', Peserta::class);
    $peserta = Peserta::findOrFail($pesertaId);
    $historiKelas = $peserta->kelas()->paginate($perPage = 10, $columns = ['*'], $pageName = 'kelas')->withQueryString();
    $historiTes = $peserta->pivotTes()->with('tes')->paginate($perPage = 10, $columns = ['*'], $pageName = 'tes')->withQueryString();
    return view('peserta.detail-peserta', compact('peserta', 'historiKelas', 'historiTes'));
  }

  public function update($pesertaId, Request $request)
  {
    try {
      Gate::authorize('kelolaPeserta', Peserta::class);

      $validator = Validator::make($request->all(), [
        'nik' => 'required|numeric|unique:peserta,nik,' . $pesertaId,
        'nama' => 'required',
        'occupation' => 'required',
      ], [
        'nik.required' => 'NIK / NRP tidak boleh kosong',
        'nik.numeric' => 'NIK / NRP tidak valid',
        'nik.unique' => 'NIK / NRP sudah terdaftar',
        'nama.required' => 'Nama tidak boleh kosong',
        'occupation.required' => 'Departemen / Occupation tidak boleh kosong',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $peserta = Peserta::findOrFail($pesertaId);
      $peserta->update([
        'nik' => $request->nik,
        'nama' => $request->nama,
        'occupation' => $request->occupation,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Peserta $peserta->nama ($peserta->nik) berhasil diupdate"])->render();
      $dataPeserta = view('peserta.partials.data-peserta', compact('peserta'))->render();

      return response()->json([
        'notification' => $notification,
        'dataPeserta' => $dataPeserta,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Peserta tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah peserta'])->render();
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

  public function destroy($pesertaId, Request $request)
  {
    try {
      $peserta = Peserta::findOrFail($pesertaId);
      $peserta->delete();
      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Peserta $peserta->nama berhasil dihapus",
      ]);
      return response()->json([
        'redirect' => route('peserta.index'),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Peserta tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus peserta'])->render();
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
