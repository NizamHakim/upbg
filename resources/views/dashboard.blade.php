@use('App\Models\Kelas', 'Kelas')
@use('App\Models\Tes', 'Tes')

<x-app-layout>
  <x-slot name="title">Dashboard</x-slot>
  <x-slot name="header">Dashboard</x-slot>

  <div class="flex flex-col gap-6">
    <section>
      <p>Selamat Datang, {{ $user->name }}!</p>
    </section>

    <section class="flex flex-col items-center justify-between gap-2 sm:flex-row">
      <p class="text-center text-lg font-semibold text-upbg sm:text-left">Panduan Penggunaan Sistem</p>
      <a href="{{ route('dokumentasi') }}" target="_blank" class="btn btn-upbg-outline"><i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>Lihat Dokumentasi</a>
    </section>

    <section class="flex flex-col items-center justify-between gap-2 sm:flex-row">
      <p class="text-center text-lg font-semibold text-upbg sm:text-left">Jadwal Kegiatan UPBG ITS</p>
      <a href="{{ route('jadwal') }}" target="_blank" class="btn btn-upbg-outline"><i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>Lihat Jadwal</a>
    </section>

    @can('viewList', Kelas::class)
      <section class="flex flex-col gap-2">
        <p class="text-base font-semibold text-upbg">Kelas yang akan datang</p>
        <div class="grid grid-cols-1 gap-y-2 rounded-md border p-4 transition hover:shadow">
          @if ($pertemuan)
            <a href="{{ route('pertemuan.detail', ['kelasId' => $pertemuan->kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="link truncate font-semibold">{{ $pertemuan->kelas->kode }}</a>
            <div class="grid grid-cols-1 gap-x-1 gap-y-1 sm:grid-cols-2">
              <p class="text-gray-700 sm:order-1"><i class="fa-solid fa-list-ol mr-2"></i>Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</p>
              <p class="text-gray-700 sm:order-2"><i class="fa-solid fa-calendar-days mr-2"></i>{{ $pertemuan->textFormat('iso-tanggal') }}</p>
              <p class="text-gray-700 sm:order-4"><i class="fa-regular fa-clock mr-2"></i>{{ $pertemuan->textFormat('iso-waktu-mulai') . ' - ' . $pertemuan->textFormat('iso-waktu-selesai') }}</p>
              <p class="text-gray-700 sm:order-3"><i class="fa-regular fa-building mr-2"></i>{{ $pertemuan->ruangan->kode }}</p>
            </div>
          @else
            <p class="empty-query">Anda tidak memiliki kelas yang akan datang</p>
          @endif
        </div>
      </section>
    @endcan

    @can('viewList', Tes::class)
      <section class="flex flex-col gap-2">
        <p class="text-base font-semibold text-upbg">Tes yang akan datang</p>
        <div class="grid grid-cols-1 gap-y-2 rounded-md border p-4 transition hover:shadow">
          @if ($tes)
            <a href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" class="link truncate font-semibold">{{ $tes->kode }}</a>
            <div class="grid grid-cols-1 gap-y-1">
              <p><i class="fa-solid fa-calendar-days mr-2"></i>{{ $tes->textFormat('iso-tanggal') }}</p>
              <p><i class="fa-regular fa-clock mr-2"></i>{{ $tes->textFormat('iso-waktu-mulai') }} - {{ $tes->textFormat('iso-waktu-selesai') }}</p>
              <div class="flex flex-col">
                @foreach ($tes->ruangan as $ruangan)
                  <p><i class="fa-regular fa-building mr-2"></i>{{ $ruangan->kode }}</p>
                @endforeach
              </div>
            </div>
          @else
            <p class="empty-query">Anda tidak memiliki tes yang akan datang</p>
          @endif
        </div>
      </section>
    @endcan
  </div>

</x-app-layout>
