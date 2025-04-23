@use('App\Models\Ruangan', 'Ruangan')

<x-app-layout>
  <x-slot name="title">Daftar Ruangan</x-slot>
  <x-slot name="header">Daftar Ruangan</x-slot>

  <section>
    <div class="flex flex-col">
      <button id="tambah-ruangan" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Ruangan</button>
      <div class="ruangan-container paginated-table">
        @include('ruangan.partials.ruangan-table', ['ruanganList' => $ruanganList])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-ruangan-modal" route="{{ route('ruangan.store') }}">
      <x-slot name="title">Tambah Ruangan</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Kode Ruangan</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Ruangan" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kapasitas</p>
          <input type="text" class="input-appearance" name="kapasitas" placeholder="Kapasitas" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-ruangan-modal">
      <x-slot name="title">Edit Ruangan</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Kode Ruangan</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Ruangan" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kapasitas</p>
          <input type="text" class="input-appearance" name="kapasitas" placeholder="Kapasitas" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="hapus-ruangan-modal">
      <x-slot name="title">Hapus Ruangan</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin menghapus ruangan <span class="kode-ruangan font-semibold">Ruangan</span> ?</p>
        <ul class="list-inside list-disc">
          <li>Data legacy dari ruangan ini tetap bisa diakses</li>
          <li>Kode ruangan yang sama tidak bisa digunakan untuk ruangan lain</li>
        </ul>
        <div class="alert alert-red flex flex-col gap-2">
          <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
          <p>Hapus permanen akan menghapus ruangan dari database dan semua data kelas, pertemuan, dan tes yang terasosiasi dengan ruangan ini!</p>
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
    <script src="{{ asset('js/ruangan/daftar-ruangan.js') }}"></script>
  @endPushOnce
</x-app-layout>
