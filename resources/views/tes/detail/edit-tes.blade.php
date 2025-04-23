<x-app-layout>
  <x-slot name="title">Edit | {{ $tes->kode }}</x-slot>
  <x-slot name="header"><a href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Edit Tes</x-slot>

  <section class="min-w-0 rounded-md bg-white p-6 shadow-sm">
    <form action="{{ route('tes.updateDetail', ['tesId' => $tes->id]) }}" class="edit-tes-form flex min-w-0 flex-col">
      @include('tes.partials.kode-tes-former', ['tipeOptions' => $tipeOptions, 'tes' => $tes])

      <hr class="my-5">

      @include('tes.partials.ruangan-tes-dynamic', ['ruanganOptions' => $ruanganOptions, 'tes' => $tes])

      <hr class="my-5">

      @include('tes.partials.pengawas-tes-dynamic', ['pengawasOptions' => $pengawasOptions, 'tes' => $tes])

      <hr class="my-5">

      <div class="flex flex-row justify-end gap-2">
        <a href="{{ route('tes.detail', ['tesId' => $tes->id]) }}" class="btn btn-white border-none shadow-none">Cancel</a>
        <button type="submit" class="btn btn-upbg-solid">Simpan</button>
      </div>
    </form>
  </section>
  @pushOnce('scripts')
    <script src="{{ asset('js/tes/detail/edit-tes.js') }}"></script>
  @endPushOnce
</x-app-layout>
