@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">{{ $kelas->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Daftar Peserta</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $kelas->kode }}">
      <x-navigation.link href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.detail-pertemuan')">Daftar Pertemuan</x-navigation.link>
      <x-navigation.link href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    @can('kelolaPeserta', Kelas::class)
      <a href="{{ route('kelas.tambah-peserta', ['kelasId' => $kelas->id]) }}" class="btn btn-green-solid mb-4 justify-self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah Peserta</a>
    @endcan
    <div class="peserta-container">
      <div class="peserta-table table-wrapper">
        <div class="table-header grid-cols-16">
          <p class="col-span-3 text-center md:col-span-2 lg:col-span-1">No.</p>
          <p class="col-span-10 md:col-span-7 lg:col-span-6">Peserta</p>
          <p class="hidden lg:col-span-4 lg:block">Tanggal Bergabung</p>
          <p class="hidden md:col-span-4 md:block md:text-center lg:col-span-3">Status</p>
        </div>
        @if ($pesertaList->isEmpty())
          <div class="empty-query py-4">
            Tidak ada data yang cocok
          </div>
        @else
          @foreach ($pesertaList as $peserta)
            <div class="peserta-item table-item grid-cols-16" data-edit-route="{{ route('kelas.update-peserta', ['kelasId' => $kelas->id, 'pesertaId' => $peserta->id]) }}" data-delete-route="{{ route('kelas.destroy-peserta', ['kelasId' => $kelas->id, 'pesertaId' => $peserta->id]) }}">
              <p class="col-span-3 text-center font-medium md:col-span-2 lg:col-span-1">{{ $loop->iteration + ($pesertaList->currentPage() - 1) * $pesertaList->perPage() }}.</p>
              <div class="col-span-10 md:col-span-7 lg:col-span-6">
                <p class="nama-peserta font-medium text-gray-700">{{ $peserta->nama }}</p>
                <p class="nik-peserta text-gray-600">{{ $peserta->nik }}</p>
              </div>
              <div class="hidden lg:col-span-4 lg:block">
                <p class="tanggal-bergabung-peserta text-gray-600">{{ $peserta->created_at->format('d-m-Y') }}</p>
              </div>
              <div class="status-peserta-container hidden md:col-span-4 md:flex md:justify-center lg:col-span-3">
                @include('kelas.partials.status-peserta', ['aktif' => $peserta->pivot->aktif])
              </div>
              <div class="col-span-3 justify-self-end lg:col-span-2">
                @can('kelolaPeserta', Kelas::class)
                  <x-menu>
                    <button type="button" class="menu-item edit-peserta text-gray-700">Edit</button>
                    <button type="button" class="menu-item delete-peserta font-medium text-red-600">Hapus</button>
                  </x-menu>
                @endcan
              </div>
            </div>
          @endforeach
        @endif
      </div>
      {{ $pesertaList->links() }}
    </div>
  </section>

  @can('kelolaPeserta', Kelas::class)
    @pushOnce('modals')
      <x-modal id="edit-peserta-modal">
        <x-slot name="title">Edit Peserta</x-slot>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-gray-700">NIK / NRP</p>
            <p class="nik-peserta">NIK / NRP Peserta</p>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-gray-700">Nama</p>
            <p class="nama-peserta">Nama Peserta</p>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold text-gray-700">Tanggal Bergabung</p>
            <p class="tanggal-bergabung-peserta">Tanggal Bergabung</p>
          </div>
          <div class="input-group">
            <p class="input-label">Status</p>
            <x-checkbox class="status-peserta" color="upbg" value="1" name="status-peserta">Status</x-checkbox>
          </div>
        </div>
        <x-slot name="control">
          <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
          <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
        </x-slot>
      </x-modal>

      <x-modal id="delete-peserta-modal">
        <x-slot name="title">Hapus Peserta</x-slot>
        <div class="flex flex-col gap-4">
          <p>Apakah anda yakin ingin menghapus <span class="nama-nik-peserta font-bold">Peserta</span> dari kelas ini?</p>
          <div class="alert alert-red flex flex-col gap-2">
            <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
            <p>Data pertemuan peserta pada kelas ini akan dihapus permanen</p>
          </div>
        </div>
        <x-slot name="control">
          <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
          <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
        </x-slot>
      </x-modal>
    @endPushOnce
  @endcan

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/detail/daftar-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
