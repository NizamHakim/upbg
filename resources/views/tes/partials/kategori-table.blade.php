@props(['kategoriList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-8 sm:col-span-10">Kategori</p>
  </div>
  <div class="table-content">
    @if ($kategoriList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($kategoriList as $kategori)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($kategoriList->currentPage() - 1) * $kategoriList->perPage() }}</p>
          <div class="kategori-item contents">
            @include('tes.partials.kategori-item', ['kategori' => $kategori, 'deleted' => $deleted])
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
{{ $kategoriList->links() }}
