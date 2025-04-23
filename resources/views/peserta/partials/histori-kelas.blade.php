@props(['historiKelas'])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-10 sm:col-span-11">Kelas</p>
  </div>
  <div class="table-content">
    @if ($historiKelas->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($historiKelas as $kelas)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($historiKelas->currentPage() - 1) * $historiKelas->perPage() }}</p>
          <div class="kelas-item contents">
            <p class="col-span-10 sm:col-span-11">{{ $kelas->kode }}</p>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
{{ $historiKelas->links() }}
