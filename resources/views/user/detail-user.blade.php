@use('App\Models\User', 'User')

<x-app-layout>
  <x-slot name="title">{{ $user->name }}</x-slot>
  <x-slot name="header"><a href="{{ route('user.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Detail User</x-slot>

  <section class="relative">
    <div class="flex flex-col gap-8 md:flex-row md:gap-20">
      <img src="{{ $user->profile_picture }}" class="size-44 self-center rounded shadow-sm md:size-52 md:self-start">
      <div class="flex w-full flex-col">
        <div class="grid grid-cols-1 gap-y-3 md:grid-cols-2">
          <div class="flex flex-col gap-1 md:col-span-1 md:col-start-1">
            <p class="font-medium">NIK / NIP</p>
            <p class="text-wrap">{{ $user->nik }}</p>
          </div>
          <div class="flex flex-col gap-1 md:row-span-1 md:row-start-2">
            <p class="font-medium">Nama Lengkap</p>
            <p class="text-wrap">{{ $user->name }}</p>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-medium">No. HP</p>
            <a href="{{ 'http://wa.me/62' . substr($user->phone_number, 1) }}" target="_blank" class="link">{{ $user->phone_number }}</a>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-medium">Email</p>
            <a href="mailto:{{ $user->email }}" class="link">{{ $user->email }}</a>
          </div>
        </div>
        <hr class="my-5 w-full">
        <form id="update-role-form" action="{{ route('user.update', ['userId' => $user->id]) }}">
          <div class="flex flex-col">
            <p class="input-label">Role</p>
            <div class="role-container flex flex-col flex-wrap gap-3 sm:flex-row" data-roles="{{ $user->roles->pluck('id') }}">
              @can('kelolaUserSuperuser', User::class)
                <x-checkbox color="upbg" value="1" name="role" checked="{{ $user->roles->contains(1) }}">Superuser</x-checkbox>
              @endcan
              @can('kelolaUserPengajaran', User::class)
                <x-checkbox color="upbg" value="2" name="role" checked="{{ $user->roles->contains(2) }}">Admin Pengajaran</x-checkbox>
                <x-checkbox color="upbg" value="3" name="role" checked="{{ $user->roles->contains(3) }}">Staf Pengajar</x-checkbox>
              @endcan
              @can('kelolaUserTes', User::class)
                <x-checkbox color="upbg" value="4" name="role" checked="{{ $user->roles->contains(4) }}">Admin Tes</x-checkbox>
                <x-checkbox color="upbg" value="5" name="role" checked="{{ $user->roles->contains(5) }}">Staf Tes</x-checkbox>
              @endcan
              @can('kelolaUserKeuangan', User::class)
                <x-checkbox color="upbg" value="6" name="role" checked="{{ $user->roles->contains(6) }}">Bagian Keuangan</x-checkbox>
              @endcan
            </div>
            <div class="control mt-4 hidden flex-col gap-4">
              @if (Auth::user()->id == $user->id)
                <div class="alert alert-red flex flex-col gap-2">
                  <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
                  <p>Mengubah role pada akun anda sendiri mungkin mencabut akses anda untuk menambahkannya kembali</p>
                </div>
              @endif
              <div class="flex justify-end gap-4">
                <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    @canany(['hapusUser', 'resetPassword'], User::class)
      <div class="absolute right-4 top-4">
        <x-menu triggerSize="medium">
          @can('resetPassword', User::class)
            <button type="button" class="menu-item reset-password">Reset Password</button>
            @pushOnce('modals')
              <x-modal id="reset-password-modal" route="{{ route('user.reset-password', ['userId' => $user->id]) }}">
                <x-slot name="title">Reset Password</x-slot>
                <div class="flex flex-col gap-4">
                  <p>Apakah anda yakin ingin mereset password user <span class="font-semibold">{{ "$user->name ($user->nik)" }}</span> ?</p>
                  <div class="alert alert-red flex flex-col gap-2">
                    <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
                    <p>Reset password akan mengganti password user menjadi <span class="font-semibold">NIK / NIP user</span>!</p>
                  </div>
                </div>
                <x-slot name="control">
                  <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                  <button type="submit" class="submit-button btn btn-red-solid">Reset Password</button>
                </x-slot>
              </x-modal>
            @endPushOnce
          @endcan

          @can('hapusUser', User::class)
            <button type="button" class="menu-item hapus-user font-medium text-red-600">Hapus</button>
            @pushOnce('modals')
              <x-modal id="hapus-user-modal" route="{{ route('user.destroy', ['userId' => $user->id]) }}">
                <x-slot name="title">Hapus User</x-slot>
                <div class="flex flex-col gap-4">
                  <p>Apakah anda yakin ingin menghapus permanen user <span class="font-semibold">{{ "$user->name ($user->nik)" }}</span> ?</p>
                  <div class="alert alert-red flex flex-col gap-2">
                    <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
                    <p>Hapus permanen akan menghapus user dari database dan semua data kelas dan tes yang terasosiasi dengan user ini!</p>
                    <p>Jika hanya ingin mencabut semua hak akses disarankan mencabut semua role daripada menghapus user.</p>
                  </div>
                </div>
                <x-slot name="control">
                  <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                  <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
                </x-slot>
              </x-modal>
            @endPushOnce
          @endcan
        </x-menu>
      </div>
    @endcanany
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/user/detail-user.js') }}"></script>
  @endPushOnce
</x-app-layout>
