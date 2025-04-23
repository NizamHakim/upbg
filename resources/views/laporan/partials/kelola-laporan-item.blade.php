@props(['key', 'value'])

<div class="flex cursor-pointer flex-col rounded-md border p-2 transition hover:shadow">
  <p class="item-key font-medium">{{ $key }}</p>
  <p class="item-value">{{ $value }}</p>
</div>
