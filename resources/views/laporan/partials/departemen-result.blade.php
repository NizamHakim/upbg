@props(['pesertaList' => null, 'departemen' => null, 'mahasiswa' => null])

@if (!$pesertaList || $pesertaList->isEmpty())
  <p class="text-center">-</p>
@else
  <form action="{{ route('laporan.export-departemen') }}" method="POST">
    @csrf
    <input type="hidden" name="departemen" value="{{ $departemen }}">
    <input type="hidden" name="mahasiswa" value="{{ $mahasiswa }}">

    <div class="mb-4 flex justify-end">
      <button type="submit" class="btn btn-upbg-solid w-fit"><i class="fa-solid fa-file-arrow-down mr-2"></i></i>Download Laporan</button>
    </div>
    <div class="table-wrapper">
      <div class="table-header grid-cols-12">
        <p class="col-span-1 text-center">No</p>
        <p class="col-span-3">Nama</p>
        <p class="col-span-4">NRP</p>
        <p class="col-span-4">Departemen</p>
      </div>
      <div class="table-content">
        @foreach ($pesertaList as $peserta)
          <div class="table-item grid-cols-12">
            <p class="col-span-1 text-center">{{ $loop->iteration }}</p>
            <p class="col-span-3">{{ $peserta->nama }}</p>
            <p class="col-span-4">{{ $peserta->nik }}</p>
            <p class="col-span-4">{{ $peserta->occupation }}</p>
            <div class="col-span-12 my-4 flex flex-col gap-4">
              <div class="flex flex-col gap-2">
                <p class="font-medium text-upbg">Histori Kelas:</p>
                @foreach ($peserta->kelas as $kelas)
                  <div class="flex flex-col sm:flex-row sm:gap-2">
                    <p class="font-medium">{{ $kelas->kode }}</p>
                    <p class="hidden sm:block">-</p>
                    <p>Terlaksana: {{ $kelas->progress }}; Hadir: {{ $kelas->kehadiran }}; ({{ $kelas->percentage }}%)</p>
                  </div>
                @endforeach
              </div>
              <div class="flex flex-col gap-2">
                <p class="font-medium text-upbg">Histori Tes:</p>
                @foreach ($peserta->pivotTes as $pivotTes)
                  <div class="flex flex-col sm:flex-row sm:gap-2">
                    <p class="font-medium">{{ $pivotTes->tes->kode }}</p>
                    <p class="hidden sm:block">-</p>
                    <p>{{ $pivotTes?->hadir ? 'Hadir' : 'Tidak Hadir' }};</p>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </form>
@endif
