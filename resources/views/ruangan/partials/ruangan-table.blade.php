@props(['ruanganList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-5 sm:col-span-5">Kode Ruangan</p>
    <p class="col-span-3 sm:col-span-5">Kapasitas</p>
  </div>
  <div class="table-content">
    @if ($ruanganList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($ruanganList as $ruangan)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($ruanganList->currentPage() - 1) * $ruanganList->perPage() }}</p>
          <div class="ruangan-item contents">
            @include('ruangan.partials.ruangan-item', ['ruangan' => $ruangan, 'deleted' => $deleted])
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $ruanganList->links() }}
  @if (!$deleted)
    <a href="{{ route('ruangan.deleted') }}" class="mt-2 w-fit self-center underline decoration-gray-600 transition hover:text-upbg-light hover:decoration-upbg-light sm:mt-0 sm:self-start">restore</a>
  @endif
</div>
