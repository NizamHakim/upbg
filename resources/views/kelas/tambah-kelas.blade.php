<x-app-layout>
  <x-slot name="title">Tambah Kelas</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Tambah Kelas</x-slot>

  <section>
    <form action="{{ route('kelas.store') }}" class="tambah-kelas-form flex min-w-0 flex-col">
      @include('kelas.partials.kode-kelas-former', ['programOptions' => $programOptions, 'tipeOptions' => $tipeOptions, 'levelOptions' => $levelOptions, 'ruanganOptions' => $ruanganOptions])

      <hr class="my-5">

      @include('kelas.partials.jadwal-kelas-dynamic', ['hariOptions' => $hariOptions])

      <hr class="my-5">

      @include('kelas.partials.pengajar-kelas-dynamic', ['pengajarOptions' => $pengajarOptions])

      <hr class="my-5">

      @include('kelas.partials.file-peserta-kelas', ['optional' => true])

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('kelas.index') }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-green-solid">Tambah</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/tambah-kelas.js') }}"></script>
  @endPushOnce
</x-app-layout>
