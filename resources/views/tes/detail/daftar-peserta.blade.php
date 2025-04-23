@use('App\Models\Tes', 'Tes')

<x-app-layout>
  <x-slot name="title">{{ $tes->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('tes.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Daftar Peserta</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $tes->kode }}">
      <x-navigation.link href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.detail')">Detail Tes</x-navigation.link>
      <x-navigation.link href="{{ route('tes.daftar-peserta', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section class="mb-4">
    <div class="flex flex-col gap-6 md:flex-row md:justify-between">
      <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
          <h2 class="truncate text-xl font-semibold text-gray-700">Pembagian Ruangan</h2>
        </div>
        <div class="total-peserta flex flex-col">
          @include('tes.partials.total-peserta', ['tes' => $tes])
        </div>
        <div class="flex flex-col">
          <h3 class="mb-2 font-semibold text-gray-700">Ruangan</h3>
          <div class="pembagian-ruangan flex flex-col gap-1">
            @include('tes.partials.pembagian-ruangan', ['ruanganTes' => $ruanganTes, 'notAssigned' => $notAssigned])
          </div>
        </div>
      </div>
      <div class="flex flex-col gap-2">
        @can('kelolaPeserta', Tes::class)
          <a href="{{ route('tes.create-peserta', ['tesId' => $tes->id]) }}" class="btn btn-green-solid"><i class="fa-solid fa-plus mr-2"></i>Tambah Peserta</a>
        @endcan
        <a href="{{ route('tes.download-ruangan', ['tesId' => $tes->id]) }}" class="btn btn-white"><i class="fa-solid fa-file-arrow-down mr-2"></i>Pembagian Ruangan</a>
      </div>
    </div>
  </section>

  @can('kelolaPeserta', Tes::class)
    <section class="mb-4">
      <p class="mb-2 font-semibold text-gray-600">Ubah Berdasarkan Ruangan</p>
      <form action="{{ route('tes.update-ruangan-batch', ['tesId' => $tes->id]) }}" class="ubah-ruangan-batch grid grid-cols-1 gap-y-3 md:grid-cols-[1fr_auto_1fr_auto] md:gap-x-4 md:gap-y-0">
        <div class="input-group">
          <select name="ruangan-src" class="tom-select">
            <option value="">Pilih ruangan asal</option>
            <option value="-1">Tanpa ruangan</option>
            @foreach ($tes->ruangan as $ruangan)
              <option value="{{ $ruangan->id }}">{{ $ruangan->kode }}</option>
            @endforeach
          </select>
        </div>
        <i class="fa-solid fa-arrow-right hidden text-center text-base md:mt-1.5 md:block"></i>
        <i class="fa-solid fa-arrow-down text-center text-base md:hidden"></i>
        <div class="input-group">
          <select name="ruangan-dest" class="tom-select">
            <option value="">Pilih ruangan tujuan</option>
            <option value="-1">Tanpa ruangan</option>
            @foreach ($tes->ruangan as $ruangan)
              <option value="{{ $ruangan->id }}">{{ $ruangan->kode }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-upbg-solid mt-1 h-fit">Update</button>
      </form>
    </section>
  @endcan

  <section>
    <p class="mb-2 font-semibold text-gray-600">Daftar Peserta</p>
    <div id="daftar-peserta" class="flex flex-col gap-6">
      <div class="grid grid-cols-1 gap-y-4 md:grid-cols-5 md:gap-x-4">
        <select name="ruangan" class="tom-select filter-ruangan col-span-1">
          <option value="">Semua ruangan</option>
          <option value="-1">Tanpa ruangan</option>
          @foreach ($tes->ruangan as $ruangan)
            <option value="{{ $ruangan->id }}">{{ $ruangan->kode }}</option>
          @endforeach
        </select>
        <form class="filter-search md:col-span-4">
          <x-search-bar name="search" placeholder="Cari nama peserta" />
        </form>
      </div>
      <div class="flex flex-col">
        <div class="grid grid-cols-12 items-center gap-x-3 border px-2 py-4">
          <p class="col-span-2 text-center font-semibold md:col-span-1">No.</p>
          <p class="col-span-8 font-semibold md:col-span-6">Peserta</p>
          <p class="hidden md:col-span-3 md:block md:font-semibold">Ruangan</p>
        </div>
        <div class="peserta-container divide-y border-x border-b">
          @include('tes.partials.peserta-table', ['tes' => $tes])
        </div>
      </div>
    </div>
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/tes/detail/daftar-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
