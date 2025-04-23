@props(['type', 'message'])

@php
  if ($type == 'success') {
      $class = 'notification-success';
      $icon = 'fa-regular fa-circle-check';
      $title = 'Success';
  } elseif ($type == 'error') {
      $class = 'notification-error';
      $icon = 'fa-regular fa-circle-xmark';
      $title = 'Error';
  }
@endphp

<div class="notification {{ $class }}">
  <div class="relative size-full">
    <button class="close-notification btn-rounded">&times;</button>
    <div class="flex items-center px-4 pb-3 pt-2">
      <i class="{{ $icon }} mr-4 text-lg"></i>
      <div class="flex flex-1 flex-col">
        <p class="text-base font-medium">{{ $title }}</p>
        <p class="text-gray-600">{{ $message }}</p>
      </div>
    </div>
    <div class="notification-progress"></div>
  </div>
</div>
