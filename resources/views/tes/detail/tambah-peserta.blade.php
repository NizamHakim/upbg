<x-app-layout>
  <x-slot name="title">Tambah Peserta | {{ $tes->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('tes.daftar-peserta', ['tesId' => $tes->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Tambah Peserta</x-slot>

  <x-slot name="navigation">
    <x-navigation.group title="{{ $tes->kode }}">
      <x-navigation.link href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.detail')">Detail Tes</x-navigation.link>
      <x-navigation.link href="{{ route('tes.daftar-peserta', ['tesId' => $tes->id]) }}" :active="request()->routeIs('tes.daftar-peserta')">Daftar Peserta</x-navigation.link>
    </x-navigation.group>
  </x-slot>

  <section>
    <form action="{{ route('tes.store-peserta', ['tesId' => $tes->id]) }}" class="tambah-peserta-form flex min-w-0 flex-col">

      @include('tes.partials.file-peserta-tes')

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-green-solid">Tambah</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/tes/detail/tambah-peserta.js') }}"></script>
  @endPushOnce
</x-app-layout>
