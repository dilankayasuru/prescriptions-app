@props([
    'name',
    'id' => null,
    'options' => [],
    'placeholder' => null,
    'value' => null,
    'icon' => 'chevrons-up-down',
    'class' => '',
])

@php
    $id = $id ?? $name;
    $normalized = [];
    foreach ($options as $k => $v) {
        if (is_int($k)) {
            $normalized[$v] = $v;
        } else {
            $normalized[$k] = $v;
        }
    }

    $selectClass = trim('appearance-none px-4 py-2 pr-10 border rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200 '. $class);
@endphp

<div class="relative">
    <select name="{{ $name }}" id="{{ $id }}" class="{{ $selectClass }}">
        @if($placeholder)
            <option value="" disabled {{ empty($value) ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif

        @foreach($normalized as $optValue => $optLabel)
            <option value="{{ $optValue }}" {{ ((string) $optValue === (string) $value) ? 'selected' : '' }}>{{ $optLabel }}</option>
        @endforeach
    </select>

    @if($icon)
        <x-dynamic-component :component="'flux::icon.' . $icon" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-300" aria-hidden="true" />
    @endif
</div>
