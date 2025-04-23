@props(['tes'])

@if ($tes->pivotPeserta->isEmpty())
  <div class="p-4">
    <p class="empty-query">Tidak ada data yang cocok</p>
  </div>
@else
  @foreach ($tes->pivotPeserta as $presensi)
    <div class="presensi-item grid grid-cols-8 items-center gap-x-3 py-5">
      <p class="col-span-1 pl-2 text-center font-medium">{{ $loop->iteration }}.</p>
      <div class="col-span-4">
        <p class="font-medium text-gray-700">{{ $presensi->peserta->nama }}</p>
        <p>{{ $presensi->peserta->nik }}</p>
      </div>
      <form action="{{ route('tes.presensi', ['tesId' => $tes->id, 'presensiId' => $presensi->id]) }}" class="form-toggle-kehadiran col-span-3 pr-2">
        <x-btn-presensi :presensi="$presensi" />
      </form>
    </div>
  @endforeach
@endif
