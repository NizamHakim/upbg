@props(['peserta'])

<div class="flex flex-col gap-1">
  <p class="font-semibold">NIK / NRP</p>
  <p class="nik">{{ $peserta->nik }}</p>
</div>
<div class="flex flex-col gap-1">
  <p class="font-semibold">Nama</p>
  <p class="nama">{{ $peserta->nama }}</p>
</div>
<div class="flex flex-col gap-1">
  <p class="font-semibold">Dept. / Occupation</p>
  <p class="occupation">{{ $peserta->occupation }}</p>
</div>
