@props(['kategori'])

<p class="nama-kategori col-span-8 sm:col-span-10">{{ $kategori->nama }}</p>
<div class="col-span-2 flex justify-end sm:col-span-1">
  <x-menu>
    <button type="button" class="menu-item edit-kategori text-gray-700" data-route="{{ route('kategori-tes.update', ['kategoriId' => $kategori->id]) }}">Edit</button>
    <button type="button" class="menu-item hapus-kategori font-medium text-red-600" data-route="{{ route('kategori-tes.destroy', ['kategoriId' => $kategori->id]) }}">Hapus</button>
  </x-menu>
</div>
