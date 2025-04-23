<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head />

<body class="flex flex-col font-poppins text-sm text-gray-600">
  <x-topbar />
  <div class="flex h-[calc(100vh-4rem)] bg-gray-100">
    <main class="mx-auto h-full w-full px-8 pb-12 pt-8">
      {{ $slot }}
    </main>
  </div>

  @include('layouts.script')
  <script src="{{ asset('js/utils/form-control.js') }}"></script>
  @stack('scripts')
</body>

</html>
