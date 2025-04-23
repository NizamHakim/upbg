<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use App\Models\ProgramKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProgramKelasController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    if ($request->ajax()) {
      Gate::authorize('kelolaKelas', Kelas::class);
      $programList = ProgramKelas::paginate(self::$pageCount);
      $programTable = view('kelas.partials.program-table', compact('programList'))->render();
      return response()->json([
        'table' => $programTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $programList = ProgramKelas::paginate(self::$pageCount);
    return view('kelas.program.daftar-program', compact('programList'));
  }

  public function store(Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:program_kelas,kode',
      ], [
        'nama.required' => 'Nama program kelas tidak boleh kosong',
        'nama.string' => 'Nama program kelas tidak valid',
        'kode.required' => 'Kode program kelas tidak boleh kosong',
        'kode.string' => 'Kode program kelas tidak valid',
        'kode.unique' => 'Kode program kelas sudah digunakan',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $program = ProgramKelas::create([
        'nama' => $request->nama,
        'kode' => $request->kode,
      ]);

      $programList = ProgramKelas::paginate(self::$pageCount);
      $programList->setPath(route('program-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Program $program->nama ($program->kode) berhasil ditambahkan"])->render();
      $programTable = view('kelas.partials.program-table', compact('programList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $programTable,
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah program kelas'])->render();
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

  public function update(Request $request, $programId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $validator = Validator::make($request->all(), [
        'nama' => 'required|string',
        'kode' => 'required|string|unique:program_kelas,kode,' . $programId,
      ], [
        'nama.required' => 'Nama program kelas tidak boleh kosong',
        'nama.string' => 'Nama program kelas tidak valid',
        'kode.required' => 'Kode program kelas tidak boleh kosong',
        'kode.string' => 'Kode program kelas tidak valid',
        'kode.unique' => 'Kode program kelas sudah digunakan',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $program = ProgramKelas::findOrFail($programId);
      $program->update([
        'nama' => $request->nama,
        'kode' => $request->kode,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Program $program->nama ($program->kode) berhasil diupdate"])->render();
      $programItem = view('kelas.partials.program-item', ['program' => $program])->render();
      return response()->json([
        'notification' => $notification,
        'programItem' => $programItem,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Program tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah program'])->render();
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

  public function destroy($programId, Request $request)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);

      $program = ProgramKelas::findOrFail($programId);
      if ($request->input('force')) {
        $program->forceDelete();
      } else {
        $program->delete();
      }

      $programList = ProgramKelas::paginate(self::$pageCount);
      $programList->setPath(route('program-kelas.index'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Program $program->nama ($program->kode) berhasil dihapus"])->render();
      $programTable = view('kelas.partials.program-table', compact('programList'))->render();
      return response()->json([
        'notification' => $notification,
        'table' => $programTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Program tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus program]'])->render();
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
      $programList = ProgramKelas::onlyTrashed()->paginate(self::$pageCount);
      $programTable = view('kelas.partials.program-table', ['programList' => $programList, 'deleted' => true])->render();
      return response()->json([
        'table' => $programTable,
      ], 200);
    }
    Gate::authorize('kelolaKelas', Kelas::class);
    $programList = ProgramKelas::onlyTrashed()->paginate(self::$pageCount);
    return view('kelas.program.deleted-program', compact('programList'));
  }

  public function restore($programId)
  {
    try {
      Gate::authorize('kelolaKelas', Kelas::class);
      $program = ProgramKelas::onlyTrashed()->where('id', $programId)->firstOrFail();
      $program->restore();

      $programList = ProgramKelas::onlyTrashed()->paginate(self::$pageCount);
      $programList->setPath(route('program-kelas.deleted'));
      $notification = view('components.notification', ['type' => 'success', 'message' => "Program $program->nama ($program->kode) berhasil direstore"])->render();
      $programTable = view('kelas.partials.program-table', ['programList' => $programList, 'deleted' => true])->render();
      return response()->json([
        'notification' => $notification,
        'table' => $programTable,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Program tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk merestore program]'])->render();
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
