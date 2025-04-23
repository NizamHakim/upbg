@props(['pengawasOptions', 'tes' => null])

<div id="pengawas-tes-dynamic" class="flex flex-col gap-2">
  <p class="input-label">Pengawas</p>
  <div class="pengawas-container flex flex-col gap-3">
    @if ($tes)
      @foreach ($tes->pengawas as $pengawas)
        <div class="pengawas-item @if ($loop->first) mr-11 @endif grid grid-flow-col grid-cols-1">
          <div class="input-group">
            <select name="pengawas" class="tom-select">
              <option value="">Pilih pengawas</option>
              @foreach ($pengawasOptions as $pengawasOption)
                <option value="{{ $pengawasOption->id }}" @selected($pengawasOption->id == $pengawas->id)>{{ $pengawasOption->textFormat('option') }}</option>
              @endforeach
            </select>
          </div>
          @if (!$loop->first)
            <button type="button" class="delete-pengawas btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
          @endif
        </div>
      @endforeach
    @else
      <div class="pengawas-item mr-11 grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="pengawas" class="tom-select">
            <option value="">Pilih pengawas</option>
            @foreach ($pengawasOptions as $pengawasOption)
              <option value="{{ $pengawasOption->id }}">{{ $pengawasOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
      </div>
    @endif

    <template id="template-pengawas">
      <div class="pengawas-item grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="pengawas" class="tom-select">
            <option value="">Pilih pengawas</option>
            @foreach ($pengawasOptions as $pengawasOption)
              <option value="{{ $pengawasOption->id }}">{{ $pengawasOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <button type="button" class="delete-pengawas btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
      </div>
    </template>
  </div>
  <div class="mt-1 flex w-full flex-row items-center justify-center gap-4">
    <button type="button" class="tambah-pengawas btn-rounded btn-white hover:bg-gray-200"><i class="fa-solid fa-plus text-lg"></i></button>
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/tes/partials/pengawas-tes-dynamic.js') }}"></script>
@endPushOnce
