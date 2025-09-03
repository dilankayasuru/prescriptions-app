@props([
    'title' => '',
    'description' => '',
    'class' => '',
])

<div class="{{ $class }}">
    <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-zinc-200">{{ $description }}</p>
</div>
