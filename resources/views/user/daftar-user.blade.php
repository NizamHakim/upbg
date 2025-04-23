<x-app-layout>
  <x-slot name="title">Daftar User</x-slot>
  <x-slot name="header">Daftar User</x-slot>

  <section>
    <div class="flex flex-col">
      <a href="{{ route('user.create') }}" class="btn btn-green-solid mb-4 self-end"><i class="fa-solid fa-plus mr-2"></i>Tambah User</a>

      @include('user.partials.filter-user', [
          'roleOptions' => $roleOptions,
      ])

      <div class="user-container">
        @include('user.partials.user-table', ['userList' => $userList])
      </div>
    </div>
  </section>

  @pushOnce('scripts')
    <script src="{{ asset('js/user/daftar-user.js') }}"></script>
  @endPushOnce
</x-app-layout>
