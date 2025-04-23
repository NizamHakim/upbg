@props(['ruanganOptions', 'tes' => null])

<div id="ruangan-tes-dynamic" class="flex flex-col gap-2">
  <p class="input-label">Ruangan</p>
  <div class="ruangan-container flex flex-col gap-3">
    @if ($tes)
      @foreach ($tes->ruangan as $ruangan)
        <div class="ruangan-item @if ($loop->first) mr-11 @endif grid grid-flow-col grid-cols-1">
          <div class="input-group">
            <select name="ruangan" class="tom-select">
              <option value="">Pilih ruangan</option>
              @foreach ($ruanganOptions as $ruanganOption)
                <option value="{{ $ruanganOption->id }}" @selected($ruanganOption->id == $ruangan->id)>{{ $ruanganOption->textFormat('option') }}</option>
              @endforeach
            </select>
          </div>
          @if (!$loop->first)
            <button type="button" class="delete-ruangan btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
          @endif
        </div>
      @endforeach
    @else
      <div class="ruangan-item mr-11 grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="ruangan" class="tom-select">
            <option value="">Pilih ruangan</option>
            @foreach ($ruanganOptions as $ruanganOption)
              <option value="{{ $ruanganOption->id }}">{{ $ruanganOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
      </div>
    @endif

    <template id="template-ruangan">
      <div class="ruangan-item grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="ruangan" class="tom-select">
            <option value="">Pilih ruangan</option>
            @foreach ($ruanganOptions as $ruanganOption)
              <option value="{{ $ruanganOption->id }}">{{ $ruanganOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <button type="button" class="delete-ruangan btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
      </div>
    </template>
  </div>
  <div class="mt-1 flex w-full flex-row items-center justify-center gap-4">
    <button type="button" class="tambah-ruangan btn-rounded btn-white hover:bg-gray-200"><i class="fa-solid fa-plus text-lg"></i></button>
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/tes/partials/ruangan-tes-dynamic.js') }}"></script>
@endPushOnce
