<x-app-layout>
  <x-slot name="title">Tambah User</x-slot>
  <x-slot name="header"><a href="{{ route('user.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Tambah User</x-slot>

  <section>
    <form id="tambah-user-form" action="{{ route('user.store') }}">
      <div class="input-group mb-5 flex flex-col items-center">
        <div class="relative w-fit">
          <img id="profile-preview" src="{{ asset('images/picturePlaceholder.png') }}" class="size-40 rounded-full border shadow">
          <label for="photo-picker" class="btn-rounded btn-upbg-solid absolute bottom-0 right-0 flex cursor-pointer items-center justify-center"><i class="fa-solid fa-camera"></i></label>
          <input id="photo-picker" type="file" name="profile-picture" class="hidden" accept="image/*">
        </div>
      </div>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">NIK / NIP</p>
          <input type="number" name="nik" class="input-appearance" placeholder="NIK / NIP">
        </div>
        <div class="input-group">
          <p class="input-label">Nama Lengkap</p>
          <input type="text" name="name" class="input-appearance" placeholder="Nama lengkap">
        </div>
        <div class="input-group">
          <p class="input-label">Nama Panggilan</p>
          <input type="text" name="nickname" class="input-appearance" placeholder="Nama panggilan">
        </div>
        <div class="input-group">
          <p class="input-label">Nomor HP</p>
          <input type="text" name="phone" class="input-appearance" placeholder="Nomor HP">
        </div>
        <div class="input-group">
          <p class="input-label">Email</p>
          <input type="email" name="email" class="input-appearance" placeholder="Email">
        </div>
        <div class="grid grid-cols-1 gap-x-2 gap-y-4 sm:grid-cols-2">
          <div class="input-group">
            <p class="input-label">Password</p>
            <input type="password" name="password" class="input-appearance input-readonly" placeholder="Password">
          </div>
          <div class="input-group">
            <p class="input-label">Konfirmasi Password</p>
            <input type="password" name="confirm-password" class="input-appearance input-readonly" placeholder="Konfirmasi Password">
          </div>
          <x-checkbox color="upbg" name="use-nik" value="1" class="col-span-full shadow-none">Gunakan NIK sebagai password</x-checkbox>
        </div>
      </div>
      <hr class="my-5">
      <div class="input-group">
        <p class="input-label">Role</p>
        <div class="flex flex-wrap gap-2">
          @foreach ($roleList as $role)
            <x-checkbox color="upbg" name="role[]" value="{{ $role->id }}">{{ $role->name }}</x-checkbox>
          @endforeach
        </div>
      </div>
      <hr class="my-5">
      <div class="flex justify-end gap-4">
        <a href="{{ route('user.index') }}" class="cancel-button btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </div>
    </form>
  </section>

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
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/user/tambah-user.js') }}"></script>
  @endPushOnce
</x-app-layout>
