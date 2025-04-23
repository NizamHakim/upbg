<x-app-layout>
  <x-slot name="title">Pertemuan {{ $pertemuan->pertemuan_ke }}</x-slot>
  <x-slot name="header"><a href="{{ route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Edit Pertemuan</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="Detail Pertemuan">
      <x-navigation.link href="{{ route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" :active="request()->routeIs('pertemuan.detail')">Pertemuan ke - {{ $pertemuan->pertemuan_ke }}</x-navigation.link>
    </x-navigation.group>
    <x-navigation.group title="{{ $kelas->kode }}">
      <x-navigation.link href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.detail-pertemuan')">Daftar Pertemuan</x-navigation.link>
      <x-navigation.link href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    <form id="edit-pertemuan-form" action="{{ route('pertemuan.update', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="grid grid-cols-2 gap-4">
      <div class="input-group col-span-2">
        <p class="input-label">Status</p>
        <select name="terlaksana" class="tom-select">
          <option value="">Pilih status</option>
          <option value="0" @selected($pertemuan->terlaksana == 0)>Tidak Terlaksana</option>
          <option value="1" @selected($pertemuan->terlaksana == 1)>Terlaksana</option>
        </select>
      </div>
      <div class="input-group col-span-2">
        <p class="input-label">Pengajar</p>
        <select name="pengajar" class="tom-select">
          <option value="">Pilih pengajar</option>
          @foreach ($kelas->pengajar as $pengajar)
            <option value="{{ $pengajar->id }}" @selected($pertemuan->pengajar?->id == $pengajar->id)>{{ $pengajar->textFormat('option') }}</option>
          @endforeach
        </select>
      </div>
      <div class="input-group col-span-2">
        <p class="input-label">Tanggal</p>
        <input type="date" name="tanggal" value="{{ $pertemuan->tanggal }}" class="fp-datepicker input-appearance" placeholder="Tanggal">
      </div>
      <div class="input-group">
        <p class="input-label">Waktu Mulai</p>
        <input type="time" name="waktu-mulai" value="{{ $pertemuan->textFormat('iso-waktu-mulai') }}" class="fp-timepicker input-appearance" placeholder="Waktu Mulai">
      </div>
      <div class="input-group">
        <p class="input-label">Waktu Selesai</p>
        <input type="time" name="waktu-selesai" value="{{ $pertemuan->textFormat('iso-waktu-selesai') }}" class="fp-timepicker input-appearance" placeholder="Waktu Selesai">
      </div>
      <div class="input-group col-span-2">
        <p class="input-label">Ruangan</p>
        <select name="ruangan" class="tom-select">
          <option value="">Pilih ruangan</option>
          @foreach ($ruanganOptions as $ruangan)
            <option value="{{ $ruangan->id }}" @selected($pertemuan->ruangan->id == $ruangan->id)>{{ $ruangan->textFormat('option') }}</option>
          @endforeach
        </select>
      </div>
      <hr class="col-span-2 my-4">
      <div class="col-span-2 flex flex-row items-center justify-end gap-4">
        <a href="{{ route('pertemuan.detail', ['kelasId' => $kelas->id, 'pertemuanId' => $pertemuan->id]) }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-upbg-solid">Simpan</button>
      </div>
    </form>
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/detail/pertemuan/edit-pertemuan.js') }}"></script>
  @endPushOnce
</x-app-layout>
