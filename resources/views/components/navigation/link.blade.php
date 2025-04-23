@props(['active', 'href'])

<li @class(['side-nav-link', 'active' => $active])>
  <a href="{{ $href }}" class="block px-3 py-1">{{ $slot }}</a>
</li>
