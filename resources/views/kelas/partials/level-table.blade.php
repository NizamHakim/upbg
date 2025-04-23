@props(['levelList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-4 sm:col-span-5">Level</p>
    <p class="col-span-4 sm:col-span-5">Kode</p>
  </div>
  <div class="table-content">
    @if ($levelList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($levelList as $level)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($levelList->currentPage() - 1) * $levelList->perPage() }}</p>
          <div class="level-item contents">
            @include('kelas.partials.level-item', ['level' => $level, 'deleted' => $deleted])
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $levelList->links() }}
  @if (!$deleted)
    <a href="{{ route('level-kelas.deleted') }}" class="mt-2 w-fit self-center underline decoration-gray-600 transition hover:text-upbg-light hover:decoration-upbg-light sm:mt-0 sm:self-start">restore</a>
  @endif
</div>
