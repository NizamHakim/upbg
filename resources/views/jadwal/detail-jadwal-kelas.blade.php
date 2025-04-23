<p class="truncate text-xl font-semibold text-gray-600">{{ $kelas->kode }}</p>
<div class="flex flex-col">
  <p class="mb-2 font-semibold text-gray-600">Pengajar:</p>
  <ul class="list-none">
    @foreach ($kelas->pengajar as $pengajar)
      <li>{{ $pengajar->name }}</li>
    @endforeach
  </ul>
</div>
<div class="flex flex-col">
  <h3 class="mb-2 font-semibold text-gray-600">Jadwal:</h3>
  <div class="flex flex-col gap-2 text-gray-600">
    @foreach ($kelas->jadwal as $jadwal)
      <p><i class="fa-solid fa-calendar-days mr-2"></i><span class="mr-2">{{ $jadwal->textFormat('nama-hari') }}</span><i class="fa-regular fa-clock mr-2"></i><span>{{ $jadwal->textFormat('iso-waktu-mulai') }} - {{ $jadwal->textFormat('iso-waktu-selesai') }}</span></p>
    @endforeach
  </div>
</div>
<div class="flex flex-col">
  <h3 class="mb-1 font-semibold text-gray-600">Ruangan:</h3>
  <span class="text-gray-700"><i class="fa-regular fa-building mr-2"></i>{{ $kelas->ruangan->kode }}</span>
</div>
