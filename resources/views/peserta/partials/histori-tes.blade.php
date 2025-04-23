@props(['historiTes'])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-10 sm:col-span-11">Tes</p>
  </div>
  <div class="table-content">
    @if ($historiTes->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($historiTes as $tes)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($historiTes->currentPage() - 1) * $historiTes->perPage() }}</p>
          <div class="tes-item contents">
            <p class="col-span-10 sm:col-span-11">{{ $tes->tes->kode }}</p>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
{{ $historiTes->links() }}
