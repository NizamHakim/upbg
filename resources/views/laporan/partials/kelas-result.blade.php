@props(['kelasList', 'firstDate', 'lastDate', 'includeTidakTerlaksana'])

<form action="{{ route('laporan.export-kelas') }}" method="POST">
  @csrf
  <input type="hidden" name="tanggal-mulai" value="{{ $firstDate }}">
  <input type="hidden" name="tanggal-akhir" value="{{ $lastDate }}">
  <input type="hidden" name="include-tidak-terlaksana" value="{{ $includeTidakTerlaksana }}">
  @if (!$kelasList->isEmpty())
    <div class="mb-4 flex flex-col items-end justify-end gap-2 sm:flex-row">
      <x-checkbox color="upbg" name="semua-kelas" class="order-2 sm:order-none">Pilih Semua</x-checkbox>
      <button type="submit" class="btn btn-upbg-solid order-1 w-fit sm:order-none"><i class="fa-solid fa-file-arrow-down mr-2"></i></i>Download Laporan</button>
    </div>
  @endif
  <div class="table-wrapper">
    <div class="table-header grid-cols-12">
      <p class="col-span-2 text-center sm:col-span-1">No</p>
      <p class="col-span-10 sm:col-span-11">Kelas</p>
    </div>
    <div class="table-content">
      @if ($kelasList->isEmpty())
        <p class="empty-query p-4">Tidak ada data yang cocok</p>
      @else
        @foreach ($kelasList as $kelas)
          <div class="table-item kelas-item cursor-pointer grid-cols-12 has-[:checked]:bg-blue-50">
            <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration }}</p>
            <p class="col-span-9 truncate font-medium sm:col-span-10">{{ $kelas->kode }}</p>
            <x-checkbox color="transparent" name="kelas[]" class="col-span-1" value="{{ $kelas->id }}" />
          </div>
        @endforeach
      @endif
    </div>
  </div>
</form>
