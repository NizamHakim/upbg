@props(['kelas'])

<div class="pertemuan-table flex flex-col gap-4 lg:gap-0 lg:divide-y lg:border">
  <div class="hidden lg:grid lg:grid-cols-16 lg:items-center lg:gap-x-4 lg:px-4 lg:py-4 lg:font-semibold">
    <p class="text-center lg:col-span-2">Pertemuan ke</p>
    <p class="lg:col-span-5">Jadwal</p>
    <p class="lg:col-span-4">Pengajar</p>
    <p class="lg:col-span-3">Status</p>
  </div>
  @if ($kelas->pertemuan->isEmpty())
    <div class="empty-query lg:py-4">
      Tidak ada data yang cocok
    </div>
  @else
    @foreach ($kelas->pertemuan as $pertemuan)
      <div class="pertemuan-item flex flex-col gap-4 rounded-md shadow lg:grid lg:grid-cols-16 lg:items-center lg:gap-x-4 lg:gap-y-0 lg:rounded-none lg:px-4 lg:py-4 lg:shadow-none">
        @include('kelas.partials.pertemuan-item', ['kelas' => $kelas, 'pertemuan' => $pertemuan])
      </div>
    @endforeach
  @endif
</div>
