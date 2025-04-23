@auth
  <nav class="sticky top-0 z-10 flex h-16 bg-white shadow-sm">
    <div class="hidden h-full w-64 items-center justify-center lg:flex">
      <a href="/"><x-application-logo class="h-12 w-auto" /></a>
    </div>
    <div class="mx-auto flex h-full max-w-7xl flex-1 items-center justify-between px-4 py-1">
      <button class="open-sidenav size-6 lg:hidden">
        <svg class="h-6 w-6" stroke="#0866b7" fill="none" viewBox="0 0 24 24">
          <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <a href="/" class="translate-x-2"><x-application-logo class="h-12 w-auto lg:hidden" /></a>
      <div class="relative flex h-full items-center">
        <div class="hidden lg:mr-4 lg:flex lg:flex-col lg:items-end lg:gap-1">
          <p class="font-medium capitalize leading-none text-upbg">{{ Auth::user()->nickname }}</p>
          <p class="font-medium capitalize leading-none text-gray-600">{{ Auth::user()->currentRole?->name ?? '-' }}</p>
        </div>
        <button type="button" class="profile-menu-button flex size-12 items-center justify-center rounded-full transition hover:bg-gray-200">
          <img src="{{ Auth::user()->profile_picture }}" class="size-11 rounded-full">
        </button>
        <div class="profile-menu absolute right-0 top-full hidden w-72 -translate-y-1 flex-col divide-y rounded-md border bg-white opacity-0 shadow-lg transition-all">
          <a href="{{ route('akun.index') }}" class="flex items-center gap-4 rounded-t-md p-3 transition hover:bg-gray-100">
            <img src="{{ Auth::user()->profile_picture }}" class="size-12 rounded-full">
            <div class="flex flex-1 flex-col justify-center truncate">
              <p class="truncate text-sm font-semibold text-upbg">{{ Auth::user()->name }}</p>
              <p class="truncate text-sm text-gray-400">{{ Auth::user()->email }}</p>
            </div>
          </a>
          <button type="button" class="switch-role-button flex w-full items-center justify-between bg-white px-4 py-3 font-medium text-gray-600 hover:bg-gray-100">
            <span><i class="fa-solid fa-repeat mr-2"></i>Role : {{ Auth::user()->currentRole?->name ?? '-' }}</span>
            <i class="fa-solid fa-chevron-down transform text-xs transition"></i>
          </button>
          <form id="switch-role-dropdown" action="{{ route('switch-role') }}" method="POST" class="switch-role-dropdown hidden max-h-0 flex-col overflow-y-hidden font-medium text-gray-600 transition-all">
            @csrf
            @method('PATCH')
            @foreach (Auth::user()->roles as $role)
              <button type="submit" class="{{ Auth::user()->current_role_id == $role->id ? 'border-l-4 border-upbg' : '' }} px-4 py-3 text-left hover:bg-gray-100" name="role" value="{{ $role->id }}">{{ $role->name }}</button>
            @endforeach
          </form>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-b-md bg-white px-4 py-3 text-left font-medium text-red-600 hover:bg-gray-100">
              <span><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Logout</span>
            </button>
          </form>
        </div>
      </div>
    </div>
    @pushOnce('scripts')
      <script src="{{ asset('js/layouts/topbar.js') }}"></script>
    @endPushOnce
  </nav>
@endauth

@guest
  <nav class="z-10 flex h-16 bg-white shadow-sm">
    <div class="mx-auto flex h-full max-w-7xl flex-1 items-center justify-between px-4 py-1">
      <img src="{{ asset('images/logoGLC.png') }}" alt="Logo UPBG" class="h-12 w-auto">
      <div class="flex items-center">
        <a href="{{ route('jadwal') }}" @class(['top-nav-link', 'active' => request()->routeIs('jadwal')])>Jadwal</a>
        <a href="{{ route('login') }}" @class(['top-nav-link', 'active' => request()->routeIs('login')])>Login</a>
      </div>
    </div>
  </nav>
@endguest
