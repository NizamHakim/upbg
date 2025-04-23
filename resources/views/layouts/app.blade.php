<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head title="{{ $title }}" />

<body class="scrollbar flex min-h-screen flex-col overflow-y-scroll scroll-smooth font-poppins text-sm text-gray-600">
  <div class="notification-rail">
    @session('notification')
      <x-notification type="{{ $value['type'] }}" message="{{ $value['message'] }}" />
    @endsession
  </div>
  <x-topbar />
  <div class="flex flex-1">
    <x-navigation>
      {{ $navigation ?? '' }}
    </x-navigation>
    <div class="flex min-w-0 flex-1 bg-gray-100">
      <main class="mx-auto w-full min-w-0 max-w-7xl px-8 pb-20 pt-6">
        <h1 class="page-header">{{ $header }}</h1>
        {{ $slot }}
      </main>
    </div>
  </div>
  @stack('modals')
  @include('layouts.script')
  @stack('scripts')
</body>

</html>
