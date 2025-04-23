@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">{{ $kelas->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Daftar Pertemuan</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $kelas->kode }}">
      <x-navigation.link href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.detail-pertemuan')">Daftar Pertemuan</x-navigation.link>
      <x-navigation.link href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section>
    <div class="flex flex-col gap-6">
      <div class="flex items-center justify-between">
        <h2 class="truncate text-xl font-semibold text-gray-700 md:text-2xl">{{ $kelas->kode }}</h2>
        @canany(['edit', 'delete'], Kelas::class)
          <x-menu triggerSize="medium">
            <a href="{{ route('kelas.edit', ['kelasId' => $kelas->id]) }}" class="menu-item text-gray-700">Edit</a>
            <button type="button" class="menu-item delete-kelas font-medium text-red-600">Hapus</button>
          </x-menu>
          @pushOnce('modals')
            <x-modal id="delete-kelas-modal" route="{{ route('kelas.destroy', ['kelasId' => $kelas->id]) }}">
              <x-slot name="title">Hapus Kelas</x-slot>
              <p class="mb-3 text-gray-700">Apakah anda yakin ingin menghapus kelas <span class="kode-kelas font-bold">{{ $kelas->kode }}</span></p>
              <div class="alert alert-red flex flex-col gap-2">
                <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
                <p>Semua data kelas ini akan dihapus permanen</p>
              </div>
              <x-slot name="control">
                <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
              </x-slot>
            </x-modal>
          @endPushOnce
        @endcanany
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Pengajar:</h3>
        <ul class="list-none">
          @foreach ($kelas->pengajar as $pengajar)
            <li>{{ $pengajar->name }}</li>
          @endforeach
        </ul>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-2 font-semibold text-gray-700">Jadwal:</h3>
        <div class="flex flex-col gap-2 text-gray-700">
          @foreach ($kelas->jadwal as $jadwal)
            <p><i class="fa-solid fa-calendar-days mr-2"></i><span class="mr-2">{{ $jadwal->textFormat('nama-hari') }}</span><i class="fa-regular fa-clock mr-2"></i><span>{{ $jadwal->textFormat('iso-waktu-mulai') }} - {{ $jadwal->textFormat('iso-waktu-selesai') }}</span></p>
          @endforeach
        </div>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Ruangan:</h3>
        <span class="text-gray-700"><i class="fa-regular fa-building mr-2"></i>{{ $kelas->ruangan->kode }}</span>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Whatsapp Group:</h3>
        @if ($kelas->group_link)
          <a href="{{ $kelas->group_link }}" target="_blank" class="link break-all">{{ $kelas->group_link }}</a>
        @else
          <span class="text-gray-700">-</span>
        @endif
      </div>
    </div>
  </section>

  <section class="my-8">
    <div class="flex min-w-0 flex-col gap-6 md:flex-row md:items-center md:justify-between">
      <div class="pertemuan-terlaksana contents">
        @include('kelas.partials.pertemuan-terlaksana', ['kelas' => $kelas])
      </div>
      <button type="button" class="tambah-pertemuan btn btn-green-solid"><i class="fa-solid fa-plus mr-2"></i><span>Tambah Pertemuan</span></button>
    </div>
  </section>

  <section>
    <div class="pertemuan-container">
      @include('kelas.partials.pertemuan-table', ['kelas' => $kelas])
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="tambah-pertemuan-modal" route="{{ route('pertemuan.store', ['kelasId' => $kelas->id]) }}">
      <x-slot name="title">Tambah Pertemuan</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="input-label">Tanggal</p>
          <input type="date" name="tanggal" value="" class="fp-datepicker input-appearance" placeholder="Tanggal">
        </div>
        <div class="grid grid-cols-2 gap-x-4">
          <div class="input-group">
            <p class="input-label">Waktu Mulai</p>
            <input type="time" name="waktu-mulai" value="" class="fp-timepicker input-appearance" placeholder="Waktu Mulai">
          </div>
          <div class="input-group">
            <p class="input-label">Waktu Selesai</p>
            <input type="time" name="waktu-selesai" value="" class="fp-timepicker input-appearance" placeholder="Waktu Selesai">
          </div>
        </div>
        <div class="input-group">
          <p class="input-label">Ruangan</p>
          <select name="ruangan" class="tom-select">
            <option value="">Semua</option>
            @foreach ($ruanganOptions as $ruangan)
              <option value="{{ $ruangan->id }}">{{ $ruangan->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-green-solid">Tambah</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/detail/daftar-pertemuan.js') }}"></script>
  @endPushOnce
</x-app-layout>
