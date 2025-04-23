@props(['programOptions', 'tipeOptions', 'levelOptions', 'ruanganOptions', 'kelas' => null])

<div id="kode-kelas-former">
  <div class="input-group col-span-full">
    <p class="input-label">Kode Kelas</p>
    <input name="kode-kelas" value="{{ $kelas?->kode }}" readonly type="text" placeholder="Isi kolom dibawah untuk membuat kode kelas" class="input-appearance input-readonly truncate font-medium">
  </div>

  <hr class="my-5">

  <div class="grid grid-cols-6 gap-x-3 gap-y-4">
    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Program Kelas</p>
      <select name="program" class="tom-select">
        <option value="">Program Kelas</option>
        @foreach ($programOptions as $program)
          <option value="{{ $program->id }}" @selected($kelas?->program_id == $program->id)>{{ $program->textFormat('option') }}</option>
        @endforeach
      </select>
    </div>

    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Tipe Kelas</p>
      <select name="tipe" class="tom-select">
        <option value="">Tipe Kelas</option>
        @foreach ($tipeOptions as $tipe)
          <option value="{{ $tipe->id }}" @selected($kelas?->tipe_id == $tipe->id)>{{ $tipe->textFormat('option') }}</option>
        @endforeach
      </select>
    </div>

    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Nomor Kelas</p>
      <input type="number" name="nomor" value="{{ $kelas?->nomor }}" class="input-appearance" placeholder="Nomor kelas">
    </div>

    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Level Kelas</p>
      <select name="level" class="tom-select">
        <option value="">Level</option>
        @foreach ($levelOptions as $level)
          <option value="{{ $level->id }}" @selected($kelas?->level_id == $level->id)>{{ $level->textFormat('option') }}</option>
        @endforeach
      </select>
    </div>

    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Banyak Pertemuan</p>
      <input type="number" name="banyak-pertemuan" value="{{ $kelas?->banyak_pertemuan }}" class="input-appearance" placeholder="Banyak Pertemuan">
    </div>

    <div class="input-group kode-former col-span-full md:col-span-3 xl:col-span-2">
      <p class="input-label">Tanggal Mulai</p>
      <input type="date" name="tanggal-mulai" value="{{ $kelas?->tanggal_mulai }}" class="fp-datepicker input-appearance" placeholder="Tanggal Mulai">
    </div>
  </div>
</div>

<div class="mt-4 flex flex-col gap-4">
  <div class="input-group">
    <p class="input-label">Ruangan</p>
    <select name="ruangan" class="tom-select filter-field">
      <option value="">Pilih ruangan</option>
      @foreach ($ruanganOptions as $ruangan)
        <option value="{{ $ruangan->id }}" @selected($kelas?->ruangan_id == $ruangan->id)>{{ $ruangan->textFormat('option') }}</option>
      @endforeach
    </select>
  </div>

  <div class="input-group">
    <p class="input-label">Whatsapp Group Link (opsional)</p>
    <input type="text" name="group-link" value="{{ $kelas?->group_link }}" class="input-appearance" placeholder="https://chat.whatsapp.com/">
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/kelas/partials/kode-kelas-former.js') }}"></script>
@endPushOnce
