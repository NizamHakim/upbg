@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Daftar Kelas</x-slot>
  <x-slot name="header">Daftar Kelas</x-slot>

  <section>
    @can('create', Kelas::class)
      <a href="{{ route('kelas.create') }}" class="btn btn-green-solid mb-4 justify-self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Kelas</a>
    @endcan

    @include('kelas.partials.filter-kelas', [
        'programOptions' => $programOptions,
        'tipeOptions' => $tipeOptions,
        'levelOptions' => $levelOptions,
        'ruanganOptions' => $ruanganOptions,
        'statusOptions' => $statusOptions,
        'sortbyOptions' => $sortbyOptions,
        'pengajarOptions' => $pengajarOptions,
    ])

    <div class="kelas-container">
      @include('kelas.partials.kelas-table', ['kelasList' => $kelasList])
    </div>
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/daftar-kelas.js') }}"></script>
  @endPushOnce
</x-app-layout>
