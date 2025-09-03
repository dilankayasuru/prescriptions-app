<x-layouts.app :title="__('Quotation Details')">
    <div class="space-y-6">
        <!-- Header with back button -->
        <x-back-button route="quotations" label="Back to Quotations" />

        <!-- Main Title -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <x-main-title title="Quotation #{{ $quotation->id }}" description="View quotation details and medicines."
                class="pb-4" />

            <div class="flex gap-3">
                @if (Auth::user()->userRole === 'admin' && $quotation->status === 'pending')
                    <x-primary-button :label="__('Edit')" :route="route('quotations.edit', $quotation)" variant="outlined" />
                @endif
                <div
                    class="px-3 py-2 rounded-lg text-sm font-medium
                    @if ($quotation->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200
                    @elseif($quotation->status === 'accepted') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200
                    @elseif($quotation->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-200
                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-200 @endif">
                    {{ ucfirst($quotation->status) }}
                </div>
            </div>
        </div>

        @if (
            $quotation->status === 'pending' &&
                Auth::user()->userRole !== 'admin' &&
                $quotation->prescription->user_id === Auth::id())
            <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Quotation Status</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Please review the quotation details above and choose to accept or reject this quotation.
                </p>
                <div class="flex gap-3">
                    <form action="{{ route('quotations.updateStatus', $quotation) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <x-primary-button :label="__('Accept Quotation')" icon="check" variant="success" type="submit"
                            role="button" />
                    </form>
                    <form action="{{ route('quotations.updateStatus', $quotation) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <x-primary-button :label="__('Reject Quotation')" icon="x-mark" variant="danger" type="submit"
                            role="button" />
                    </form>
                </div>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Prescription Details -->
            <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prescription Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Patient</p>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-200">
                                @if (isset($quotation->prescription->user) && $quotation->prescription->user->name)
                                    {{ Str::limit(collect(explode(' ', $quotation->prescription->user->name))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join(''),2,'') }}
                                @else
                                    P
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $quotation->prescription->user->name ?? 'Unknown Patient' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $quotation->prescription->user->email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Prescription ID</p>
                        <p class="font-medium">#{{ $quotation->prescription->id }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Created At</p>
                        <p class="font-medium">{{ $quotation->prescription->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Delivery Address</p>
                        <p class="font-medium">{{ $quotation->prescription->delivery_address ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Prescription Images -->
                @if ($quotation->prescription->images->count() > 0)
                    <div class="lg:col-span-1" x-data="{
                        currentImage: '{{ asset('storage/' . $quotation->prescription->images[0]->file_path) }}',
                        currentImageName: '{{ $quotation->prescription->images[0]->file_name }}'
                    }">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prescription Images</h3>
                        <div
                            class="h-96 rounded overflow-hidden flex items-center justify-center bg-neutral-200 dark:bg-neutral-700">
                            <img :src="currentImage" :alt="currentImageName"
                                class="h-full object-contain cursor-pointer transition-all duration-300"
                                @click="openImageModal(currentImage, currentImageName)">
                        </div>
                        <div class="grid grid-cols-5 space-x-1">
                            @foreach ($quotation->prescription->images as $image)
                                <div class="py-2 cursor-pointer">
                                    <img src="{{ asset('storage/' . $image->file_path) }}"
                                        alt="{{ $image->file_name }}"
                                        class="max-h-32 aspect-square object-cover rounded transition-all duration-200 hover:opacity-80 hover:scale-105"
                                        @mouseenter="currentImage = '{{ asset('storage/' . $image->file_path) }}'; currentImageName = '{{ $image->file_name }}'"
                                        @click="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->file_name }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Right Column: Medicine List -->
                <div class="@if ($quotation->prescription->images->count() > 0) lg:col-span-2 @else lg:col-span-3 @endif">
                    <div
                        class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medicine Details</h2>

                        <!-- Medicine Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Drug Name</th>
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Dosage</th>
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Quantity</th>
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Unit Price (LKR)</th>
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total (LKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation->medicineQuotations as $medicine)
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            <td class="py-3 px-2 font-medium">{{ $medicine->name }}</td>
                                            <td class="py-3 px-2">{{ $medicine->dosage }}</td>
                                            <td class="py-3 px-2">{{ $medicine->quantity }}</td>
                                            <td class="py-3 px-2">{{ number_format($medicine->unit_price, 2) }}</td>
                                            <td class="py-3 px-2 font-medium text-blue-600 dark:text-blue-400">
                                                {{ number_format($medicine->unit_price * $medicine->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    LKR {{ number_format($quotation->total_price, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    @if ($quotation->prescription->images->count() > 0)
        <div id="imageModal" class="fixed inset-0 bg-black/25 backdrop-blur-sm z-50 items-center justify-center"
            style="display: none;">
            <div class="relative max-w-4xl max-h-full p-4">
                <button onclick="closeImageModal()"
                    class="cursor-pointer absolute top-6 right-6 text-white hover:text-gray-300 z-10">
                    <flux:icon.x-mark class="w-8 h-8" />
                </button>
                <img id="modalImage" src="" alt="" class="max-h-[90vh] object-cover rounded-lg">
            </div>
        </div>

        <script>
            // Image modal functionality
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
    @endif
</x-layouts.app>
