@props(['tesList'])

<div class="tes-container flex flex-col gap-3 lg:gap-0 lg:divide-y lg:border-x lg:border-b">
  @if ($tesList->isEmpty())
    <div class="p-4">
      <p class="empty-query">Tidak ada data yang cocok</p>
    </div>
  @else
    @foreach ($tesList as $tes)
      <div class="tes-item grid grid-cols-1 gap-y-3 rounded-md border bg-white p-4 shadow-sm lg:grid-cols-6 lg:gap-x-3 lg:gap-y-0 lg:rounded-none lg:border-0 lg:shadow-none">
        <div class="col-span-2 flex items-center">
          <a href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" class="truncate font-semibold text-upbg underline decoration-transparent transition hover:decoration-upbg">{{ $tes->kode }}</a>
        </div>
        <div class="col-span-2 flex flex-col justify-center gap-1">
          <p class="font-medium text-gray-700 lg:hidden">Jadwal:</p>
          <p><i class="fa-solid fa-calendar-days mr-2"></i>{{ $tes->textFormat('iso-tanggal') }}</p>
          <p><i class="fa-regular fa-clock mr-2"></i>{{ $tes->textFormat('iso-waktu-mulai') }} - {{ $tes->textFormat('iso-waktu-selesai') }}</p>
        </div>
        <div class="col-span-2 flex flex-col justify-center gap-1">
          <p class="font-medium text-gray-700 lg:hidden">Ruangan:</p>
          @foreach ($tes->ruangan as $ruangan)
            <p><i class="fa-regular fa-building mr-2"></i>{{ $ruangan->kode }}</p>
          @endforeach
        </div>
      </div>
    @endforeach
  @endif
</div>
{{ $tesList->links() }}
