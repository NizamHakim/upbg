@use('App\Models\Tes', 'Tes')

@props(['tipeOptions', 'ruanganOptions', 'statusOptions', 'sortbyOptions', 'pengawasOptions'])

<div id="filter-tes" class="mb-6">
  <form action="{{ route('tes.index') }}" method="GET" class="filter-form flex flex-col">
    <div class="grid grid-cols-1 gap-y-2 sm:grid-cols-[auto_1fr] sm:gap-x-2 sm:gap-y-0">
      <button type="button" class="open-filter btn btn-gray row-span-1 row-start-2 text-sm shadow-none sm:col-auto sm:row-auto"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
      <x-search-bar placeholder="Cari kode tes" name="kode" value="{{ old('kode') }}" />
    </div>

    <div class="filter-container">
      <div class="mb-8 flex flex-row items-center justify-between text-xl font-semibold text-gray-700 sm:hidden">
        <p>Filter Tes</p>
        <button type="button" class="close-filter btn-rounded btn-white border-none text-2xl shadow-none">&times;</button>
      </div>
      <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4 lg:grid-cols-3 xl:grid-cols-4">
        <div class="input-group">
          <p class="input-label">Tipe Tes</p>
          <select name="tipe" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($tipeOptions as $tipe)
              <option value="{{ $tipe->id }}" @selected(old('tipe') == $tipe->id)>{{ $tipe->textFormat('option') }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Tanggal</p>
          <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="fp-datepicker input-appearance filter-field" data-plugin="month" placeholder="Semua">
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
              <option value="{{ $status->value }}" @selected(old('status') == $status->value)>{{ $status->text }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group">
          <p class="input-label">Sort By</p>
          <select name="order" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($sortbyOptions as $sortby)
              <option value="{{ $sortby->value }}" @selected(old('order') == $sortby->value)>{{ $sortby->text }}</option>
            @endforeach
          </select>
        </div>
        @can('viewAny', Tes::class)
          <div class="input-group xl:col-span-1">
            <p class="input-label">Pengawas</p>
            <select name="pengawas" class="tom-select filter-field">
              <option value="">Semua</option>
              @foreach ($pengawasOptions as $pengawas)
                <option value="{{ $pengawas->id }}" @selected(old('pengawas') == $pengawas->id)>{{ $pengawas->textFormat('option') }}</option>
              @endforeach
            </select>
          </div>
        @endcan
        <button type="submit" class="btn btn-upbg-solid h-9 self-end text-sm sm:col-span-1 sm:col-start-2 sm:row-span-1 sm:row-start-4 lg:col-span-1 lg:col-start-3 lg:row-span-1 lg:row-start-3 xl:col-span-1 xl:col-start-4 xl:row-span-1 xl:row-start-2"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button>
        <button type="button" class="reset-filter btn btn-red-outline h-9 self-end text-sm sm:col-span-1 sm:col-start-1 sm:row-span-1 sm:row-start-4 lg:col-span-1 lg:col-start-2 lg:row-span-1 lg:row-start-3 xl:col-span-1 xl:col-start-3 xl:row-span-1 xl:row-start-2">Reset Filter</button>
      </div>
    </div>
  </form>
</div>
