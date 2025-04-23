@props(['triggerSize' => 'small'])
@php
  $size = '';
  $font = '';
  switch ($triggerSize) {
      case 'small':
          $size = 'size-8';
          $font = 'text-sm';
          break;
      case 'medium':
          $size = 'size-10';
          $font = 'text-base';
          break;
      case 'large':
          $size = 'size-12';
          $font = 'text-lg';
          break;
  }
@endphp

<div class="relative ml-auto h-fit">
  <button type="button" class="menu-trigger btn-rounded {{ $size }} border-none bg-transparent shadow-none hover:bg-gray-200"><i class="fa-solid fa-ellipsis-vertical {{ $font }}"></i></button>
  <div class="menu absolute right-0 top-full z-10 hidden min-w-24 max-w-52 translate-y-1 flex-col divide-y rounded border border-gray-200 bg-white text-left shadow-sm">
    {{ $slot }}
  </div>
</div>
@pushOnce('scripts')
  <script src="{{ asset('js/components/menu.js') }}"></script>
@endPushOnce
