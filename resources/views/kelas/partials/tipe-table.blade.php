@props(['tipeList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-4">Tipe</p>
    <p class="col-span-4 sm:col-span-3">Kode</p>
    <p class="hidden sm:col-span-3 sm:block">Kategori</p>
  </div>
  <div class="table-content">
    @if ($tipeList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($tipeList as $tipe)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($tipeList->currentPage() - 1) * $tipeList->perPage() }}</p>
          <div class="tipe-item contents">
            @include('kelas.partials.tipe-item', ['tipe' => $tipe, 'deleted' => $deleted])
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $tipeList->links() }}
  @if (!$deleted)
    <a href="{{ route('tipe-kelas.deleted') }}" class="mt-2 w-fit self-center underline decoration-gray-600 transition hover:text-upbg-light hover:decoration-upbg-light sm:mt-0 sm:self-start">restore</a>
  @endif
</div>
