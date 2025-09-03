@props([
    'nameStart' => 'date_from',
    'nameEnd' => 'date_to',
    'idStart' => null,
    'idEnd' => null,
    'valueStart' => null,
    'valueEnd' => null,
    'label' => null,
    'class' => '',
])

@php
    $idStart = $idStart ?? $nameStart;
    $idEnd = $idEnd ?? $nameEnd;
    $label = $label ?? __('Date range');
    $baseClasses = 'px-3 py-2 border rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200';
@endphp

<div {{ $attributes->merge(['class' => 'relative ' . $class]) }}>
    <label for="{{ $idStart }}" class="sr-only">{{ $label }}</label>

    <div class="flex items-center space-x-2">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <flux:icon.calendar-days class="w-4 h-4 text-gray-400 dark:text-gray-300" />
            </div>

            <input type="date" name="{{ $nameStart }}" id="{{ $idStart }}" value="{{ $valueStart ?? '' }}"
                class="pl-10 pr-3 {{ $baseClasses }}" />
        </div>

        <span class="text-sm text-gray-500 dark:text-gray-400">to</span>

        <input type="date" name="{{ $nameEnd }}" id="{{ $idEnd }}" value="{{ $valueEnd ?? '' }}"
            class="{{ $baseClasses }}" />
    </div>
</div>
