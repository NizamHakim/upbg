@props(['hariOptions', 'kelas' => null])

<div id="jadwal-kelas-dynamic" class="flex min-w-0 flex-col gap-2">
  <p class="input-label">Jadwal</p>
  @if ($kelas)
    <p class="alert alert-upbg">Jadwal pertemuan yang sudah dibuat tidak akan otomatis diubah</p>
  @endif

  <div class="jadwal-kelas-container flex min-w-0 flex-col gap-3">
    @if ($kelas)
      @foreach ($kelas->jadwal as $jadwal)
        <div class="jadwal-kelas-item input-group">
          <div class="flex items-center rounded border px-2 py-1">
            <p class="truncate"><span class="hari">{{ $jadwal->textFormat('nama-hari') }}</span>, <span class="waktu-mulai">{{ $jadwal->textFormat('iso-waktu-mulai') }}</span> - <span class="waktu-selesai">{{ $jadwal->textFormat('iso-waktu-selesai') }}</span></p>
            <input type="hidden" name="hari" value="{{ $jadwal->hari }}">
            <input type="hidden" name="waktu-mulai" value="{{ $jadwal->textFormat('iso-waktu-mulai') }}">
            <input type="hidden" name="waktu-selesai" value="{{ $jadwal->textFormat('iso-waktu-selesai') }}">
            <x-menu>
              <button type="button" class="menu-item edit-jadwal">Edit</button>
              @if (!$loop->first)
                <button type="button" class="menu-item delete-jadwal font-medium text-red-600">Hapus</button>
              @endif
            </x-menu>
          </div>
        </div>
      @endforeach
    @else
      <div class="jadwal-kelas-item input-group">
        <div class="flex items-center rounded border px-2 py-1 shadow-sm">
          <p class="truncate"><span class="hari">Hari</span>, <span class="waktu-mulai">Waktu Mulai</span> - <span class="waktu-selesai">Waktu Selesai</span></p>
          <input type="hidden" name="hari" value="">
          <input type="hidden" name="waktu-mulai" value="">
          <input type="hidden" name="waktu-selesai" value="">
          <x-menu>
            <button type="button" class="menu-item edit-jadwal">Edit</button>
          </x-menu>
        </div>
      </div>
    @endif
  </div>
  <div class="mt-1 flex w-full flex-row items-center justify-center gap-4">
    <button type="button" class="tambah-jadwal btn-rounded btn-white hover:bg-gray-200"><i class="fa-solid fa-plus text-lg"></i></button>
  </div>
</div>

<template id="template-jadwal">
  <div class="jadwal-kelas-item input-group">
    <div class="flex items-center rounded border px-2 py-1">
      <p class="truncate"><span class="hari"></span>, <span class="waktu-mulai"></span> - <span class="waktu-selesai"></span></p>
      <input type="hidden" name="hari" value="">
      <input type="hidden" name="waktu-mulai" value="">
      <input type="hidden" name="waktu-selesai" value="">
      <x-menu>
        <button type="button" class="menu-item edit-jadwal">Edit</button>
        <button type="button" class="menu-item delete-jadwal font-medium text-red-600">Hapus</button>
      </x-menu>
    </div>
  </div>
</template>

@pushOnce('modals')
  <x-modal id="add-jadwal-modal">
    <x-slot name="title">Tambah Jadwal</x-slot>
    <div class="flex flex-col gap-4">
      <div class="input-group">
        <p class="input-label">Hari</p>
        <select name="hari" class="tom-select">
          <option value="">Hari</option>
          @foreach ($hariOptions as $hari)
            <option value="{{ $hari->value }}">{{ $hari->text }}</option>
          @endforeach
        </select>
      </div>
      <div class="grid grid-cols-2 gap-x-4">
        <div class="input-group">
          <p class="input-label">Waktu Mulai</p>
          <input type="time" name="waktu-mulai" value="" class="fp-timepicker input-appearance" placeholder="Semua">
        </div>
        <div class="input-group">
          <p class="input-label">Waktu Selesai</p>
          <input type="time" name="waktu-selesai" value="" class="fp-timepicker input-appearance" placeholder="Semua">
        </div>
      </div>
    </div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
      <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
    </x-slot>
  </x-modal>

  <x-modal id="edit-jadwal-modal">
    <x-slot name="title">Edit Jadwal</x-slot>
    <div class="flex flex-col gap-4">
      <div class="input-group">
        <p class="input-label">Hari</p>
        <select name="hari" class="tom-select">
          <option value="">Pilih hari</option>
          @foreach ($hariOptions as $hari)
            <option value="{{ $hari->value }}">{{ $hari->text }}</option>
          @endforeach
        </select>
      </div>
      <div class="grid grid-cols-2 gap-x-4">
        <div class="input-group">
          <p class="input-label">Waktu Mulai</p>
          <input type="time" name="waktu-mulai" value="" class="fp-timepicker input-appearance" placeholder="Waktu Mulai">
        </div>
        <div class="input-group">
          <p class="input-label">Waktu Selesai</p>
          <input type="time" name="waktu-selesai" value="" class="fp-timepicker input-appearance" placeholder="Waktu Selesai">
        </div>
      </div>
    </div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
      <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
    </x-slot>
  </x-modal>
@endPushOnce

@pushOnce('scripts')
  <script src="{{ asset('js/kelas/partials/jadwal-kelas-dynamic.js') }}"></script>
@endPushOnce
