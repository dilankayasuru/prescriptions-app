<x-layouts.app :title="__('Prescriptions')">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Upload Your Prescription</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">Please upload clear images of your prescription. You
                can upload up to 5 images.</p>
        </div>

        {{-- Display Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Upload Dropzone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Prescription Images <span class="text-red-500">*</span>
                </label>
                @livewire('drop-zone', ['name' => 'images[]', 'maxFiles' => 5, 'hint' => 'Accepted formats: JPG, PNG. Max :count files.', 'icon' => 'photo'])
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes, Address, Delivery Time --}}
            <div class="space-y-4">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes
                        for the Pharmacy <span class="text-xs text-gray-400">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="4"
                        placeholder="e.g., Please provide a generic alternative if possible."
                        class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_address"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Delivery Address <span class="text-red-500">*</span></label>
                    <input id="delivery_address" name="delivery_address" type="text"
                        placeholder="Enter your full delivery address" 
                        value="{{ old('delivery_address', Auth::user()->address ?? '') }}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white dark:bg-neutral-700 text-gray-900 dark:text-gray-100 dark:border-none focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required />
                    @error('delivery_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_time"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Delivery Time <span class="text-red-500">*</span></label>
                    <x-select-input name="delivery_time" id="delivery_time" :options="[
                        '' => 'Select a 2-hour time slot',
                        '09:00-11:00' => '09:00 - 11:00',
                        '11:00-13:00' => '11:00 - 13:00',
                        '13:00-15:00' => '13:00 - 15:00',
                        '15:00-17:00' => '15:00 - 17:00',
                    ]" :value="old('delivery_time')"
                        class="w-full"
                        required />
                    @error('delivery_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button :label="__('Submit Prescription')" class="inline-block" type="submit" />
            </div>
        </form>
    </div>
</x-layouts.app>