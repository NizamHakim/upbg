<x-app-layout>
  <x-slot name="title">Daftar Peserta</x-slot>
  <x-slot name="header">Daftar Peserta</x-slot>

  <section>
    <div class="flex flex-col">
      <form id="filter-peserta" action="{{ route('peserta.index') }}" class="mb-4">
        <x-search-bar placeholder="Cari nama, NIK/NRP, atau Dept./Occupation peserta" value="{{ old('search') }}" name="search" />
      </form>

      <div class="user-container">
        @include('peserta.partials.peserta-table', ['pesertaList' => $pesertaList])
      </div>
    </div>
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/peserta/daftar-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
