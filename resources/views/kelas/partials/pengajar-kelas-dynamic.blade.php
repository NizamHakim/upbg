@props(['pengajarOptions', 'kelas' => null])

<div id="pengajar-kelas-dynamic" class="flex flex-col gap-2">
  <p class="input-label">Pengajar</p>
  <div class="pengajar-container flex flex-col gap-3">
    @if ($kelas)
      @foreach ($kelas->pengajar as $pengajar)
        <div class="pengajar-item @if ($loop->first) mr-11 @endif grid grid-flow-col grid-cols-1">
          <div class="input-group">
            <select name="pengajar" class="tom-select">
              <option value="">Pilih pengajar</option>
              @foreach ($pengajarOptions as $pengajarOption)
                <option value="{{ $pengajarOption->id }}" @selected($pengajarOption->id == $pengajar->id)>{{ $pengajarOption->textFormat('option') }}</option>
              @endforeach
            </select>
          </div>
          @if (!$loop->first)
            <button type="button" class="delete-pengajar btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
          @endif
        </div>
      @endforeach
    @else
      <div class="pengajar-item mr-11 grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="pengajar" class="tom-select">
            <option value="">Pilih pengajar</option>
            @foreach ($pengajarOptions as $pengajarOption)
              <option value="{{ $pengajarOption->id }}">{{ $pengajarOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
      </div>
    @endif
    <template id="template-pengajar">
      <div class="pengajar-item grid grid-flow-col grid-cols-1">
        <div class="input-group">
          <select name="pengajar" class="tom-select">
            <option value="">Pilih pengajar</option>
            @foreach ($pengajarOptions as $pengajarOption)
              <option value="{{ $pengajarOption->id }}">{{ $pengajarOption->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <button type="button" class="delete-pengajar btn-rounded btn-white ml-2 text-red-600"><i class="fa-regular fa-trash-can"></i></button>
      </div>
    </template>
  </div>
  <div class="mt-1 flex w-full flex-row items-center justify-center gap-4">
    <button type="button" class="tambah-pengajar btn-rounded btn-white hover:bg-gray-200"><i class="fa-solid fa-plus text-lg"></i></button>
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/kelas/partials/pengajar-kelas-dynamic.js') }}"></script>
@endPushOnce
