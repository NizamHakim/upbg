<x-app-layout>
  <x-slot name="title">Kelola Akun</x-slot>
  <x-slot name="header">Kelola Akun</x-slot>

  <section>
    <div class="flex flex-col gap-8 md:flex-row md:gap-14">
      <form id="profile-picture" class="relative size-44 self-center rounded shadow-sm md:size-52 md:self-start">
        @include('akun.partials.profile-picture', ['user' => $user])
      </form>
      <div id="data-akun" class="flex flex-1 flex-col">
        @include('akun.partials.data-akun', ['user' => $user])
      </div>
    </div>
  </section>

  <div class="my-4 rounded bg-white shadow-sm">
    <div id="edit-nik" class="cursor-pointer rounded-t-md px-6 py-4 transition hover:bg-gray-200">
      <p class="font-semibold">NIK / NIP</p>
      <p class="text-xs font-medium text-gray-500">Edit NIK / NIP</p>
    </div>
    <div id="edit-nama" class="cursor-pointer rounded-b-md px-6 py-4 transition hover:bg-gray-200">
      <p class="font-semibold">Nama</p>
      <p class="text-xs font-medium text-gray-500">Edit nama lengkap dan nama panggilan</p>
    </div>
  </div>

  <div class="rounded bg-white shadow-sm">
    <div id="edit-phone" class="cursor-pointer rounded-t-md px-6 py-4 transition hover:bg-gray-200">
      <p class="font-semibold">No. HP</p>
      <p class="text-xs font-medium text-gray-500">Edit nomor hp</p>
    </div>
    <div id="edit-email" class="cursor-pointer rounded-b-md px-6 py-4 transition hover:bg-gray-200">
      <p class="font-semibold">Email</p>
      <p class="text-xs font-medium text-gray-500">Edit email untuk kredensial login</p>
    </div>
  </div>

  <div class="mt-4 rounded bg-white shadow-sm">
    <div id="edit-password" class="cursor-pointer rounded-md px-6 py-4 transition hover:bg-gray-200">
      <p class="font-semibold">Password</p>
      <p class="text-xs font-medium text-gray-500">Edit password untuk kredensial login</p>
    </div>
  </div>

  @pushOnce('modals')
    <x-modal id="cropper-modal">
      <x-slot name="title">Crop Foto</x-slot>
      <div class="flex flex-col gap-4">
        <img id="modal-preview" src="" alt="Cropper Preview" class="max-h-96">
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Crop</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-profile-picture-modal" route="{{ route('akun.update-profile-picture') }}">
      <x-slot name="title">Edit Foto Profil</x-slot>
      <div class="input-group flex flex-col items-center">
        <img src="" alt="Profile Preview" class="profile-preview size-64">
        <input type="file" name="profile-picture" accept="image/*" class="hidden">
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-nik-modal" route="{{ route('akun.update-nik') }}">
      <x-slot name="title">Edit NIK / NIP</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">NIK / NIP</p>
          <input type="number" class="input-appearance" name="nik" placeholder="NIK / NIP" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-nama-modal" route="{{ route('akun.update-nama') }}">
      <x-slot name="title">Edit Nama</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Lengkap</p>
          <input type="text" class="input-appearance" name="name" placeholder="Nama Lengkap" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Nama Panggilan</p>
          <input type="text" class="input-appearance" name="nickname" placeholder="Nama Panggilan" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-phone-modal" route="{{ route('akun.update-no-hp') }}">
      <x-slot name="title">Edit No. HP</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">No. HP</p>
          <input type="text" class="input-appearance" name="phone" placeholder="No. HP" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-email-modal" route="{{ route('akun.update-email') }}">
      <x-slot name="title">Edit Email</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Email</p>
          <input type="text" class="input-appearance" name="email" placeholder="Email" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-password-modal" route="{{ route('akun.update-password') }}">
      <x-slot name="title">Edit Password</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Password</p>
          <input type="password" class="input-appearance" name="password" placeholder="Password" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Password Baru</p>
          <input type="password" class="input-appearance" name="new-password" placeholder="Password baru" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Konfirmasi Password Baru</p>
          <input type="password" class="input-appearance" name="confirm-new-password" placeholder="Konfirmasi password baru" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/akun/kelola-akun.js') }}"></script>
  @endPushOnce
</x-app-layout>
