@props(['kelas', 'pertemuan'])

<div class="order-1 rounded-t-md bg-upbg-dark px-4 py-2 lg:order-none lg:col-span-2 lg:bg-inherit lg:px-0 lg:py-0">
  <p class="text-base font-medium text-white lg:hidden">Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</p>
  <p class="hidden lg:block lg:text-center lg:text-2xl lg:font-semibold lg:text-gray-700">{{ $pertemuan->pertemuan_ke }}</p>
</div>
<div class="order-3 px-4 lg:order-none lg:col-span-5 lg:px-0">
  <h3 class="mb-1 font-semibold text-gray-700 lg:hidden">Jadwal:</h3>
  <p class="text-gray-700"><i class="fa-solid fa-calendar-days mr-2"></i>{{ $pertemuan->textFormat('iso-tanggal') }}</p>
  <p class="text-gray-700"><i class="fa-regular fa-clock mr-2"></i>{{ $pertemuan->textFormat('iso-waktu-mulai') . ' - ' . $pertemuan->textFormat('iso-waktu-selesai') }}</p>
  <p class="text-gray-700"><i class="fa-regular fa-building mr-2"></i>{{ $pertemuan->ruangan->kode }}</p>
</div>
<div class="order-4 px-4 lg:order-none lg:col-span-4 lg:px-0">
  <h3 class="font-semibold text-gray-700 lg:hidden">Pengajar:</h3>
  <p class="text-gray-700">{{ $pertemuan->pengajar?->name ?? '-' }}</p>
</div>
<div class="order-2 px-4 lg:order-none lg:col-span-3 lg:px-0">
  <h3 class="font-semibold text-gray-700 lg:hidden">Status:</h3>
  {!! $pertemuan->textFormat('status') !!}
</div>
<div class="order-5 mb-5 flex flex-col px-4 text-center lg:order-none lg:col-span-2 lg:mb-0 lg:block lg:px-0">
  <a href="{{ route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="btn btn-upbg-outline">View<i class="fa-solid fa-circle-arrow-right ml-2"></i></a>
</div>
