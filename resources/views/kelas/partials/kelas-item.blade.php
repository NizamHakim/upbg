@props(['kelas'])

<div class="grid grid-cols-1 gap-y-2 rounded-md border p-4 transition hover:shadow lg:grid-cols-12 lg:items-center lg:gap-x-4 lg:gap-y-0 lg:rounded-none lg:border-t-0 lg:hover:shadow-none">
  <a href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" class="link truncate font-semibold lg:col-span-3">{{ $kelas->kode }}</a>
  <div class="flex flex-col justify-center gap-2 text-gray-700 lg:col-span-3">
    @foreach ($kelas->jadwal as $jadwal)
      <p><i class="fa-solid fa-calendar-days mr-2"></i><span class="mr-2">{{ $jadwal->textFormat('nama-hari') }}</span><i class="fa-regular fa-clock mr-2"></i><span>{{ $jadwal->waktu_mulai->format('H:i') }} - {{ $jadwal->waktu_selesai->format('H:i') }}</span></p>
    @endforeach
  </div>
  <p class="text-gray-700 lg:col-span-3"><i class="fa-regular fa-building mr-2"></i>{{ $kelas->ruangan->kode }}</p>
  <div class="flex flex-col items-end justify-center gap-1 lg:col-span-3">
    <p class="font-medium text-gray-700">{{ "$kelas->progress/$kelas->banyak_pertemuan" }} Pertemuan</p>
    @include('kelas.partials.progress-bar', ['kelas' => $kelas])
  </div>
</div>
