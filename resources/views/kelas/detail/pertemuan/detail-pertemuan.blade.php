@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Pertemuan {{ $pertemuan->pertemuan_ke }}</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="Detail Pertemuan">
      <x-navigation.link href="{{ route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" :active="request()->routeIs('pertemuan.detail')">Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</x-navigation.link>
    </x-navigation.group>
    <x-navigation.group title="{{ $kelas->kode }}">
      <x-navigation.link href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.detail-pertemuan')">Daftar Pertemuan</x-navigation.link>
      <x-navigation.link href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section>
    <div class="flex flex-col gap-6">
      <div class="flex items-center justify-between">
        <h2 class="truncate text-xl font-semibold text-gray-700 md:text-2xl">Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</h2>
        <x-menu triggerSize="medium">
          @can('editPertemuan', Kelas::class)
            <a href="{{ route('pertemuan.edit', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="menu-item text-gray-700">Edit</a>
          @endcan
          @if (Auth::user()->currentRole->hasPermissionTo('reschedule-pertemuan') && !$pertemuan->terlaksana)
            <button type="button" class="menu-item reschedule-pertemuan text-gray-700">Reschedule</button>
            @pushOnce('modals')
              <x-modal id="reschedule-pertemuan-modal" route="{{ route('pertemuan.reschedule', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}">
                <x-slot name="title">Reschedule Pertemuan</x-slot>
                <div class="flex flex-col gap-4">
                  <div class="input-group">
                    <p class="input-label">Tanggal</p>
                    <input type="date" name="tanggal" data-current-value="{{ $pertemuan->tanggal }}" class="fp-datepicker input-appearance" placeholder="Tanggal">
                  </div>
                  <div class="grid grid-cols-2 gap-x-4">
                    <div class="input-group">
                      <p class="input-label">Waktu Mulai</p>
                      <input type="time" name="waktu-mulai" data-current-value="{{ $pertemuan->textFormat('iso-waktu-mulai') }}" class="fp-timepicker input-appearance" placeholder="Waktu Mulai">
                    </div>
                    <div class="input-group">
                      <p class="input-label">Waktu Selesai</p>
                      <input type="time" name="waktu-selesai" data-current-value="{{ $pertemuan->textFormat('iso-waktu-selesai') }}" class="fp-timepicker input-appearance" placeholder="Waktu Selesai">
                    </div>
                  </div>
                  <div class="input-group">
                    <p class="input-label">Ruangan</p>
                    <select name="ruangan" class="tom-select" data-current-value="{{ $pertemuan->ruangan_id }}">
                      <option value="">Pilih ruangan</option>
                      @foreach ($ruanganOptions as $ruangan)
                        <option value="{{ $ruangan->id }}">{{ $ruangan->textFormat('option') }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <x-slot name="control">
                  <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                  <button type="submit" class="submit-button btn btn-upbg-solid">Reschedule</button>
                </x-slot>
              </x-modal>
            @endPushOnce
          @endif
          <button type="button" class="menu-item delete-pertemuan font-medium text-red-600">Hapus</button>
          @pushOnce('modals')
            <x-modal id="delete-pertemuan-modal" route="{{ route('pertemuan.destroy', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}">
              <x-slot name="title">Hapus Pertemuan</x-slot>
              <div class="flex flex-col gap-4">
                <p class="text-gray-700">Apakah anda yakin ingin menghapus <span class="font-semibold">Pertemuan Ke - {{ $pertemuan->pertemuan_ke }}</span> dari kelas <span class="font-semibold">{{ $kelas->kode }}</span></p>
                <div class="alert alert-red flex flex-col gap-2">
                  <p class="font-semibold"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Peringatan</p>
                  <p>Semua data pertemuan ini akan dihapus permanen</p>
                </div>
              </div>
              <x-slot name="control">
                <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
                <button type="submit" class="submit-button btn btn-red-solid">Hapus</button>
              </x-slot>
            </x-modal>
          @endPushOnce
        </x-menu>
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Status:</h3>
        {!! $pertemuan->textFormat('status') !!}
      </div>
      <div class="flex flex-col">
        <h3 class="mb-1 font-semibold text-gray-700">Pengajar:</h3>
        <p class="text-gray-700">{{ $pertemuan->pengajar?->name ?? '-' }}</p>
      </div>
      <div class="flex flex-col gap-1">
        <h3 class="mb-1 font-semibold text-gray-700">Jadwal:</h3>
        <p class="text-gray-700"><i class="fa-solid fa-calendar-days mr-2"></i>{{ $pertemuan->textFormat('iso-tanggal') }}</p>
        <p class="text-gray-700"><i class="fa-regular fa-clock mr-2"></i>{{ $pertemuan->textFormat('iso-waktu-mulai') }} - {{ $pertemuan->textFormat('iso-waktu-selesai') }}</p>
        <p class="text-gray-700"><i class="fa-regular fa-building mr-2"></i>{{ $pertemuan->ruangan->kode }}</p>
      </div>
    </div>
  </section>

  <section class="my-8">
    <div id="topik-catatan" class="flex">
      <div class="topik-catatan-container flex flex-1 flex-col gap-4">
        @include('kelas.partials.topik-catatan', ['pertemuan' => $pertemuan])
      </div>
      <div class="h-fit">
        <x-menu triggerSize="medium">
          <button type="button" class="menu-item edit-topik-catatan">Edit</button>
        </x-menu>
        @pushOnce('modals')
          <x-modal id="edit-topik-catatan-modal" route="{{ route('pertemuan.update-topik-catatan', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}">
            <x-slot name="title">Topik dan Catatan</x-slot>
            <div class="flex flex-col gap-4">
              <div class="input-group">
                <p class="input-label">Topik</p>
                <textarea name="topik" placeholder="Topik bahasan pertemuan ini" class="input-appearance h-24 resize-none rounded-md p-2"></textarea>
              </div>
              <div class="input-group">
                <p class="input-label">Catatan</p>
                <textarea name="catatan" placeholder="Catatan untuk admin" class="input-appearance h-24 resize-none rounded-md p-2"></textarea>
              </div>
            </div>
            <x-slot name="control">
              <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
              <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
            </x-slot>
          </x-modal>
        @endPushOnce
      </div>
    </div>
  </section>

  <section>
    @if ($pertemuan->status() == 'terlaksana')
      {{-- show presensi list --}}
      <div id="daftar-presensi" class="flex flex-col gap-6">
        <div class="presensi-control flex flex-col gap-6 md:flex-row md:justify-between">
          @include('kelas.partials.presensi-control', ['kelas' => $kelas, 'pertemuan' => $pertemuan])
        </div>
        <div class="presensi-container">
          @include('kelas.partials.presensi-table', ['kelas' => $kelas, 'pertemuan' => $pertemuan])
        </div>
      </div>
    @elseif ($pertemuan->status() == 'belum-terlaksana')
      {{-- show countdown and start button --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-center text-gray-600">Mulai pertemuan untuk membuat daftar kehadiran</p>
        <button disabled type="button" class="mulai-pertemuan btn btn-upbg-solid disabled:opacity-70 disabled:hover:bg-upbg">Mulai Pertemuan</button>
        <p class="countdown-label text-center text-gray-600">Pertemuan dapat dimulai dalam,<br><span data-waktu-mulai="{{ $pertemuan->waktu_mulai }}" class="countdown font-semibold text-upbg">0d 0h 0m 0s</span></p>
      </div>
    @elseif ($pertemuan->status() == 'sedang-berlangsung')
      {{-- show start button --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-center text-gray-600">Mulai pertemuan untuk membuat daftar kehadiran</p>
        <button type="button" class="mulai-pertemuan btn btn-upbg-solid disabled:opacity-70 disabled:hover:bg-upbg">Mulai Pertemuan</button>
      </div>
      @pushOnce('modals')
        <x-modal id="mulai-pertemuan-modal" route="{{ route('pertemuan.mulai', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}">
          <x-slot name="title">Mulai Pertemuan</x-slot>
          <div class="flex flex-col">
            <div class="input-group">
              <p class="input-label">Pengajar</p>
              <select name="pengajar" class="tom-select">
                <option value="">Pilih pengajar</option>
                @foreach ($kelas->pengajar as $pengajar)
                  <option value="{{ $pengajar->id }}">{{ $pengajar->textFormat('option') }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <x-slot name="control">
            <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
            <button type="submit" class="submit-button btn btn-upbg-solid">Mulai</button>
          </x-slot>
        </x-modal>
      @endPushOnce
    @elseif ($pertemuan->status() == 'tidak-terlaksana')
      {{-- show notes for tidak terlaksana --}}
      <div id="notice-presensi" class="flex flex-col items-center gap-4">
        <p class="text-base font-semibold text-red-600">Pertemuan telah selesai</p>
        <p class="text-center text-gray-600">Silahkan <span class="font-semibold">Reschedule</span> pertemuan atau tambahkan <span class="font-semibold">Catatan</span> alasan pertemuan tidak terlaksana untuk admin</p>
      </div>
    @endif
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/utils/countdown.js') }}"></script>
    <script src="{{ asset('js/kelas/detail/pertemuan/detail-pertemuan.js') }}"></script>
  @endPushOnce
</x-app-layout>
