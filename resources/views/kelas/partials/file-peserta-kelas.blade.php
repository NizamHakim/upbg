@props(['optional' => false])

<div class="flex min-w-0 flex-col gap-2">
  @if ($optional)
    <p class="mb-2 font-medium text-gray-700">Peserta (opsional)</p>
  @endif
  <div class="mb-2 flex flex-row items-center gap-6">
    <label id="excel-csv-label" for="excel-csv" class="btn btn-white cursor-pointer text-nowrap"><i class="fa-solid fa-file mr-2"></i>Pilih file</label>
    <input id="excel-csv" name="input-excel-csv" type="file" class="hidden" accept=".xlsx,.csv">
    <div class="panduan-penggunaan flex cursor-pointer flex-row items-center gap-2 text-upbg transition hover:text-upbg-light">
      <i class="fa-solid fa-circle-info text-base"></i>
      <span class="text-xs">Panduan penggunaan</span>
    </div>
  </div>
  <div class="peserta-container flex min-w-0 flex-col gap-3">
    <div class="peserta-item-placeholder dotted-placeholder-div">
      Tambah peserta
    </div>
    <template id="template-peserta">
      <div class="peserta-item input-group">
        <div class="flex min-w-0 rounded-md border p-2 shadow-sm">
          <div class="flex min-w-0 flex-col gap-1">
            <p class="nama truncate font-semibold">Nama</p>
            <p class="nik truncate">NIK</p>
            <p class="occupation truncate">Occupation</p>
          </div>
          <x-menu>
            <button type="button" class="edit-peserta menu-item">Edit</button>
            <button type="button" class="delete-peserta menu-item font-medium text-red-600">Delete</button>
          </x-menu>
        </div>
        <input type="hidden" name="nik" value="">
        <input type="hidden" name="nama" value="">
        <input type="hidden" name="occupation" value="">
      </div>
    </template>
  </div>
  <div class="mt-1 flex w-full flex-row items-center justify-center gap-4">
    <button type="button" class="tambah-peserta btn-rounded btn-white hover:bg-gray-200"><i class="fa-solid fa-plus text-lg"></i></button>
  </div>
</div>

@pushOnce('modals')
  <x-modal id="panduan-penggunaan-modal">
    <x-slot name="title">Panduan Penggunaan</x-slot>
    <div class="flex flex-col gap-4">
      <ul class="list-outside list-disc pl-4">
        <li>Dapat menggunakan file dengan extension .xlsx atau .csv</li>
        <li>File harus memiliki header dengan nama <span class="font-semibold text-upbg">NIK/NRP</span>, <span class="font-semibold text-upbg">Nama</span>, dan <span class="font-semibold text-upbg">Dept./Occupation</span></li>
      </ul>

      <div class="grid grid-cols-1 divide-y border">
        <div class="grid grid-cols-3 divide-x">
          <div class="truncate px-2 py-3 font-bold">NIK/NRP</div>
          <div class="truncate px-2 py-3 font-bold">Nama</div>
          <div class="truncate px-2 py-3 font-bold">Dept./Occupation</div>
        </div>

        <div class="grid grid-cols-3 divide-x">
          <div class="truncate px-2 py-3">5822305823</div>
          <div class="truncate px-2 py-3">Kevin</div>
          <div class="truncate px-2 py-3">Teknik Biomedis</div>
        </div>

        <div class="grid grid-cols-3 divide-x">
          <div class="truncate px-2 py-3">2182943812</div>
          <div class="truncate px-2 py-3">Steven</div>
          <div class="truncate px-2 py-3">Desain Komunikasi Visual</div>
        </div>

        <div class="grid grid-cols-3 divide-x">
          <div class="truncate px-2 py-3">2394350238</div>
          <div class="truncate px-2 py-3">Jane</div>
          <div class="truncate px-2 py-3">Teknik Industri</div>
        </div>

        <div class="grid grid-cols-3 divide-x">
          <div class="truncate px-2 py-3">1282359452</div>
          <div class="truncate px-2 py-3">John</div>
          <div class="truncate px-2 py-3">Teknik Perkapalan</div>
        </div>
      </div>
      <x-slot name="control">
        <a href="{{ asset('files/template-input-peserta.xlsx') }}" class="btn btn-white"><i class="fa-solid fa-file-arrow-down mr-2"></i>Download Template</a>
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Tutup</button>
      </x-slot>
    </div>
  </x-modal>

  <x-modal id="add-peserta-modal">
    <x-slot name="title">Tambah Peserta</x-slot>
    <div class="flex flex-col gap-4">
      <div class="input-group">
        <p class="input-label">NIK / NRP</p>
        <input type="text" name="nik" placeholder="NIK / NRP" class="input-appearance">
      </div>
      <div class="input-group">
        <p class="input-label">Nama</p>
        <input type="text" name="nama" placeholder="Nama" class="input-appearance">
      </div>
      <div class="input-group">
        <p class="input-label">Departemen / Occupation</p>
        <input type="text" name="occupation" placeholder="Departemen / Occupation" class="input-appearance">
      </div>
    </div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Batal</button>
      <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
    </x-slot>
  </x-modal>

  <x-modal id="edit-peserta-modal">
    <x-slot name="title">Edit Peserta</x-slot>
    <div class="flex flex-col gap-4">
      <div class="input-group">
        <p class="input-label">NIK / NRP</p>
        <input type="text" name="nik" placeholder="NIK / NRP" class="input-appearance">
      </div>
      <div class="input-group">
        <p class="input-label">Nama</p>
        <input type="text" name="nama" placeholder="Nama" class="input-appearance">
      </div>
      <div class="input-group">
        <p class="input-label">Departemen / Occupation</p>
        <input type="text" name="occupation" placeholder="Departemen / Occupation" class="input-appearance">
      </div>
    </div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Batal</button>
      <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
    </x-slot>
  </x-modal>
@endPushOnce

@pushOnce('scripts')
  <script src="{{ asset('js/kelas/partials/file-peserta-kelas.js') }}"></script>
@endPushOnce
