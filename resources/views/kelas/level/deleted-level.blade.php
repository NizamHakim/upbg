@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Restore | Level Kelas</x-slot>
  <x-slot name="header"><a href="{{ route('level-kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Restore</x-slot>

  <section>
    <div class="flex flex-col">
      <div class="level-container paginated-table">
        @include('kelas.partials.level-table', ['levelList' => $levelList, 'deleted' => true])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="restore-level-modal">
      <x-slot name="title">Restore Level</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin merestore level <span class="nama-kode-level font-semibold">Level</span> ?</p>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Restore</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/level/deleted-level.js') }}"></script>
  @endPushOnce
</x-app-layout>
