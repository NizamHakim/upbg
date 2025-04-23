@props(['programList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-4 sm:col-span-5">Program</p>
    <p class="col-span-4 sm:col-span-5">Kode</p>
  </div>
  <div class="table-content">
    @if ($programList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($programList as $program)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($programList->currentPage() - 1) * $programList->perPage() }}</p>
          <div class="program-item contents">
            @include('kelas.partials.program-item', ['program' => $program, 'deleted' => $deleted])
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $programList->links() }}
  @if (!$deleted)
    <a href="{{ route('program-kelas.deleted') }}" class="mt-2 w-fit self-center underline decoration-gray-600 transition hover:text-upbg-light hover:decoration-upbg-light sm:mt-0 sm:self-start">restore</a>
  @endif
</div>
