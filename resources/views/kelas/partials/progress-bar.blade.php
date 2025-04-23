@props(['kelas'])
@php
  $progress = ($kelas->progress / $kelas->banyak_pertemuan) * 100;
  $progress = $progress > 100 ? 100 : $progress;
  $color = $progress < 100 ? 'bg-upbg' : 'bg-green-600';
@endphp

<div class="h-1.5 w-full rounded border bg-slate-200 shadow-inner">
  <div style="width: {{ $progress }}%" class="{{ $color }} h-full rounded-full"></div>
</div>
