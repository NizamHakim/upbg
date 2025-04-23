@props(['program', 'deleted' => false])

<p class="nama-program col-span-4 sm:col-span-5">{{ $program->nama }}</p>
<p class="kode-program col-span-4 sm:col-span-5">{{ $program->kode }}</p>
<div class="col-span-2 flex justify-end sm:col-span-1">
  @if ($deleted)
    <x-menu>
      <button type="button" class="menu-item restore-program text-upbg" data-route="{{ route('program-kelas.restore', ['programId' => $program->id]) }}">Restore</button>
    </x-menu>
  @else
    <x-menu>
      <button type="button" class="menu-item edit-program text-gray-700" data-route="{{ route('program-kelas.update', ['programId' => $program->id]) }}">Edit</button>
      <button type="button" class="menu-item hapus-program font-medium text-red-600" data-route="{{ route('program-kelas.destroy', ['programId' => $program->id]) }}">Hapus</button>
    </x-menu>
  @endif
</div>
