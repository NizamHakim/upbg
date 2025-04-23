@props(['kelasList'])

<div class="flex flex-col">
  <div class="hidden lg:grid lg:grid-cols-12 lg:items-center lg:gap-x-4 lg:border lg:p-4 lg:font-semibold">
    <p class="col-span-3">Kode Kelas</p>
    <p class="col-span-3">Jadwal</p>
    <p class="col-span-3">Ruangan</p>
    <p class="col-span-3">Progress</p>
  </div>
  <div class="flex flex-col gap-4 lg:gap-0">
    @if ($kelasList->isEmpty())
      <p class="empty-query grid grid-cols-1 gap-y-2 rounded-md border p-4 transition hover:shadow lg:items-center lg:rounded-none lg:border-t-0 lg:hover:shadow-none">Tidak ada data yang cocok</p>
    @else
      @foreach ($kelasList as $kelas)
        @include('kelas.partials.kelas-item', ['kelas' => $kelas])
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $kelasList->links() }}
</div>
