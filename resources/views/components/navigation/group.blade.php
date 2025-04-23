@props(['title'])

<div class="my-2">
  <h1 class="mb-1 truncate text-base font-semibold text-gray-600">{{ $title }}</h1>
  <ul class="flex flex-col gap-1">
    {{ $slot }}
  </ul>
</div>
<hr class="last-of-type:hidden">
