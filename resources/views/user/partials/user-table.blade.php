@props(['userList', 'deleted' => false])

<div class="table-wrapper">
  <div class="table-header grid-cols-12">
    <p class="col-span-2 text-center sm:col-span-1">No</p>
    <p class="col-span-10 sm:col-span-11">User</p>
  </div>
  <div class="table-content">
    @if ($userList->isEmpty())
      <p class="empty-query p-4">Tidak ada data yang cocok</p>
    @else
      @foreach ($userList as $user)
        <div class="table-item grid-cols-12">
          <p class="col-span-2 text-center sm:col-span-1">{{ $loop->iteration + ($userList->currentPage() - 1) * $userList->perPage() }}</p>
          <div class="user-item contents">
            <img src="{{ $user->profile_picture }}" alt="foto-user" class="col-span-3 w-16 rounded sm:col-span-2 lg:col-span-1">
            <div class="col-span-6 sm:col-span-7 lg:col-span-8">
              <a href="{{ route('user.detail', ['userId' => $user->id]) }}" class="link font-semibold">{{ $user->name }}</a>
              <p>{{ $user->nik }}</p>
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</div>
<div class="flex flex-col">
  {{ $userList->links() }}
  {{-- @if (!$deleted)
    <a href="{{ route('user-kelas.deleted') }}" class="mt-2 w-fit self-center underline decoration-gray-600 transition hover:text-upbg-light hover:decoration-upbg-light sm:mt-0 sm:self-start">restore</a>
  @endif --}}
</div>
