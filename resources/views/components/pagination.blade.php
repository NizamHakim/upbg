<div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
  <p class="text-center text-sm text-gray-600 sm:text-left">
    Menampilkan
    @if ($paginator->firstItem())
      <span class="font-semibold">{{ $paginator->firstItem() }}</span>
      sampai
      <span class="font-semibold">{{ $paginator->lastItem() }}</span>
    @else
      <span class="font-semibold">{{ $paginator->count() }}</span>
    @endif
    dari
    <span class="font-semibold">{{ $paginator->total() }}</span> hasil pencarian
  </p>
  @if ($paginator->hasPages())
    <nav id="pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
      <div class="flex flex-1 items-center justify-center">
        <div>
          <div class="flex flex-wrap gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
              <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <span class="relative inline-flex size-9 cursor-default items-center justify-center rounded-full text-sm font-medium leading-5 text-gray-400" aria-hidden="true">
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </span>
              </span>
            @else
              <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link relative inline-flex size-9 items-center justify-center rounded-full text-sm font-medium leading-5 transition duration-150 ease-in-out hover:bg-gray-100" aria-label="{{ __('pagination.previous') }}">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </a>
            @endif

            @php
              $pagesToShow = [1, $paginator->currentPage() - 2, $paginator->currentPage() - 1, $paginator->currentPage() + 1, $paginator->currentPage() + 2, $paginator->lastPage()];
              $startDots = false;
              $endDots = false;
            @endphp

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
              {{-- Array Of Links --}}
              @if (is_array($element))
                @foreach ($element as $page => $url)
                  @if ($page == $paginator->currentPage())
                    <span aria-current="page">
                      <span class="relative -ml-px inline-flex size-9 cursor-default items-center justify-center rounded bg-upbg text-sm font-medium leading-5 text-white">{{ $page }}</span>
                    </span>
                  @elseif (in_array($page, $pagesToShow))
                    <a href="{{ $url }}" class="pagination-link relative -ml-px inline-flex size-9 items-center justify-center rounded text-sm font-medium leading-5 ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-100" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                      {{ $page }}
                    </a>
                  @else
                    @if ($page < $paginator->currentPage() && !$startDots)
                      @php $startDots = true; @endphp
                      <span aria-disabled="true">
                        <span class="relative -ml-px inline-flex size-9 cursor-default items-center justify-center text-sm font-medium leading-5">...</span>
                      </span>
                    @elseif ($page > $paginator->currentPage() && !$endDots)
                      @php $endDots = true; @endphp
                      <span aria-disabled="true">
                        <span class="relative -ml-px inline-flex size-9 cursor-default items-center justify-center text-sm font-medium leading-5">...</span>
                      </span>
                    @endif
                  @endif
                @endforeach
              @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
              <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link relative -ml-px inline-flex size-9 items-center justify-center rounded-full text-sm font-medium leading-5 transition duration-150 ease-in-out hover:bg-gray-100" aria-label="{{ __('pagination.next') }}">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </a>
            @else
              <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <span class="relative -ml-px inline-flex size-9 cursor-default items-center justify-center rounded-full text-sm font-medium leading-5 text-gray-400" aria-hidden="true">
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </span>
              </span>
            @endif
          </div>
        </div>
      </div>
    </nav>
    @pushOnce('scripts')
      <script src="{{ asset('js/components/ajax-pagination.js') }}"></script>
    @endPushOnce
  @endif
</div>
