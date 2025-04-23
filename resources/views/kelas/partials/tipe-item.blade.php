@props(['tipe', 'deleted' => false])

<p class="nama-tipe col-span-4">{{ $tipe->nama }}</p>
<p class="kode-tipe col-span-4 sm:col-span-3">{{ $tipe->kode }}</p>
<p class="kategori-tipe hidden sm:col-span-3 sm:block" data-id="{{ $tipe->kategori?->id }}">{{ $tipe->kategori?->nama ?? '-' }}</p>
<div class="col-span-2 flex justify-end sm:col-span-1">
  @if ($deleted)
    <x-menu>
      <button type="button" class="menu-item restore-tipe text-upbg" data-route="{{ route('tipe-kelas.restore', ['tipeId' => $tipe->id]) }}">Restore</button>
    </x-menu>
  @else
    <x-menu>
      <button type="button" class="menu-item edit-tipe text-gray-700" data-route="{{ route('tipe-kelas.update', ['tipeId' => $tipe->id]) }}">Edit</button>
      <button type="button" class="menu-item hapus-tipe font-medium text-red-600" data-route="{{ route('tipe-kelas.destroy', ['tipeId' => $tipe->id]) }}">Hapus</button>
    </x-menu>
  @endif
</div>
