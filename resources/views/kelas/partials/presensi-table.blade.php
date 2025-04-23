@use('App\Models\Kelas', 'Kelas')
@props(['kelas', 'pertemuan'])

<div class="presensi-table table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="hidden sm:col-span-1 sm:block sm:text-center">No.</p>
    <p class="col-span-6">Peserta</p>
    <p class="col-span-4">Status Kehadiran</p>
  </div>
  @if ($pertemuan->presensi->isEmpty())
    <div class="empty-query p-4">
      Tidak ada data yang cocok
    </div>
  @else
    @foreach ($pertemuan->presensi as $presensi)
      <div class="presensi-item table-item grid-cols-12" data-delete-route="{{ route('presensi.destroy', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id, 'presensiId' => $presensi->id]) }}">
        <p class="hidden sm:col-span-1 sm:block sm:text-center sm:font-medium">{{ $loop->iteration }}.</p>
        <div class="col-span-6">
          <p class="nama-peserta font-medium text-gray-700">{{ $presensi->peserta->nama }}</p>
          <p class="nik-peserta text-gray-600">{{ $presensi->peserta->nik }}</p>
        </div>
        <form action="{{ route('presensi.toggle', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id, 'presensiId' => $presensi->id]) }}" class="form-toggle-kehadiran col-span-4 whitespace-nowrap">
          <x-btn-presensi :presensi="$presensi" />
        </form>
        <div class="col-span-2 flex sm:col-span-1">
          @can('hapusPresensiKelas', $kelas)
            <x-menu>
              <button type="button" class="menu-item delete-presensi font-medium text-red-600">Hapus</button>
            </x-menu>
          @endcan
        </div>
      </div>
    @endforeach
  @endif
</div>

@pushOnce('modals')
  <x-modal id="delete-presensi-modal" route="">
    <x-slot name="title">Hapus Presensi</x-slot>
    <div class="flex flex-col gap-4">
      <p>Apakah anda yakin ingin menghapus <span class="nama-nik-peserta font-bold">Peserta</span> dari pertemuan ini?</p>
      <div class="alert alert-red flex flex-col gap-2">
        <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
        <p>Data peserta pada pertemuan ini akan dihapus permanen</p>
      </div>
    </div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
      <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
    </x-slot>
  </x-modal>
@endPushOnce
