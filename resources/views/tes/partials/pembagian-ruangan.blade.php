@props(['ruanganTes', 'notAssigned'])

@foreach ($ruanganTes as $ruangan)
  <p class="{{ $ruangan->pesertaCount > $ruangan->kapasitas ? 'text-red-600' : 'text-gray-700' }} font-medium">{{ $ruangan->kode }} : <span class="font-normal">{{ $ruangan->pesertaCount }} / {{ $ruangan->kapasitas }}</span></p>
@endforeach
@if ($notAssigned > 0)
  <p class="font-medium text-red-600">Tanpa Ruangan : <span class="font-normal">{{ $notAssigned }}</span></p>
@endif
