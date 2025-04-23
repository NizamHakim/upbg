@props(['ruangan', 'deleted' => false])

<p class="kode-ruangan col-span-5 sm:col-span-5">{{ $ruangan->kode }}</p>
<p class="kapasitas-ruangan col-span-3 sm:col-span-5">{{ $ruangan->kapasitas }}</p>
<div class="col-span-2 flex justify-end sm:col-span-1">
  @if ($deleted)
    <x-menu>
      <button type="button" class="menu-item restore-ruangan text-upbg" data-route="{{ route('ruangan.restore', ['ruanganId' => $ruangan->id]) }}">Restore</button>
    </x-menu>
  @else
    <x-menu>
      <button type="button" class="menu-item edit-ruangan text-gray-700" data-route="{{ route('ruangan.update', ['ruanganId' => $ruangan->id]) }}">Edit</button>
      <button type="button" class="menu-item hapus-ruangan font-medium text-red-600" data-route="{{ route('ruangan.destroy', ['ruanganId' => $ruangan->id]) }}">Hapus</button>
    </x-menu>
  @endif
</div>
