@props([
    'name' => 'search',
    'placeholder' => 'Search...',
    'value' => '',
])

<div class="search-bar">
  <i class="fa-solid fa-magnifying-glass search-icon"></i>
  <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="input-appearance search-input peer">
  <button type="submit" class="submit-button active:flow-root peer-focus:flow-root"><i class="fa-solid fa-arrow-right"></i></button>
</div>
