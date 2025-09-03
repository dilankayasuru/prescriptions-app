@props([
    'name' => 'files[]',
    'accept' => 'image/*',
    'multiple' => true,
    'maxFiles' => 5,
    'hint' => 'Accepted formats: JPG, PNG. Max :count files.',
    'icon' => 'photo',
    'heightClass' => 'h-56',
])

@php
    $hintText = str_replace(':count', $maxFiles, $hint);
    $multipleAttr = $multiple ? 'multiple' : '';
@endphp

<label class="w-full block">
    <div class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-neutral-700 rounded-md {{ $heightClass }} bg-white dark:bg-neutral-800 text-center p-6">
        <x-dynamic-component :component="'flux::icon.' . $icon" class="h-10 w-10 text-gray-400 dark:text-gray-300 mb-3" />

        <p class="text-sm font-medium text-gray-900 dark:text-white">Drag &amp; drop files here</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">or <span class="text-blue-600 dark:text-blue-400 underline">click to browse</span></p>

        <input type="file" name="{{ $name }}" {{ $multipleAttr }} accept="{{ $accept }}" class="sr-only" />
    </div>

    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $hintText }}</p>
</label>
