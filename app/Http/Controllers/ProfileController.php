<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Illuminate\Log\log;

class ProfileController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    return view('akun.kelola-akun', compact('user'));
  }

  public function updateProfilePicture(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'profile-picture' => 'required|image|max:10240',
      ], [
        'profile-picture.image' => 'Foto profil tidak valid',
        'profile-picture.max' => 'Ukuran foto profil maksimal 10MB',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $directory = 'images';
      $filename = $request->nik . '.' . $request->file('profile-picture')->extension();
      $profilePicture = $request->file('profile-picture')->storeAs($directory, $filename, 'public');

      $user->update([
        'profile_picture' => $profilePicture,
      ]);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Berhasil mengubah foto profil",
      ]);

      return response()->json([
        'redirect' => route('akun.index'),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function updateNama(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'nickname' => 'required|string',
      ], [
        'name.required' => 'Nama tidak boleh kosong',
        'name.string' => 'Nama tidak valid',
        'nickname.required' => 'Nama panggilan tidak boleh kosong',
        'nickname.string' => 'Nama panggilan tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $user->update([
        'name' => $request->name,
        'nickname' => $request->nickname,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Berhasil mengubah nama"])->render();
      $dataAkun = view('akun.partials.data-akun', ['user' => $user])->render();
      return response()->json([
        'notification' => $notification,
        'dataAkun' => $dataAkun,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function updateNIK(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'nik' => 'required|numeric|unique:users,nik,' . $user->id,
      ], [
        'nik.required' => 'NIK tidak boleh kosong',
        'nik.numeric' => 'NIK harus tidak valid',
        'nik.unique' => 'NIK sudah terdaftar',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $user->update([
        'nik' => $request->nik,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Berhasil mengubah NIK / NIP"])->render();
      $dataAkun = view('akun.partials.data-akun', ['user' => $user])->render();
      return response()->json([
        'notification' => $notification,
        'dataAkun' => $dataAkun,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function updateNoHP(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'phone' => 'nullable|numeric',
      ], [
        'phone.numeric' => 'Nomor telepon harus berupa angka',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $user->update([
        'phone_number' => $request->phone,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Berhasil mengubah no. hp"])->render();
      $dataAkun = view('akun.partials.data-akun', ['user' => $user])->render();
      return response()->json([
        'notification' => $notification,
        'dataAkun' => $dataAkun,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function updateEmail(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email,' . $user->id,
      ], [
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $user->update([
        'email' => $request->email,
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Berhasil mengubah email"])->render();
      $dataAkun = view('akun.partials.data-akun', ['user' => $user])->render();
      return response()->json([
        'notification' => $notification,
        'dataAkun' => $dataAkun,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }

  public function updatePassword(Request $request)
  {
    try {
      $user = Auth::user();

      $validator = Validator::make($request->all(), [
        'password' => 'required|current_password',
        'new-password' => 'required|string|min:8',
        'confirm-new-password' => 'required|same:new-password',
      ], [
        'password.required' => 'Password tidak boleh kosong',
        'password.current_password' => 'Password salah',
        'new-password.required' => 'Password baru tidak boleh kosong',
        'new-password.string' => 'Password baru tidak valid',
        'new-password.min' => 'Password baru minimal 8 karakter',
        'confirm-new-password.required' => 'Konfirmasi password baru tidak boleh kosong',
        'confirm-new-password.same' => 'Konfirmasi password baru tidak sama dengan password',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      $user->update([
        'password' => bcrypt($request['new-password']),
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Berhasil mengubah password"])->render();
      return response()->json([
        'notification' => $notification,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (Exception $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Terjadi kesalahan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 500);
    }
  }
}
