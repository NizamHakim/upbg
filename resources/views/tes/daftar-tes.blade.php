@use('App\Models\Tes', 'Tes')

<x-app-layout>
  <x-slot name="title">Daftar Tes</x-slot>
  <x-slot name="header">Daftar Tes</x-slot>

  <section class="rounded-md bg-white p-6 shadow-sm">
    @can('create', Tes::class)
      <a href="{{ route('tes.create') }}" class="btn btn-green-solid mb-4 justify-self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Tes</a>
    @endcan

    @include('tes.partials.filter-tes', [
        'tipeOptions' => $tipeOptions,
        'ruanganOptions' => $ruanganOptions,
        'statusOptions' => $statusOptions,
        'sortbyOptions' => $sortbyOptions,
        'pengawasOptions' => $pengawasOptions,
    ])

    <div class="flex flex-col">
      <div class="hidden lg:grid lg:grid-cols-6 lg:gap-x-3 lg:border lg:p-4">
        <p class="col-span-2 font-semibold">Kode Tes</p>
        <p class="col-span-2 font-semibold">Jadwal</p>
        <p class="col-span-2 font-semibold">Ruangan</p>
      </div>
      @include('tes.partials.tes-table', ['tesList' => $tesList])
    </div>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/tes/daftar-tes.js') }}"></script>
  @endPushOnce
</x-app-layout>
