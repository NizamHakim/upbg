<x-app-layout>
  <x-slot name="title">Tambah Peserta</x-slot>
  <x-slot name="header"><a href="{{ route('kelas.daftar-peserta', ['kelasId' => $kelas->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Tambah Peserta</x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    <form action="{{ route('kelas.store-peserta', ['kelasId' => $kelas->id]) }}" class="tambah-peserta-form flex min-w-0 flex-col">

      @include('kelas.partials.file-peserta-kelas')

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('kelas.index') }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-green-solid">Tambah</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/detail/tambah-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
