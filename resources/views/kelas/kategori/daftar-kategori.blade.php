@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Kategori Kelas</x-slot>
  <x-slot name="header">Kategori Kelas</x-slot>

  <section>
    <div class="flex flex-col">
      <button id="tambah-kategori" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Kategori</button>
      <div class="kategori-container paginated-table">
        @include('kelas.partials.kategori-table', ['kategoriList' => $kategoriList])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-kategori-modal" route="{{ route('kategori-kelas.store') }}">
      <x-slot name="title">Tambah Kategori</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Kategori</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Kategori" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-kategori-modal">
      <x-slot name="title">Edit Kategori</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Kategori</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Kategori" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="hapus-kategori-modal">
      <x-slot name="title">Hapus Kategori</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin <span class="font-semibold">menghapus permanen</span> kategori <span class="nama-kategori font-semibold">Kategori</span> ?</p>
        <div class="alert alert-red flex flex-col gap-2">
          <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
          <p>Menghapus kategori akan mengubah tipe yang terasosiasi menjadi tanpa kategori</p>
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/kategori/daftar-kategori.js') }}"></script>
  @endPushOnce
</x-app-layout>
