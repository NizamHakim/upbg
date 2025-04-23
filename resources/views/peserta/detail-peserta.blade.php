@use('App\Models\Peserta', 'Peserta')

<x-app-layout>
  <x-slot name="title">{{ $peserta->nama }}</x-slot>
  <x-slot name="header"><a href="{{ route('peserta.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Detail Peserta</x-slot>

  <section>
    <div id="detail-peserta" class="flex">
      <div class="data-peserta flex flex-col gap-4">
        @include('peserta.partials.data-peserta', ['peserta' => $peserta])
      </div>
      @can('kelolaPeserta', Peserta::class)
        <x-menu triggerSize="medium">
          <button type="button" class="menu-item edit-peserta text-gray-700">Edit</button>
          <button type="button" class="menu-item delete-peserta font-medium text-red-600">Hapus</button>
        </x-menu>
      @endcan
    </div>
  </section>

  <section class="my-4">
    <p class="mb-4 font-semibold text-upbg">Histori Kelas</p>
    <div class="kelas-container paginated-table">
      @include('peserta.partials.histori-kelas', ['historiKelas' => $historiKelas])
    </div>
  </section>

  <section>
    <p class="mb-4 font-semibold text-upbg">Histori Tes</p>
    <div class="tes-container paginated-table">
      @include('peserta.partials.histori-tes', ['historiTes' => $historiTes])
    </div>
  </section>

  @can('kelolaPeserta', Peserta::class)
    @pushOnce('modals')
      <x-modal id="edit-peserta-modal" route="{{ route('peserta.update', ['pesertaId' => $peserta->id]) }}">
        <x-slot name="title">Edit Peserta</x-slot>
        <div class="flex flex-col gap-4">
          <div class="input-group">
            <p class="input-label">NIK / NRP</p>
            <input type="text" class="input-appearance" name="nik" placeholder="NIK / NRP" value="">
          </div>
          <div class="input-group">
            <p class="input-label">Nama</p>
            <input type="text" class="input-appearance" name="nama" placeholder="Nama" value="">
          </div>
          <div class="input-group">
            <p class="input-label">Dept. / Occupation</p>
            <input type="text" class="input-appearance" name="occupation" placeholder="Dept. / Occupation" value="">
          </div>
        </div>
        <x-slot name="control">
          <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
          <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
        </x-slot>
      </x-modal>

      <x-modal id="delete-peserta-modal" route="{{ route('peserta.destroy', ['pesertaId' => $peserta->id]) }}">
        <x-slot name="title">Hapus Peserta</x-slot>
        <div class="flex flex-col gap-4">
          <p>Apakah anda yakin ingin menghapus permanen peserta <span class="nama-nik-peserta font-semibold">Peserta</span> ?</p>
        </div>
        <x-slot name="control">
          <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
          <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
        </x-slot>
      </x-modal>
    @endPushOnce
  @endcan

  @pushOnce('scripts')
    <script src="{{ asset('js/peserta/detail-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
