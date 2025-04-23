<x-app-layout>
  <x-slot name="title">Kelola Laporan</x-slot>
  <x-slot name="header">Kelola Laporan</x-slot>

  <div id="kelola-laporan-container" class="flex flex-col gap-4">
    @foreach ($configList as $config)
      <section class="flex flex-col gap-2">
        <p class="text-base font-semibold text-upbg">{{ $config->group }}</p>
        @foreach ($config->data as $key => $value)
          <div class="kelola-laporan-item" data-route="{{ route('laporan.update-config', ['configId' => $config->id, 'itemKey' => $key]) }}">
            @include('laporan.partials.kelola-laporan-item', ['key' => $key, 'value' => $value])
          </div>
        @endforeach
      </section>
    @endforeach
  </div>

  @pushOnce('modals')
    <x-modal id="update-laporan-modal">
      <x-slot name="title">Edit Laporan</x-slot>
      <div class="flex flex-col gap-4">
        <div class="input-group">
          <p class="item-key input-label">Key Laporan</p>
          <input type="text" class="input-appearance" name="item-value" placeholder="item-key">
        </div>
      </div>
      <x-slot name="control">
        <button type="button" class="cancel-button btn btn-white border-none shadow-none">Cancel</button>
        <button type="submit" class="submit-button btn btn-upbg-solid">Simpan</button>
      </x-slot>
    </x-modal>
  @endPushOnce

  @pushOnce('scripts')
    <script src="{{ asset('js/laporan/kelola-laporan.js') }}"></script>
  @endPushOnce
</x-app-layout>
