<x-layouts.app :title="__('Prescriptions')">
    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <x-main-title title="Prescriptions" description="Manage and track all prescription orders." />
            @if (Auth::user()->userRole === 'user')
                <div class="flex items-center space-x-3">
                    <x-primary-button :label="__('New Prescription')" :route="route('prescriptions.create')" icon="plus" />
                </div>
            @endif
        </div>

        <div
            class="bg-white dark:bg-neutral-800 rounded-lg shadow border border-gray-200 dark:border-neutral-700 overflow-hidden">
            <div class="px-6 py-4 border-b">
                <form method="GET" action="{{ route('prescriptions') }}" id="filterForm">
                    <div class="flex items-center justify-end space-x-3">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm">Status:</p>
                            <label for="status" class="sr-only">status</label>
                            <div class="relative">
                                <x-select-input name="status" :options="[
                                    '' => 'All',
                                    'pending' => 'Pending Review',
                                    'quotation_sent' => 'Quotation Sent',
                                ]" :value="request('status')" />
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <p class="text-sm">Sort By:</p>
                            <label for="sort" class="sr-only">Sort</label>
                            <div class="relative">
                                <x-select-input name="sort" :options="[
                                    'newest' => 'Newest First',
                                    'oldest' => 'Oldest First',
                                ]" :value="request('sort', 'newest')" />
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <p class="text-sm">Date Range:</p>
                            <x-date-range name-start="date_from" name-end="date_to" :value-start="request('date_from')" :value-end="request('date_to')" />
                        </div>

                        <div class="flex items-center space-x-2">
                            <x-primary-button label="Apply Filters" type="submit" role="button" />
                            <x-primary-button label="Clear Filters" variant="outlined"
                                route="{{ route('prescriptions') }}" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Prescription ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Patient</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($prescriptions ?? collect([]) as $prescription)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-900">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    #{{ $prescription->id ?? '—' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $prescription->created_at->format('Y-m-d g:i a') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($prescription->quotation)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Quotation Sent
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Pending Review
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ isset($prescription) ? route('prescriptions.show', $prescription) : '#' }}"
                                        class="text-blue-600 hover:text-blue-800 dark:hover:text-neutral-200 dark:text-white">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <x-empty-state title="{{ __('No prescriptions yet') }}"
                                        description="{{ __('You don\'t have any prescriptions. Create your first prescription to get started — you can add notes, delivery details and upload images.') }}"
                                        icon="document-text" :button-label="__('New Prescription')" :button-route="route('prescriptions.create')" button-icon="plus" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
