<x-layouts.app :title="__('Create Quotation')">
    <div class="space-y-6">
        <!-- Header with back button -->
        <x-back-button route="quotations" label="Back to Quotations" />

        <!-- Main Title -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <x-main-title title="Add Medicines to Quotation" description="Add medicines with quantities and prices."
                class="pb-4" />
        </div>

        {{-- Display Success/Error Messages --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:border-red-800">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Please fix the following errors:
                </h3>
                <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-8">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Prescription Details</h2>
                <div class="flex items-center gap-6 flex-wrap">
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">User</p>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-200">
                                @if (isset($prescription->user) && $prescription->user->name)
                                    {{ Str::limit(collect(explode(' ', $prescription->user->name))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join(''),2,'') }}
                                @else
                                    P
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $prescription->user->name ?? 'Unknown Patient' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $prescription->user->email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Prescription ID</p>
                        <p>#{{ $prescription->id ?? 'Unknown' }}</p>
                    </div>

                    <div class="space-y-2">
                        <p class="text-sm dark:text-neutral-300 text-neutral-500">Created At</p>
                        <p>{{ $prescription->created_at ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Prescription Images -->

                <div class="lg:col-span-1" x-data="{
                    currentImage: '{{ asset('storage/' . $prescription->images[0]->file_path) }}',
                    currentImageName: '{{ $prescription->images[0]->file_name }}'
                }">
                    <div
                        class="h-96 rounded overflow-hidden flex items-center justify-center bg-neutral-200 dark:bg-neutral-700">
                        <img :src="currentImage" :alt="currentImageName"
                            class="h-full object-contain cursor-pointer transition-all duration-300"
                            @click="openImageModal(currentImage, currentImageName)">
                    </div>
                    <div class="grid grid-cols-5 space-x-1">
                        @foreach ($prescription->images as $image)
                            <div class="py-2 cursor-pointer">
                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->file_name }}"
                                    class="max-h-32 aspect-square object-cover rounded transition-all duration-200 hover:opacity-80 hover:scale-105"
                                    @mouseenter="currentImage = '{{ asset('storage/' . $image->file_path) }}'; currentImageName = '{{ $image->file_name }}'"
                                    @click="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->file_name }}')">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Medicine List -->
                <form action="{{ route('quotations.store') }}" method="POST" class="lg:col-span-2"
                    x-data="medicineQuotation()">
                    @csrf
                    <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">

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
                                            Price per Unit (LKR)</th>
                                        <th
                                            class="text-left py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total (LKR)</th>
                                        <th
                                            class="text-center py-3 px-2 text-sm font-medium text-gray-700 dark:text-gray-300 w-16">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(medicine, index) in medicines" :key="index">
                                        <tr class="medicine-row border-b border-gray-100 dark:border-gray-700">
                                            <td class="py-3 px-2">
                                                <input type="text" :name="`medicines[${index}][name]`"
                                                    x-model="medicine.name" placeholder="Medicine name"
                                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600"
                                                    required />
                                            </td>
                                            <td class="py-3 px-2">
                                                <input type="text" :name="`medicines[${index}][dosage]`"
                                                    x-model="medicine.dosage" placeholder="Dosage"
                                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600"
                                                    required />
                                            </td>
                                            <td class="py-3 px-2">
                                                <input type="number" :name="`medicines[${index}][quantity]`"
                                                    x-model="medicine.quantity" @input="updateRowTotal(index)"
                                                    placeholder="Quantity" min="1"
                                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600"
                                                    required />
                                            </td>
                                            <td class="py-3 px-2">
                                                <input type="number" :name="`medicines[${index}][price]`"
                                                    x-model="medicine.price" @input="updateRowTotal(index)"
                                                    placeholder="Unit price" min="0" step="0.01"
                                                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-600"
                                                    required />
                                            </td>
                                            <td class="py-3 px-2">
                                                <span class="text-blue-600 dark:text-blue-400 font-medium"
                                                    x-text="`${medicine.total.toFixed(2)}`">0.00</span>
                                            </td>
                                            <td class="py-3 px-2 text-center">
                                                <button type="button" @click="removeMedicineRow(index)"
                                                    :disabled="!canRemoveRows"
                                                    :class="canRemoveRows ?
                                                        'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300' :
                                                        'text-gray-300 dark:text-gray-600 cursor-not-allowed'"
                                                    class="p-1 transition-colors duration-200">
                                                    <flux:icon.trash class="w-4 h-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Row Button -->
                        <div class="mt-4 flex justify-start">
                            <button type="button" @click="addMedicineRow()"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                                <flux:icon.plus class="w-4 h-4 mr-2" />
                                Add Row
                            </button>
                        </div>

                        <!-- Total Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                    x-text="`LKR ${grandTotal.toFixed(2)}`">LKR 0.00</span>
                            </div>
                            <input type="hidden" name="total_price" :value="grandTotal.toFixed(2)">
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                            <x-primary-button :label="__('Cancel')" :route="route('quotations')" variant="outlined" />
                            <x-primary-button :label="__('Send Quotation')" role="button" type="submit" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
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
        function medicineQuotation() {
            return {
                medicines: [{
                    name: '',
                    dosage: '',
                    quantity: 1,
                    price: 0,
                    total: 0
                }],

                get grandTotal() {
                    return this.medicines.reduce((sum, medicine) => sum + medicine.total, 0);
                },

                get canRemoveRows() {
                    return this.medicines.length > 1;
                },

                addMedicineRow() {
                    this.medicines.push({
                        name: '',
                        dosage: '',
                        quantity: 1,
                        price: 0,
                        total: 0
                    });
                },

                removeMedicineRow(index) {
                    if (this.medicines.length > 1) {
                        this.medicines.splice(index, 1);
                    }
                },

                updateRowTotal(index) {
                    const medicine = this.medicines[index];
                    const quantity = parseFloat(medicine.quantity) || 0;
                    const price = parseFloat(medicine.price) || 0;
                    medicine.total = quantity * price;
                }
            }
        }

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
</x-layouts.app>
