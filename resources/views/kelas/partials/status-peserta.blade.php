@props(['aktif'])

@if ($aktif)
  <p class="status-peserta font-semibold text-green-600">Aktif</p>
@else
  <p class="status-peserta font-semibold text-red-600">Tidak Aktif</p>
@endif
