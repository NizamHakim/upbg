<div id="filter-user" class="mb-6">
  <form action="{{ route('user.index') }}" method="GET" class="filter-form flex flex-col">
    <div class="grid grid-cols-1 gap-y-2 sm:grid-cols-[auto_1fr] sm:gap-x-2 sm:gap-y-0">
      <button type="button" class="open-filter btn btn-gray row-span-1 row-start-2 text-sm shadow-none sm:col-auto sm:row-auto"><i class="fa-solid fa-filter mr-2"></i>Filter</button>
      <x-search-bar placeholder="Cari nama atau NIK user" name="search" value="{{ old('search') }}" />
    </div>

    <div class="filter-container">
      <div class="mb-8 flex flex-row items-center justify-between text-xl font-semibold text-gray-700 sm:hidden">
        <p>Filter User</p>
        <button type="button" class="close-filter btn-rounded btn-white border-none text-2xl">&times;</button>
      </div>
      <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-4 sm:gap-x-4">
        <div class="input-group sm:col-span-2">
          <p class="input-label">Role</p>
          <select name="role" class="tom-select filter-field">
            <option value="">Semua</option>
            @foreach ($roleOptions as $role)
              <option value="{{ $role->id }}" @selected(old('role') == $role->id)>{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-upbg-solid h-9 self-end text-sm sm:col-span-1 sm:col-start-4 sm:row-span-1 sm:row-start-1"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button>
        <button type="button" class="reset-filter btn btn-red-outline h-9 self-end text-sm sm:col-span-1 sm:col-start-3 sm:row-span-1 sm:row-start-1">Reset Filter</button>
      </div>
    </div>
  </form>
</div>
