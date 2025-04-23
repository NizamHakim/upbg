@use('App\Models\Kelas', 'Kelas')

@props(['programOptions', 'tipeOptions', 'levelOptions', 'ruanganOptions', 'statusOptions', 'sortbyOptions', 'pengajarOptions'])

<div id="filter-kelas" class="mb-6">
  <form action="{{ route('kelas.index') }}" method="GET" class="filter-form flex flex-col">
    <div class="grid grid-cols-1 gap-y-2 sm:grid-cols-[auto_1fr] sm:gap-x-2 sm:gap-y-0">
      <button type="button" class="open-filter btn btn-gray row-span-1 row-start-2 text-sm shadow-none sm:col-auto sm:row-auto"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
      <x-search-bar placeholder="Cari kode kelas" name="kode" value="{{ old('kode') }}" />
    </div>

    <div class="filter-container">
      <div class="mb-8 flex flex-row items-center justify-between text-xl font-semibold text-gray-700 sm:hidden">
        <p>Filter Kelas</p>
        <button type="button" class="close-filter btn-rounded btn-white border-none text-2xl">&times;</button>
      </div>
      <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4 lg:grid-cols-3 xl:grid-cols-4">
        <div class="input-group">
          <p class="input-label">Program Kelas</p>
          <select name="program" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($programOptions as $program)
              <option value="{{ $program->id }}" @selected(old('program') == $program->id)>{{ $program->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Tipe Kelas</p>
          <select name="tipe" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($tipeOptions as $tipe)
              <option value="{{ $tipe->id }}" @selected(old('tipe') == $tipe->id)>{{ $tipe->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Level Kelas</p>
          <select name="level" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($levelOptions as $level)
              <option value="{{ $level->id }}" @selected(old('level') == $level->id)>{{ $level->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Banyak Pertemuan</p>
          <input type="number" name="banyak-pertemuan" value="{{ old('banyak-pertemuan') }}" class="input-appearance filter-field" placeholder="Semua">
        </div>
        <div class="input-group">
          <p class="input-label">Tanggal Mulai</p>
          <input type="date" name="tanggal-mulai" value="{{ old('tanggal-mulai') }}" class="fp-datepicker input-appearance filter-field" data-plugin="month" placeholder="Semua">
        </div>
        <div class="input-group">
          <p class="input-label">Ruangan</p>
          <select name="ruangan" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($ruanganOptions as $ruangan)
              <option value="{{ $ruangan->id }}" @selected(old('ruangan') == $ruangan->id)>{{ $ruangan->kode }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Status</p>
          <select name="status" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($statusOptions as $status)
              <option value="{{ $status['value'] }}" @selected(old('status') == $status['value'])>{{ $status['text'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Sort By</p>
          <select name="order" class="tom-select filter-field">
            <option value="">Tanggal Dibuat (Terbaru)</option>
            @foreach ($sortbyOptions as $sortby)
              <option value="{{ $sortby['value'] }}" @selected(old('order') == $sortby['value'])>{{ $sortby['text'] }}</option>
            @endforeach
          </select>
        </div>
        @can('viewAny', Kelas::class)
          <div class="input-group sm:col-span-2 lg:col-span-1 xl:col-span-2">
            <p class="input-label">Pengajar</p>
            <select name="pengajar" class="tom-select filter-field">
              <option value="">Semua</option>
              @foreach ($pengajarOptions as $pengajar)
                <option value="{{ $pengajar->id }}" @selected(old('pengajar') == $pengajar->id)>{{ $pengajar->textFormat('option') }}</option>
              @endforeach
            </select>
          </div>
        @endcan
        <button type="submit" class="btn btn-upbg-solid h-9 self-end text-sm sm:col-span-1 sm:col-start-2 sm:row-span-1 sm:row-start-6 lg:col-span-1 lg:col-start-3 lg:row-span-1 lg:row-start-4 xl:col-span-1 xl:col-start-4 xl:row-span-1 xl:row-start-3"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button>
        <button type="button" class="reset-filter btn btn-red-outline h-9 self-end text-sm sm:col-span-1 sm:col-start-1 sm:row-span-1 sm:row-start-6 lg:col-span-1 lg:col-start-2 lg:row-span-1 lg:row-start-4 xl:col-span-1 xl:col-start-3 xl:row-span-1 xl:row-start-3">Reset Filter</button>
      </div>
    </div>
  </form>
</div>
