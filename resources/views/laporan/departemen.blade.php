<x-app-layout>
  <x-slot name="title">Laporan Departemen</x-slot>
  <x-slot name="header">Laporan Departemen</x-slot>

  <section>
    <form id="filter-peserta-form" action="{{ route('laporan.departemen') }}" class="flex flex-col gap-4">
      <div class="input-group">
        <p class="input-label">Cari Departemen</p>
        <select name="departemen" placeholder="Nama Departemen" data-route="{{ route('laporan.find-departemen') }}"></select>
      </div>
      <div class="input-group">
        <p class="input-label">Cari Mahasiswa</p>
        <select name="mahasiswa" placeholder="Nama Mahasiswa" data-route="{{ route('laporan.find-mahasiswa') }}"></select>
      </div>
      <button type="submit" class="btn btn-upbg-solid"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button>
    </form>
    <hr class="my-5">
    <div class="flex flex-col items-center">
      <p class="font-medium">Hasil Pencarian:</p>
      <div id="peserta-result" class="mt-4 w-full">
        @include('laporan.partials.departemen-result')
      </div>
    </div>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/laporan/departemen.js') }}"></script>
  @endPushOnce
</x-app-layout>
