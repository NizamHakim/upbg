<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  private static $pageCount = 20;

  public function index(Request $request)
  {
    Gate::authorize('lihatUser', User::class);
    $roleOptions = Role::all();
    $userList = User::query();

    $userList->when($request->query('role'), function ($query) use ($request) {
      return $query->whereHas('roles', function ($query) use ($request) {
        return $query->where('role_id', $request->query('role'));
      });
    });

    $userList->when($request->query('search'), function ($query) use ($request) {
      return $query->where('name', 'like', '%' . $request->query('search') . '%')->orWhere('nik', 'like', '%' . $request->query('search') . '%');
    });

    $userList = $userList->paginate(self::$pageCount)->withQueryString();
    $request->flash();

    return view('user.daftar-user', compact('userList', 'roleOptions'));
  }

  public function create()
  {
    if (Gate::allows('kelolaUserSuperuser', User::class)) {
      $roleList = Role::all();
    } elseif (Gate::allows('kelolaUserPengajaran', User::class)) {
      $roleList = Role::whereIn('id', [2, 3])->get();
    } elseif (Gate::allows('kelolaUserTes', User::class)) {
      $roleList = Role::whereIn('id', [4, 5])->get();
    } elseif (Gate::allows('kelolaUserKeuangan', User::class)) {
      $roleList = Role::whereIn('id', [6])->get();
    } else {
      abort(403);
    }

    return view('user.tambah-user', compact('roleList'));
  }

  public function store(Request $request)
  {
    try {
      if (Gate::none(['kelolaUserPengajaran', 'kelolaUserTes', 'kelolaUserKeuangan', 'kelolaUserSuperuser'], User::class)) {
        throw new AuthorizationException();
      }

      $validator = Validator::make($request->all(), [
        'nik' => 'required|numeric|unique:users,nik',
        'name' => 'required|string',
        'nickname' => 'required|string',
        'phone' => 'nullable|numeric',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'confirm-password' => 'required|same:password',
        'role' => 'nullable|array',
        'role.*' => 'exists:roles,id',
        'profile-picture' => 'nullable|image',
      ], [
        'nik.required' => 'NIK tidak boleh kosong',
        'nik.numeric' => 'NIK harus berupa angka',
        'nik.unique' => 'NIK sudah terdaftar',
        'name.required' => 'Nama tidak boleh kosong',
        'name.string' => 'Nama tidak valid',
        'nickname.required' => 'Nama panggilan tidak boleh kosong',
        'nickname.string' => 'Nama panggilan tidak valid',
        'phone.numeric' => 'Nomor telepon harus berupa angka',
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'password.required' => 'Password tidak boleh kosong',
        'password.string' => 'Password tidak valid',
        'password.min' => 'Password minimal 8 karakter',
        'confirm-password.required' => 'Konfirmasi password tidak boleh kosong',
        'confirm-password.same' => 'Konfirmasi password tidak sama dengan password',
        'role.*.exists' => 'Role tidak valid',
        'profile-picture.image' => 'Foto profil tidak valid',
      ]);

      if ($validator->fails()) {
        return response($validator->errors(), 422);
      }

      if ($request->hasFile('profile-picture')) {
        $directory = 'images';
        $filename = $request->nik . '.' . $request->file('profile-picture')->extension();
        $profilePicture = $request->file('profile-picture')->storeAs($directory, $filename, 'public');
      } else {
        $profilePicture = null;
      }

      $user = User::create([
        'nik' => $request->nik,
        'name' => $request->name,
        'nickname' => $request->nickname,
        'phone_number' => $request->phone,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'profile_picture' => $profilePicture,
      ]);

      $user->roles()->syncWithoutDetaching($request->role);
      $user->update(['current_role_id' => $request->role[0]]);

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "User $user->name ($user->nik) berhasil ditambahkan",
      ]);

      return response()->json([
        'redirect' => route('user.index'),
      ], 200);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menambah user'])->render();
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

  public function detail($userId, Request $request)
  {
    Gate::authorize('lihatUser', User::class);
    $user = User::with('roles')->findOrFail($userId);
    return view('user.detail-user', compact('user'));
  }

  public function update($userId, Request $request)
  {
    try {
      $user = User::findOrFail($userId);
      $validator = Validator::make($request->all(), [
        'role' => 'nullable|array',
        'role.*' => 'exists:roles,id',
      ]);

      if ($validator->fails()) {
        abort(500);
      }

      if (Gate::allows('kelolaUserSuperuser', User::class)) {
        $user->roles()->sync($request->role);
      } elseif (Gate::allows('kelolaUserPengajaran', User::class)) {
        $roles = [2, 3];
        $user->roles()->detach($roles);
        $user->roles()->attach($request->role);
      } elseif (Gate::allows('kelolaUserTes', User::class)) {
        $roles = [4, 5];
        $user->roles()->detach($roles);
        $user->roles()->attach($request->role);
      } elseif (Gate::allows('kelolaUserKeuangan', User::class)) {
        $roles = [6];
        $user->roles()->detach($roles);
        $user->roles()->attach($request->role);
      } else {
        throw new AuthorizationException();
      }

      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "Role $user->name berhasil diubah",
      ]);

      if (!$user->roles->contains($user->current_role_id)) {
        $user->update(['current_role_id' => null]);
      }

      return response()->json([
        'redirect' => route('user.detail', $userId),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah role'])->render();
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

  public function destroy($userId, Request $request)
  {
    try {
      Gate::authorize('hapusUser', User::class);
      $user = User::findOrFail($userId);
      $user->delete();
      $request->session()->flash('notification', [
        'type' => 'success',
        'message' => "User $user->name berhasil dihapus",
      ]);
      return response()->json([
        'redirect' => route('user.index'),
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus user'])->render();
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

  public function resetPassword($userId, Request $request)
  {
    try {
      Gate::authorize('resetPassword', User::class);
      $user = User::findOrFail($userId);

      $user->update([
        'password' => bcrypt($user->nik),
      ]);

      $notification = view('components.notification', ['type' => 'success', 'message' => "Password {$user->name} berhasil direset"])->render();
      return response()->json([
        'notification' => $notification,
      ], 200);
    } catch (ModelNotFoundException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'User tidak ditemukan, silahkan refresh dan coba lagi'])->render();
      return response()->json([
        'notification' => $notification,
      ], 404);
    } catch (AuthorizationException $e) {
      $notification = view('components.notification', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mereset password'])->render();
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
