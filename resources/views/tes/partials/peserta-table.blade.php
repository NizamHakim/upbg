@use('App\Models\Tes', 'Tes')
@props(['tes'])

@if ($tes->pivotPeserta->isEmpty())
  <div class="p-4">
    <p class="empty-query">Tidak ada data yang cocok</p>
  </div>
@else
  @foreach ($tes->pivotPeserta as $peserta)
    <div class="peserta-item grid grid-cols-12 items-center gap-x-3 px-2 py-5" data-edit-route="{{ route('tes.update-ruangan', ['tesId' => $tes->id, 'pesertaId' => $peserta->id]) }}" data-delete-route="{{ route('tes.destroy-peserta', ['tesId' => $tes->id, 'pesertaId' => $peserta->id]) }}"="">
      <p class="col-span-2 text-center font-medium md:col-span-1">{{ $loop->iteration }}.</p>
      <div class="col-span-8 md:col-span-6">
        <p class="nama-peserta font-medium text-gray-700">{{ $peserta->peserta->nama }}</p>
        <p class="nik-peserta">{{ $peserta->peserta->nik }}</p>
        <p class="ruangan-peserta text-gray-600 md:hidden">{{ $peserta->ruangan ? $peserta->ruangan->kode : 'Tanpa ruangan' }}</p>
      </div>
      <div class="hidden md:col-span-3 md:block">
        @can('kelolaPeserta', Tes::class)
          <select name="ruangan" class="tom-select" data-route="{{ route('tes.update-ruangan', ['tesId' => $tes->id, 'pesertaId' => $peserta->id]) }}">
            <option value="-1">Tanpa ruangan</option>
            @foreach ($tes->ruangan as $ruangan)
              <option value="{{ $ruangan->id }}" @selected($ruangan->id == $peserta->ruangan_id)>{{ $ruangan->kode }}</option>
            @endforeach
          </select>
        @else
          <p class="font-medium text-gray-600">{{ $peserta->ruangan?->kode ?? 'Tanpa ruangan' }}</p>
        @endcan
      </div>
      <div class="col-span-2 flex justify-end">
        @can('kelolaPeserta', Tes::class)
          <x-menu>
            <button type="button" class="menu-item edit-peserta font-medium md:hidden">Edit</button>
            <button type="button" class="menu-item delete-peserta font-medium text-red-600">Hapus</button>
          </x-menu>
        @endcan
      </div>
    </div>
  @endforeach
  @can('kelolaPeserta', Tes::class)
    @pushOnce('modals')
      <x-modal id="edit-peserta-modal">
        <x-slot name="title">Edit Peserta</x-slot>
        <div class="flex flex-col gap-2">
          <div class="flex flex-col gap-1">
            <p class="font-semibold">Nama</p>
            <p class="nama-peserta"></p>
          </div>
          <div class="flex flex-col gap-1">
            <p class="font-semibold">NIP / NRP</p>
            <p class="nik-peserta"></p>
          </div>
          <div class="flex flex-col gap-1">
            <p class="input-group font-semibold">Ruangan</p>
            <select name="ruangan" class="tom-select">
              <option value="-1">Tanpa ruangan</option>
              @foreach ($tes->ruangan as $ruangan)
                <option value="{{ $ruangan->id }}">{{ $ruangan->kode }}</option>
              @endforeach
            </select>
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
          <p>Apakah anda yakin ingin menghapus <span class="nama-nik-peserta font-bold">Peserta</span> dari tes ini?</p>
          <div class="alert alert-red flex flex-col gap-2">
            <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
            <p>Data peserta pada tes ini akan dihapus permanen</p>
          </div>
        </div>
        <x-slot name="control">
          <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
          <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
        </x-slot>
      </x-modal>
    @endPushOnce
  @endcan
@endif
