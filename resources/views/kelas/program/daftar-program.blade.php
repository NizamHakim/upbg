@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Program Kelas</x-slot>
  <x-slot name="header">Program Kelas</x-slot>

  <section>
    <div class="flex flex-col">
      <button id="tambah-program" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Program</button>
      <div class="program-container paginated-table">
        @include('kelas.partials.program-table', ['programList' => $programList])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-program-modal" route="{{ route('program-kelas.store') }}">
      <x-slot name="title">Tambah Program</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Program</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Program" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Program</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Program" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>

    <x-modal id="edit-program-modal">
      <x-slot name="title">Edit Program</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Nama Program</p>
          <input type="text" class="input-appearance" name="nama" placeholder="Nama Program" value="">
        </div>
        <div class="input-group">
          <p class="input-label">Kode Program</p>
          <input type="text" class="input-appearance" name="kode" placeholder="Kode Program" value="">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>

    <x-modal id="hapus-program-modal">
      <x-slot name="title">Hapus Program</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin menghapus program <span class="nama-kode-program font-semibold">Program</span> ?</p>
        <ul class="list-inside list-disc">
          <li>Data legacy dari program ini tetap bisa diakses</li>
          <li>Kode program yang sama tidak bisa digunakan untuk program lain</li>
        </ul>
        <div class="alert alert-red flex flex-col gap-2">
          <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
          <p>Hapus permanen akan menghapus program dari database dan semua data kelas yang terasosiasi dengan program ini!</p>
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
    <script src="{{ asset('js/kelas/program/daftar-program.js') }}"></script>
  @endPushOnce
</x-app-layout>
