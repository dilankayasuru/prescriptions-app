<x-layouts.app :title="__('Prescriptions')">
    <p>Prescription details will be shown here.</p>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-4">Prescription</h1>

        @if ($prescription->images && $prescription->images->count())
            <div class="grid grid-cols-3 gap-3">
                @foreach ($prescription->images as $image)
                    <div class="border rounded overflow-hidden bg-white dark:bg-neutral-700">
                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->file_name }}"
                            class="w-full h-48 object-cover" />
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No images uploaded for this prescription.</p>
        @endif
    </div>
</x-layouts.app>
