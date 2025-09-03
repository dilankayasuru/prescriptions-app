@props([
    'label',
    'route' => '#',
    'icon' => null,
    'class' => '',
    'role' => 'link',
    'type' => '',
    'variant' => 'primary',
])

@php
    switch ($variant) {
        case 'outlined':
            $class = 'bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 dark:text-blue-300 dark:hover:text-blue-200';
            break;
        case 'danger':
            $class = 'bg-red-600 hover:bg-red-700 text-white';
            break;
        case 'primary':
            $class = 'bg-blue-600 hover:bg-blue-700 text-white';
            break;
        default:
            $class = 'bg-blue-600 hover:bg-blue-700 text-white';
            break;
    }
@endphp

@if ($role === 'button')
    <button type="{{ $type === 'submit' ? 'submit' : 'button' }}"
        {{ $attributes->merge(['class' => trim('cursor-pointer inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium ' . $class)]) }}>
        @if ($icon)
            <x-dynamic-component :component="'flux::icon.' . $icon" class="-ml-1 mr-2 h-4 w-4" />
        @endif

        {{ $label }}
    </button>
@else
    <a href="{{ $route }}"
        {{ $attributes->merge(['class' => trim('cursor-pointer inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium ' . $class)]) }}>
        @if ($icon)
            <x-dynamic-component :component="'flux::icon.' . $icon" class="-ml-1 mr-2 h-4 w-4" />
        @endif

        {{ $label }}
    </a>
@endif
