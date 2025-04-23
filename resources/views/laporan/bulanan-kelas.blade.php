<x-app-layout>
  <x-slot name="title">Laporan Kelas</x-slot>
  <x-slot name="header">Laporan Kelas</x-slot>

  <section>
    <form id="filter-kelas-form" action="{{ route('laporan.kelas') }}" class="flex flex-col gap-5">
      <div class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-[1fr_auto_1fr]">
        <div class="input-group">
          <p class="input-label">Tanggal Mulai</p>
          <input type="date" name="tanggal-mulai" value="" class="fp-datepicker input-appearance" placeholder="Tanggal mulai">
        </div>
        <i class="fa-solid fa-arrow-right hidden text-center text-base sm:mt-8 sm:block"></i>
        <i class="fa-solid fa-arrow-down text-center text-base sm:hidden"></i>
        <div class="input-group">
          <p class="input-label">Tanggal Akhir</p>
          <input type="date" name="tanggal-akhir" value="" class="fp-datepicker input-appearance" placeholder="Tanggal akhir">
        </div>
      </div>
      <div class="flex justify-center">
        <x-checkbox color="upbg" name="include-tidak-terlaksana" value="1" class="w-fit">Include Tidak Terlaksana</x-checkbox>
      </div>
      <div class="alert alert-upbg alert-tidak-terlaksana hidden">
        <p class="text-center italic">note : Pada kelas dengan pengajar lebih dari satu, pertemuan kelas yang <span class="font-semibold">Tidak Terlaksana</span> akan <span class="font-semibold">dianggap terlaksana untuk setiap pengajar</span> (duplikat). Silahkan lakukan penyesuaian pada file output. Contoh output dapat dilihat pada <a class="underline transition hover:text-upbg-light" href="{{ route('dokumentasi') }}#buat-laporan-kelas">dokumentasi</a></p>
      </div>
      <button type="submit" class="btn btn-upbg-solid"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button>
    </form>
    <hr class="my-5">
    <div class="flex flex-col items-center">
      <p class="font-medium">Hasil Pencarian:</p>
      <div id="kelas-result" class="mt-4 w-full">
        <p class="text-center">-</p>
      </div>
    </div>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/laporan/bulanan-kelas.js') }}"></script>
  @endPushOnce
</x-app-layout>
