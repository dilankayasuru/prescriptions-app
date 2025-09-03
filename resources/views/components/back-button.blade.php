@props([
    'route' => '',
    'label' => '',
])
<a href="{{ route($route) }}"
    class="flex items-center hover:text-neutral-600 dark:text-neutral-100 dark:hover:text-neutral-200 transition-colors duration-200">
    <flux:icon name="arrow-left" class="w-5 h-5 mr-2" />
    {{ $label }}
</a>
