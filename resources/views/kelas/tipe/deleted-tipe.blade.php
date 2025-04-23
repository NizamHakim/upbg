@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Restore | Tipe Kelas</x-slot>
  <x-slot name="header"><a href="{{ route('tipe-kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Restore</x-slot>

  <section>
    <div class="flex flex-col">
      <div class="tipe-container paginated-table">
        @include('kelas.partials.tipe-table', ['tipeList' => $tipeList, 'deleted' => true])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="restore-tipe-modal">
      <x-slot name="title">Restore Tipe</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin merestore tipe <span class="nama-kode-tipe font-semibold">Tipe</span> ?</p>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Restore</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/tipe/deleted-tipe.js') }}"></script>
  @endPushOnce
</x-app-layout>
