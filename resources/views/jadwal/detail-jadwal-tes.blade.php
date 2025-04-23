<p class="truncate text-xl font-semibold text-gray-600">{{ $tes->kode }}</p>
<div class="flex flex-col">
  <p class="mb-2 font-semibold text-gray-600">Pengawas:</p>
  <ul class="list-none">
    @foreach ($tes->pengawas as $pengawas)
      <li>{{ $pengawas->name }}</li>
    @endforeach
  </ul>
</div>
<div class="flex flex-col">
  <h3 class="mb-2 font-semibold text-gray-600">Jadwal:</h3>
  <div class="flex flex-col gap-2 text-gray-600">
    <p><i class="fa-solid fa-calendar-days mr-2"></i>{{ $tes->textFormat('iso-tanggal') }}</p>
    <p><i class="fa-regular fa-clock mr-2"></i>{{ $tes->textFormat('iso-waktu-mulai') }} - {{ $tes->textFormat('iso-waktu-selesai') }}</p>
  </div>
</div>
<div class="flex flex-col">
  <h3 class="mb-1 font-semibold text-gray-600">Ruangan:</h3>
  @foreach ($tes->ruangan as $ruangan)
    <p><i class="fa-regular fa-building mr-2"></i>{{ $ruangan->kode }}</p>
  @endforeach
</div>
