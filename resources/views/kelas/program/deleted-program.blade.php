@use('App\Models\Kelas', 'Kelas')

<x-app-layout>
  <x-slot name="title">Restore | Program Kelas</x-slot>
  <x-slot name="header"><a href="{{ route('program-kelas.index') }}" class="navigation-back"><i class="fa-solid fa-arrow-left"></i></a>Restore</x-slot>

  <section>
    <div class="flex flex-col">
      <div class="program-container paginated-table">
        @include('kelas.partials.program-table', ['programList' => $programList, 'deleted' => true])
      </div>
    </div>
  </section>

  @pushOnce('modals')
    <x-modal id="restore-program-modal">
      <x-slot name="title">Restore Program</x-slot>
      <div class="flex flex-col gap-4">
        <p>Apakah anda yakin ingin merestore program <span class="nama-kode-program font-semibold">Program</span> ?</p>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Restore</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/kelas/program/deleted-program.js') }}"></script>
  @endPushOnce
</x-app-layout>
