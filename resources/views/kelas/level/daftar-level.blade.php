@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Level Kelas</x-slot>
  <x-slot name="header">Level Kelas</x-slot>

  <section>
    <div class="flex flex-col">
      <button id="tambah-level" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Level</button>
      <div class="level-container paginated-table">
        @include('kelas.partials.level-table', ['levelList' => $levelList])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-level-modal" route="{{ route('level-kelas.store') }}">
      <x-slot name="title">Tambah Level</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Level</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Level" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Level</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Level" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-level-modal">
      <x-slot name="title">Edit Level</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Level</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Level" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Level</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Level" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="hapus-level-modal">
      <x-slot name="title">Hapus Level</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin menghapus level <span class="nama-kode-level font-semibold">Level</span> ?</p>
        <ul class="list-inside list-disc">
          <li>Data legacy dari level ini tetap bisa diakses</li>
          <li>Kode level yang sama tidak bisa digunakan untuk level lain</li>
        </ul>
        <div class="alert alert-red flex flex-col gap-2">
          <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
          <p>Hapus permanen akan menghapus level dari database dan semua data kelas yang terasosiasi dengan level ini!</p>
        </div>
        <x-checkbox color="red" value="1" name="force" class="self-center">Hapus Permanen</x-checkbox>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/level/daftar-level.js') }}"></script>
  @endPushOnce
</x-app-layout>
