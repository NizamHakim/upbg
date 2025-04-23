@props(['today', 'jadwalToday'])

<p class="mb-4 text-center text-lg">
  {{ $today }}</p>
<div class="flex flex-col gap-2">
  @foreach ($jadwalToday as $key => $array)
    @if ($key == 'kelas')
      @foreach ($array as $pertemuan)
        <div class="jadwal-item kelas gap-1 bg-upbg-light bg-opacity-20" data-route="{{ route('jadwal.kelas', ['pertemuanId' => $pertemuan->id]) }}">
          <p class="truncate font-medium text-upbg">{{ $pertemuan->kelas->kode }}</p>
          <p class="truncate">{{ $pertemuan->textFormat('iso-waktu-mulai') }} - {{ $pertemuan->textFormat('iso-waktu-selesai') }}</p>
          <p class="truncate">{{ $pertemuan->kelas->pengajar->pluck('nickname')->implode(' - ') }}</p>
          <p class="truncate">{{ $pertemuan->ruangan->kode }}</p>
        </div>
      @endforeach
    @elseif ($key == 'tes')
      @foreach ($array as $tes)
        <div class="jadwal-item tes bg-yellow-400 bg-opacity-20" data-route="{{ route('jadwal.tes', ['tesId' => $tes->id]) }}">
          <p class="truncate font-medium text-yellow-700">{{ $tes->kode }}</p>
          <p class="truncate">{{ $tes->textFormat('iso-waktu-mulai') }} - {{ $tes->textFormat('iso-waktu-selesai') }}</p>
          <p class="truncate">{{ $tes->pengawas->pluck('nickname')->implode(' - ') }}</p>
          <p class="truncate">{{ $tes->ruangan->pluck('kode')->implode(' - ') }}</p>
        </div>
      @endforeach
    @endif
  @endforeach
</div>
