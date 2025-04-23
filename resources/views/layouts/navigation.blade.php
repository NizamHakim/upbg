@use('App\Models\Tes', 'Tes')
@use('App\Models\Kelas', 'Kelas')
@use('App\Models\Ruangan', 'Ruangan')
@use('App\Models\User', 'User')
@use('App\Models\ConfigLaporan', 'ConfigLaporan')
@use('App\Models\Peserta', 'Peserta')

<nav class="sidenav hidden lg:block">
  <div class="sidenav-content">
    <div class="flex h-16 items-center justify-center lg:hidden">
      <a href="{{ route('dashboard') }}"><x-application-logo class="h-12 w-auto" /></a>
    </div>
    <hr class="lg:hidden">
    <div class="scrollbar flex-1 overflow-y-auto px-3 py-4">
      {{ $slot }}
      <x-navigation.group title="Home">
        <x-navigation.link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">Dashboard</x-navigation.link>
        @can('viewList', Kelas::class)
          <x-navigation.link href="{{ route('kelas.index') }}" :active="request()->routeIs('kelas.index')">Daftar Kelas</x-navigation.link>
        @endcan
        @can('viewList', Tes::class)
          <x-navigation.link href="{{ route('tes.index') }}" :active="request()->routeIs('tes.index')">Daftar Tes</x-navigation.link>
        @endcan
      </x-navigation.group>

      @if (Auth::user()->can('buatLaporanKelas', Kelas::class) || Auth::user()->can('buatLaporanTes', Tes::class) || Auth::user()->can('buatLaporanPeserta', Peserta::class))
        <x-navigation.group title="Laporan">
          @can('buatLaporanKelas', Kelas::class)
            <x-navigation.link href="{{ route('laporan.kelas') }}" :active="request()->routeIs('laporan.kelas')">Laporan Kelas</x-navigation.link>
          @endcan
          @can('buatLaporanTes', Tes::class)
            <x-navigation.link href="{{ route('laporan.tes') }}" :active="request()->routeIs('laporan.tes')">Laporan Tes</x-navigation.link>
          @endcan
          @can('buatLaporanPeserta', Peserta::class)
            <x-navigation.link href="{{ route('laporan.departemen') }}" :active="request()->routeIs('laporan.departemen')">Laporan Departemen</x-navigation.link>
          @endcan
        </x-navigation.group>
      @endif

      @can('kelolaKelas', Kelas::class)
        <x-navigation.group title="Kelola Kelas">
          <x-navigation.link href="{{ route('program-kelas.index') }}" :active="request()->routeIs('program-kelas.index')">Program</x-navigation.link>
          <x-navigation.link href="{{ route('tipe-kelas.index') }}" :active="request()->routeIs('tipe-kelas.index')">Tipe</x-navigation.link>
          <x-navigation.link href="{{ route('level-kelas.index') }}" :active="request()->routeIs('level-kelas.index')">Level</x-navigation.link>
          <x-navigation.link href="{{ route('kategori-kelas.index') }}" :active="request()->routeIs('kategori-kelas.index')">Kategori</x-navigation.link>
        </x-navigation.group>
      @endcan

      @can('kelolaTes', Tes::class)
        <x-navigation.group title="Kelola Tes">
          <x-navigation.link href="{{ route('tipe-tes.index') }}" :active="request()->routeIs('tipe-tes.index')">Tipe</x-navigation.link>
          <x-navigation.link href="{{ route('kategori-tes.index') }}" :active="request()->routeIs('kategori-tes.index')">Kategori</x-navigation.link>
        </x-navigation.group>
      @endcan

      @can('kelolaRuangan', Ruangan::class)
        <x-navigation.group title="Kelola Ruangan">
          <x-navigation.link href="{{ route('ruangan.index') }}" :active="request()->routeIs('ruangan.index')">Ruangan</x-navigation.link>
        </x-navigation.group>
      @endcan

      @can('kelolaLaporan', ConfigLaporan::class)
        <x-navigation.group title="Kelola Laporan">
          <x-navigation.link href="{{ route('laporan.kelola') }}" :active="request()->routeIs('laporan.kelola')">Kelola Laporan</x-navigation.link>
        </x-navigation.group>
      @endcan

      @can('lihatUser', User::class)
        <x-navigation.group title="Kelola User">
          <x-navigation.link href="{{ route('user.index') }}" :active="request()->routeIs('user.index')">Daftar User</x-navigation.link>
          <x-navigation.link href="{{ route('peserta.index') }}" :active="request()->routeIs('peserta.index')">Daftar Peserta</x-navigation.link>
        </x-navigation.group>
      @endcan

      <x-navigation.group title="Kelola Akun">
        <x-navigation.link href="{{ route('akun.index') }}" :active="request()->routeIs('akun.index')">Kelola Akun</x-navigation.link>
      </x-navigation.group>
    </div>
  </div>
</nav>
