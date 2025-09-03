<x-layouts.app :title="__('Prescription Details')">
    <div class="max-w-4xl mx-auto px-4 space-y-6">
        <!-- Header with back button -->
        <x-back-button route="prescriptions" label="Back to Prescriptions" />

        <!-- Main Title -->
        <x-main-title title="Prescription Details" description="View the details of your uploaded prescription."
            class="pb-4" />

        <!-- Prescription Information Section -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prescription Information</h2>
            <div
                class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-700 dark:border-neutral-600 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm font-medium mb-2 text-neutral-500 dark:text-neutral-300">Prescription ID</p>
                        <p class="font-semibold">#{{ $prescription->id }}</p>
                    </div>

                    <div>
                        <div class="flex items-center mb-2 text-neutral-500 dark:text-neutral-300">
                            <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                            <span class="text-sm font-medium">Status</span>
                        </div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Pending Review
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center mb-2 text-neutral-500 dark:text-neutral-300">
                            <flux:icon name="calendar" class="w-5 h-5 mr-2" />
                            <span class="text-sm font-medium">Created</span>
                        </div>
                        <p>{{ $prescription->created_at->format('M j, Y') }}</p>
                        <p class="text-sm">{{ $prescription->created_at->format('g:i A') }}</p>
                    </div>

                    <div>
                        <div class="flex items-center mb-2 text-neutral-500 dark:text-neutral-300">
                            <flux:icon name="calendar" class="w-5 h-5 mr-2" />
                            <span class="text-sm font-medium">Last Updated</span>
                        </div>
                        <p>{{ $prescription->updated_at->format('M j, Y') }}</p>
                        <p class="text-sm">{{ $prescription->updated_at->format('g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Images Section -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Prescription Images</h2>

            @if ($prescription->images && $prescription->images->count())
                <div class="flex flex-wrap md:grid-cols-5 md:grid gap-4">
                    @foreach ($prescription->images as $image)
                        <div class="group relative md:max-w-none md:max-h-none max-w-32 max-h-32">
                            <div
                                class="aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-md hover:shadow-lg transition-shadow duration-300">
                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->file_name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                                    onclick="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->file_name }}')" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No images uploaded for this prescription.</p>
                </div>
            @endif
        </div>

        <!-- Notes Section -->
        @if ($prescription->note)
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notes</h2>
                <div
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-700 dark:border-neutral-600 rounded-lg p-4">
                    <p class="leading-relaxed">{{ $prescription->note }}</p>
                </div>
            </div>
        @endif

        <!-- Delivery Information Section -->
        @if ($prescription->delivery_address || $prescription->delivery_time)
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delivery Information</h2>
                <div
                    class="bg-neutral-100 border border-neutral-200 dark:bg-neutral-700 dark:border-neutral-600 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($prescription->delivery_address)
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</span>
                                </div>
                                <p>{{ $prescription->delivery_address }}</p>
                            </div>
                        @endif

                        @if ($prescription->delivery_time)
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Delivery
                                        Time</span>
                                </div>
                                <p>{{ $prescription->delivery_time }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col justify-end sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <x-primary-button :label="__('Edit Prescription')" variant="outlined" icon="pencil-square"
                route="{{ route('prescriptions', $prescription) }}" />
            <x-primary-button :label="__('Delete Prescription')" variant="danger" icon="trash"
                route="{{ route('prescriptions.destroy', $prescription) }}" />
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black/25 backdrop-blur-sm z-50 items-center justify-center max-h-70vh top-0 left-0 p-4"
        style="display: none;">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeImageModal()" class="cursor-pointer absolute top-6 right-6 text-white hover:text-gray-300 z-10">
                <flux:icon.x-mark class="w-8 h-8" />
            </button>
            <img id="modalImage" src="" alt=""
                class="max-h-[90vh] object-cover rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(src, alt) {
            const modal = document.getElementById('imageModal');
            document.getElementById('modalImage').src = src;
            document.getElementById('modalImage').alt = alt;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
</x-layouts.app>
