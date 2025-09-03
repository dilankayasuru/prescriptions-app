@props([
    'title' => '',
    'description' => '',
    'icon' => null,
    'buttonLabel' => null,
    'buttonRoute' => null,
    'buttonIcon' => null,
])

<div class="max-w-lg mx-auto text-center">
    @if ($icon)
        <x-dynamic-component :component="'flux::icon.' . $icon" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-400" />
    @endif

    @if ($title)
        <h3 class="mt-6 text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
    @endif

    @if ($description)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
    @endif

    @if ($buttonLabel && $buttonRoute)
        <div class="mt-6">
            <x-primary-button :label="$buttonLabel" :route="$buttonRoute" :icon="$buttonIcon" />
        </div>
    @endif

    {{ $slot }}
</div>
