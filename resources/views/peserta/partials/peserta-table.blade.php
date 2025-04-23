@props(['pesertaList'])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-5 sm:col-span-6">Peserta</p>
    <p class="col-span-5">Dept. / Occupation</p>
  </div>
  <div class="table-content">
    @if ($pesertaList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($pesertaList as $peserta)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($pesertaList->currentPage() - 1) * $pesertaList->perPage() }}</p>
          <div class="peserta-item contents">
            <div class="col-span-5 sm:col-span-6">
              <a href="{{ route('peserta.detail', ['pesertaId' => $peserta->id]) }}" class="link font-semibold">{{ $peserta->nama }}</a>
              <p>{{ $peserta->nik }}</p>
            </div>
            <p class="col-span-5">{{ $peserta->occupation }}</p>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
{{ $pesertaList->links() }}
