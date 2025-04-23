@props(['kelas'])

<div class="flex flex-col items-center gap-2 md:items-start">
  <p class="text-center text-lg text-gray-700 md:text-left md:text-2xl">Pertemuan Terlaksana</p>
  <p class="text-3xl font-semibold text-gray-700 md:text-4xl">{{ $kelas->progress }} / {{ $kelas->banyak_pertemuan }}</p>
</div>
