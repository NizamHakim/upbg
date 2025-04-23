@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Tipe Kelas</x-slot>
  <x-slot name="header">Tipe Kelas</x-slot>

  <section>
    <div class="flex flex-col">
      <button id="tambah-tipe" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Tipe</button>
      <div class="tipe-container paginated-table">
        @include('kelas.partials.tipe-table', ['tipeList' => $tipeList])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-tipe-modal" route="{{ route('tipe-kelas.store') }}">
      <x-slot name="title">Tambah Tipe</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Tipe</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Tipe" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Tipe</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Tipe" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kategori</p>
          <select name="kategori" class="tom-select">
            <option value="">Pilih Kategori</option>
            @foreach ($kategoriList as $kategori)
              <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-tipe-modal">
      <x-slot name="title">Edit Tipe</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Tipe</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Tipe" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Tipe</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Tipe" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kategori</p>
          <select name="kategori" class="tom-select">
            <option value="">Pilih Kategori</option>
            @foreach ($kategoriList as $kategori)
              <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="hapus-tipe-modal">
      <x-slot name="title">Hapus Tipe</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin menghapus tipe <span class="nama-kode-tipe font-semibold">Tipe</span> ?</p>
        <ul class="list-inside list-disc">
          <li>Data legacy dari tipe ini tetap bisa diakses</li>
          <li>Kode tipe yang sama tidak bisa digunakan untuk tipe lain</li>
        </ul>
        <div class="alert alert-red flex flex-col gap-2">
          <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
          <p>Hapus permanen akan menghapus tipe dari database dan semua data kelas yang terasosiasi dengan tipe ini!</p>
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
    <script src="{{ asset('js/kelas/tipe/daftar-tipe.js') }}"></script>
  @endPushOnce
</x-app-layout>
