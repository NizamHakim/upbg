@props(['pertemuan'])

<div class="input-group flex flex-col gap-2">
  <h3 class="font-semibold text-gray-700">Topik Bahasan</h3>
  <p class="topik text-wrap break-words">{!! $pertemuan->topik ? $pertemuan->topik : '-' !!}</p>
</div>
<div class="input-group flex flex-col gap-2">
  <h3 class="font-semibold text-gray-700">Catatan</h3>
  <p class="catatan text-wrap break-words">{!! $pertemuan->catatan ? $pertemuan->catatan : '-' !!}</p>
</div>
