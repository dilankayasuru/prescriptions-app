@props([
    'placeholder' => 'Search...',
    'name' => 'search',
    'id' => null,
    'value' => null,
    'oninput' => null,
    'class' => '',
])

@php
    $id = $id ?? $name;
    $inputClass = trim('block w-full pl-10 pr-4 py-2 border rounded-lg text-sm placeholder-gray-400 bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200 '. $class);
@endphp

<div class="relative max-w-md">
    <flux:icon.magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-gray-400" />

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="search"
        placeholder="{{ $placeholder }}"
        value="{{ $value ?? '' }}"
        class="{{ $inputClass }}"
        @if($oninput) oninput="{{ $oninput }}(this.value)" @endif
    />
</div>
