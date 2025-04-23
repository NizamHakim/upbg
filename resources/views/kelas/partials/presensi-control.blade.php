@props(['kelas', 'pertemuan'])

<div class="flex flex-col items-center gap-2 md:items-start">
  <p class="text-lg text-gray-700 md:text-2xl">Kehadiran Peserta</p>
  <div class="hadir-counter">
    <x-hadir-count :hadir="$pertemuan->hadirCount" :total="$pertemuan->presensi->count()" />
  </div>
</div>
<div class="flex flex-col justify-center gap-2">
  <button type="button" class="tambah-presensi btn btn-green-solid"><i class="fa-solid fa-plus mr-2"></i>Tambah Presensi</button>
  <form action="{{ route('presensi.hadir-semua', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="tandai-semua-hadir flex w-full flex-col">
    <button type="submit" class="btn btn-green-outline"><i class="fa-regular fa-square-check mr-2"></i>Tandai Semua Hadir</button>
  </form>
</div>

@pushOnce('modals')
  <x-modal id="tambah-presensi-modal" route="{{ route('presensi.store', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}">
    <x-slot name="title">Tambah Presensi</x-slot>
    @if ($kelas->peserta->isEmpty())
      <div class="flex flex-col gap-4">
        <div class="alert alert-green">
          <p class="mb-2 font-semibold"><i class="fa-solid fa-circle-info mr-2"></i>Info</p>
          <p>Semua peserta sudah ditambahkan ke pertemuan ini</p>
        </div>
      </div>
    @else
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Peserta</p>
          <select name="peserta" class="tom-select">
            <option value="">Pilih peserta</option>
            @foreach ($kelas->peserta as $peserta)
              <option value="{{ $peserta->id }}">{{ $peserta->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Status Kehadiran</p>
          <select name="hadir" class="tom-select">
            <option value="">Pilih status kehadiran</option>
            <option value="1">Hadir</option>
            <option value="0">Tidak Hadir</option>
          </select>
        </div>
        <div class="alert alert-green">
          <p class="mb-2 font-semibold"><i class="fa-solid fa-circle-info mr-2"></i>Info</p>
          <p>Jika peserta tidak ada dalam daftar, pastikan peserta sudah terdaftar di kelas ini</p>
        </div>
      </div>
    @endif
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
      @if (!$kelas->peserta->isEmpty())
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      @endif
    </x-slot>
  </x-modal>
@endPushOnce
