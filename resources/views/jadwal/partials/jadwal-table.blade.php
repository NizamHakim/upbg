<table class="min-w-full table-fixed">
  <thead class="sticky top-0 border-b border-gray-200 bg-gray-50">
    <tr>
      <th class="w-40">Hari</th>
      <th class="w-36">Jam</th>
      @foreach ($ruanganList as $ruangan)
        <th>{{ $ruangan->kode }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody class="divide-y divide-gray-200">
    @foreach ($jadwalList as $tanggal => $sesiList)
      @foreach ($sesiList as $sesi => $ruanganList)
        <tr class="divide-x">
          @if ($loop->first)
            <th rowspan="7" class="border-r">{{ $tanggal }}</th>
          @endif
          <th>{{ $sesi }}</th>
          @foreach ($ruanganList as $ruangan => $array)
            <td class="max-w-72">
              <div class="flex flex-col gap-2">
                @foreach ($array['kelas'] as $pertemuan)
                  <div class="jadwal-item kelas bg-upbg-light bg-opacity-20" data-route="{{ route('jadwal.kelas', ['pertemuanId' => $pertemuan->id]) }}">
                    <p class="truncate font-medium text-upbg">{{ $pertemuan->kelas->kode }}</p>
                    <p class="truncate">{{ $pertemuan->kelas->pengajar->pluck('nickname')->implode(' - ') }}</p>
                  </div>
                @endforeach
                @foreach ($array['tes'] as $tes)
                  <div class="jadwal-item tes bg-yellow-400 bg-opacity-20" data-route="{{ route('jadwal.tes', ['tesId' => $tes->id]) }}">
                    <p class="truncate font-medium text-yellow-700">{{ $tes->kode }}</p>
                    <p class="truncate">{{ $tes->pengawas->pluck('nickname')->implode(' - ') }}</p>
                  </div>
                @endforeach
              </div>
            </td>
          @endforeach
        </tr>
      @endforeach
      <tr>
        <td colspan="{{ count($ruanganList) + 2 }}" class="bg-black"></td>
      </tr>
    @endforeach
  </tbody>
</table>
