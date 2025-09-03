<x-layouts.app :title="__('Prescriptions')">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Upload Your Prescription</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Please upload clear images of your prescription. You
                can upload up to 5 images.</p>
        </div>

        <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Upload Dropzone --}}
            <x-dropzone name="images[]" :maxFiles="5" hint="Accepted formats: JPG, PNG. Max :count files."
                icon="photo" />

            {{-- Notes, Address, Delivery Time --}}
            <div class="space-y-4">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes
                        for the Pharmacy <span class="text-xs text-gray-400">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="4"
                        placeholder="e.g., Please provide a generic alternative if possible."
                        class="mt-1 block w-full px-4 py-3 border rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200"></textarea>
                </div>

                <div>
                    <label for="delivery_address"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Delivery Address</label>
                    <input id="delivery_address" name="delivery_address" type="text"
                        placeholder="Enter your full delivery address" value="{{ Auth::user()->address }}"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 border-gray-200 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>

                <div>
                    <label for="delivery_time"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Delivery Time</label>
                    <x-select-input name="delivery_time" id="delivery_time" :options="[
                        '' => 'Select a 2-hour time slot',
                        '09:00-11:00' => '09:00 - 11:00',
                        '11:00-13:00' => '11:00 - 13:00',
                        '13:00-15:00' => '13:00 - 15:00',
                        '15:00-17:00' => '15:00 - 17:00',
                    ]" :value="old('delivery_time')"
                        class="w-full" />
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button :label="__('Submit Prescription')" class="inline-block" />
            </div>
        </form>
    </div>
</x-layouts.app>