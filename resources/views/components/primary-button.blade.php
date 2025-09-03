@props(['label', 'route' => '#', 'icon' => null, 'class' => '', 'type' => 'submit'])

@if ($type === 'submit')
    <button type="submit"
        {{ $attributes->merge(['class' => trim('cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm text-sm font-medium ' . $class)]) }}>
        @if ($icon)
            <x-dynamic-component :component="'flux::icon.' . $icon" class="-ml-1 mr-2 h-4 w-4" />
        @endif

        {{ $label }}
    </button>
@else
    <a href="{{ $route }}"
        {{ $attributes->merge(['class' => trim('cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm text-sm font-medium ' . $class)]) }}>
        @if ($icon)
            <x-dynamic-component :component="'flux::icon.' . $icon" class="-ml-1 mr-2 h-4 w-4" />
        @endif

        {{ $label }}
    </a>
@endif
