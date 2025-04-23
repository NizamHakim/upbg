@props(['id', 'route' => ''])

<div id="{{ $id }}" class="modal fixed inset-0 z-20 hidden select-none items-center justify-center bg-black bg-opacity-25 opacity-0 transition duration-300">
  <div class="modal-content mx-4 flex w-full max-w-2xl -translate-y-5 flex-col rounded-md bg-white transition duration-200">
    <form action="{{ $route }}" class="divide-y">
      <div class="flex items-center justify-between px-4 py-2">
        <h2 class="text-base font-semibold">{{ $title }}</h2>
        <button type="button" class="cancel-button btn-rounded btn-white border-none text-2xl shadow-none">&times;</button>
      </div>
      <div class="bg-white p-4">
        {{ $slot }}
      </div>
      <div class="flex justify-end gap-4 px-4 py-3">
        {{ $control }}
      </div>
    </form>
  </div>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/components/modal.js') }}"></script>
@endPushOnce
