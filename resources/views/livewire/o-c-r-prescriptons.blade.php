<div class="mt-4">
    {{-- Error Message Display --}}
    @if ($errorMessage)
        <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-4 relative"
            role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $errorMessage }}</span>
        </div>
    @endif

    @if (count($extractedMedicines) > 0)
        <h2 class="text-xl font-semibold mb-4">Extracted Medicines:</h2>
        @foreach ($extractedMedicines as $medicine)
            <div
                class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 mb-2">
                <p class="text-gray-900 dark:text-white"><strong>Medicine Name:</strong> {{ $medicine['medicine'] }}</p>
                <p class="text-gray-900 dark:text-white"><strong>Dosage:</strong> {{ $medicine['dosage'] }}</p>
            </div>
        @endforeach
    @else
        <button wire:click="extractMedicine"
            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 cursor-pointer">
            <span wire:target="extractMedicine" wire:loading.remove>
                Extract Medicines
            </span>
            <span wire:target="extractMedicine" wire:loading wire:loading.class="animate-pulse">
                Extracting...
            </span>
        </button>
    @endif
</div>
