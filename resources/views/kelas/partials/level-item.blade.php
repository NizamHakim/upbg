@props(['level', 'deleted' => false])

<p class="nama-level col-span-4 sm:col-span-5">{{ $level->nama }}</p>
<p class="kode-level col-span-4 sm:col-span-5">{{ $level->kode }}</p>
<div class="col-span-2 flex justify-end sm:col-span-1">
  @if ($deleted)
    <x-menu>
      <button type="button" class="menu-item restore-level text-upbg" data-route="{{ route('level-kelas.restore', ['levelId' => $level->id]) }}">Restore</button>
    </x-menu>
  @else
    <x-menu>
      <button type="button" class="menu-item edit-level text-gray-700" data-route="{{ route('level-kelas.update', ['levelId' => $level->id]) }}">Edit</button>
      <button type="button" class="menu-item hapus-level font-medium text-red-600" data-route="{{ route('level-kelas.destroy', ['levelId' => $level->id]) }}">Hapus</button>
    </x-menu>
  @endif
</div>
