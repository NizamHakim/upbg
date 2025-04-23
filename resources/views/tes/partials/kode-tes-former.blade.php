@props(['tipeOptions', 'tes' => null])

<div id="kode-tes-former">
  <div class="input-group col-span-full">
    <p class="input-label">Kode Tes</p>
    <input name="kode-tes" value="{{ $tes?->kode }}" readonly type="text" placeholder="Isi kolom dibawah untuk membuat kode tes" class="input-appearance input-readonly truncate font-medium">
  </div>

  <hr class="my-5">

  <div class="grid grid-cols-6 gap-x-3 gap-y-4">
    <div class="input-group kode-former col-span-full md:col-span-2">
      <p class="input-label">Tipe Tes</p>
      <select name="tipe" class="tom-select">
        <option value="">Tipe tes</option>
        @foreach ($tipeOptions as $tipe)
          <option data-kode="{{ $tipe->kode }}" value="{{ $tipe->id }}" @selected($tes?->tipe->id == $tipe->id)>{{ $tipe->textFormat('option') }}</option>
        @endforeach
      </select>
    </div>

    <div class="input-group kode-former col-span-full md:col-span-4">
      <p class="input-label">Nama Tes</p>
      <input type="text" name="nama" value="{{ $tes?->nama }}" class="input-appearance" placeholder="Nama tes" autocomplete="off">
    </div>

    <div class="input-group kode-former col-span-full md:col-span-2">
      <p class="input-label">Tanggal</p>
      <input type="date" name="tanggal" value="{{ $tes?->tanggal }}" class="fp-datepicker input-appearance" placeholder="Tanggal">
    </div>
    <div class="input-group col-span-3 md:col-span-2">
      <p class="input-label">Waktu Mulai</p>
      <input type="time" name="waktu-mulai" value="{{ $tes?->textFormat('iso-waktu-mulai') }}" class="fp-timepicker input-appearance" placeholder="Waktu mulai">
    </div>
    <div class="input-group col-span-3 md:col-span-2">
      <p class="input-label">Waktu Selesai</p>
      <input type="time" name="waktu-selesai" value="{{ $tes?->textFormat('iso-waktu-selesai') }}" class="fp-timepicker input-appearance" placeholder="Waktu selesai">
    </div>
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/tes/partials/kode-tes-former.js') }}"></script>
@endPushOnce
