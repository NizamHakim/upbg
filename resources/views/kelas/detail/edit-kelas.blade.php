<x-app-layout>
  <x-slot name="title">Edit | {{ $kelas->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Edit Kelas</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $kelas->kode }}">
      <x-navigation.link href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.detail-pertemuan')">Daftar Pertemuan</x-navigation.link>
      <x-navigation.link href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" :active="request()->routeIs('kelas.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    <form action="{{ route('kelas.update', ['kelasId' => $kelas->id]) }}" class="update-kelas-form flex min-w-0 flex-col">
      @include('kelas.partials.kode-kelas-former', ['kelas' => $kelas, 'programOptions' => $programOptions, 'tipeOptions' => $tipeOptions, 'levelOptions' => $levelOptions, 'ruanganOptions' => $ruanganOptions])

      <hr class="my-5">

      @include('kelas.partials.jadwal-kelas-dynamic', ['kelas' => $kelas, 'hariOptions' => $hariOptions])

      <hr class="my-5">

      @include('kelas.partials.pengajar-kelas-dynamic', ['kelas' => $kelas, 'pengajarOptions' => $pengajarOptions])

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('kelas.detail-pertemuan', ['kelasId' => $kelas->id]) }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-upbg-solid">Simpan</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/detail/edit-kelas.js') }}"></script>
  @endPushOnce
</x-app-layout>
