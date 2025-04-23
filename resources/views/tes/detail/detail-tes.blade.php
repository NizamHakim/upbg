@use('App\Models\Tes', 'Tes')

<x-app-layout>
  <x-slot name="title">{{ $tes->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('tes.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Detail Tes</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $tes->kode }}">
      <x-navigation.link href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.detail')">Detail Tes</x-navigation.link>
      <x-navigation.link href="{{ route('tes.daftar-peserta', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section>
    <div class="flex flex-col gap-6">
      <div class="flex items-center justify-between">
        <h2 class="truncate text-xl font-semibold text-gray-700 md:text-2xl">{{ $tes->kode }}</h2>
        @canany(['delete', 'editDetail'], Tes::class)
          <x-menu triggerSize="medium">
            <a href="{{ route('tes.edit', ['tesId' => $tes->id]) }}" class="menu-item text-gray-700">Edit</a>
            <button type="button" class="menu-item delete-tes font-medium text-red-600">Hapus</button>
          </x-menu>
        @endcanany
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Status:</h3>
        {!! $tes->textFormat('status') !!}
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Pengawas:</h3>
        <ul class="list-none">
          @foreach ($tes->pengawas as $pengawas)
            <li>{{ $pengawas->name }}</li>
          @endforeach
        </ul>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-2 font-semibold text-gray-700">Jadwal:</h3>
        <p><i class="fa-solid fa-calendar-days mr-2"></i>{{ $tes->textFormat('iso-tanggal') }}</p>
        <p><i class="fa-regular fa-clock mr-2"></i>{{ $tes->textFormat('iso-waktu-mulai') }} - {{ $tes->textFormat('iso-waktu-selesai') }}</p>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Ruangan:</h3>
        @foreach ($tes->ruangan as $ruangan)
          <p><i class="fa-regular fa-building mr-2"></i>{{ $ruangan->kode }}</p>
        @endforeach
      </div>
    </div>
    @can('delete', Tes::class)
      @pushOnce('modals')
        <x-modal id="delete-tes-modal" route="{{ route('tes.destroy', ['tesId' => $tes->id]) }}">
          <x-slot name="title">Hapus Tes</x-slot>
          <p class="mb-3 text-gray-700">Apakah anda yakin ingin menghapus tes <span class="kode-tes font-bold">{{ $tes->kode }}</span></p>
          <div class="alert alert-red flex flex-col gap-2">
            <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
            <p>Semua data tes ini akan dihapus permanen</p>
          </div>
          <x-slot name="control">
            <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
            <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
          </x-slot>
        </x-modal>
      @endPushOnce
    @endcan
  </section>

  <section class="mt-4">
    @if ($tes->status() == 'terlaksana')
      {{-- show presensi list --}}
      <div id="daftar-presensi" class="flex flex-col gap-4">
        <div class="flex flex-col gap-6 md:flex-row md:justify-between">
          <div class="flex flex-col items-center gap-2 md:items-start">
            <p class="text-lg text-gray-700 md:text-2xl">Kehadiran Peserta</p>
            <div class="hadir-counter">
              <x-hadir-count :hadir="$tes->hadirCount" :total="$tes->totalCount" />
            </div>
          </div>
          <div class="flex flex-col justify-center gap-2">
            <select name="ruangan" class="tom-select filter-ruangan min-w-64">
              <option value="">Semua ruangan</option>
              <option value="-1">Tanpa ruangan</option>
              @foreach ($tes->ruangan as $ruangan)
                <option value="{{ $ruangan->id }}">{{ $ruangan->kode }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <form class="filter-search">
          <x-search-bar name="search" placeholder="Cari nama peserta" />
        </form>
        <div class="mt-2 flex flex-col">
          <div class="grid grid-cols-8 items-center gap-x-3 border py-4">
            <p class="col-span-1 pl-2 text-center font-semibold">No.</p>
            <p class="col-span-4 font-semibold">Peserta</p>
            <p class="col-span-3 pr-2 font-semibold">Status Kehadiran</p>
          </div>
          <div class="presensi-container divide-y border-x border-b">
            @include('tes.partials.presensi-table', ['tes' => $tes])
          </div>
        </div>
      </div>
    @elseif ($tes->status() == 'belum-terlaksana')
      {{-- show countdown and start button --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-center text-gray-600">Mulai tes untuk membuat daftar kehadiran</p>
        <form action="{{ route('tes.mulai', ['tesId' => $tes->id]) }}">
          <button disabled type="submit" class="mulai-tes btn btn-upbg-solid disabled:opacity-70 disabled:hover:bg-upbg">Mulai Tes</button>
        </form>
        <p class="countdown-label text-center text-gray-600">Tes dapat dimulai dalam,<br><span data-waktu-mulai="{{ $tes->waktu_mulai }}" class="countdown font-semibold text-upbg">0d 0h 0m 0s</span></p>
      </div>
    @elseif ($tes->status() == 'sedang-berlangsung')
      {{-- show start button --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-center text-gray-600">Mulai tes untuk membuat daftar kehadiran</p>
        <form action="{{ route('tes.mulai', ['tesId' => $tes->id]) }}">
          <button type="submit" class="mulai-tes btn btn-upbg-solid disabled:opacity-70 disabled:hover:bg-upbg">Mulai Tes</button>
        </form>
      </div>
    @elseif ($tes->status() == 'tidak-terlaksana')
      {{-- show notes for tidak terlaksana --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-base font-semibold text-red-600">Tes telah selesai</p>
        <p class="text-center text-gray-600">Silahkan menghubungi admin untuk mengubah jadwal</p>
      </div>
    @endif
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/utils/countdown.js') }}"></script>
    <script src="{{ asset('js/tes/detail/detail-tes.js') }}"></script>
  @endPushOnce
</x-app-layout>
