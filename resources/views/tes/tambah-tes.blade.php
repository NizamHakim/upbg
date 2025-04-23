<x-app-layout>
  <x-slot name="title">Tambah Tes</x-slot>
  <x-slot name="header"><a href="{{ route('tes.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Tambah Tes</x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    <form action="{{ route('tes.store') }}" class="tambah-tes-form flex min-w-0 flex-col">
      @include('tes.partials.kode-tes-former', ['tipeOptions' => $tipeOptions])

      <hr class="my-5">

      @include('tes.partials.ruangan-tes-dynamic', ['ruanganOptions' => $ruanganOptions])

      <hr class="my-5">

      @include('tes.partials.pengawas-tes-dynamic', ['pengawasOptions' => $pengawasOptions])

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('tes.index') }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-green-solid">Tambah</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/tes/tambah-tes.js') }}"></script>
  @endPushOnce
</x-app-layout>
