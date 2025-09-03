<x-layouts.app :title="__('Quotations')">
    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <x-main-title title="Quotations" description="Manage and track all prescription quotations." />
        </div>

        <div
            class="bg-white dark:bg-neutral-800 rounded-lg shadow border border-gray-200 dark:border-neutral-700 overflow-hidden">
            <div class="px-6 py-4 border-b">
                <form method="GET" action="{{ route('quotations') }}" id="filterForm">
                    <div class="flex items-center justify-end space-x-3">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm">Status:</p>
                            <label for="status" class="sr-only">filter status</label>
                            <div class="relative">
                                <x-select-input name="status" :options="[
                                    'all' => 'All',
                                    'pending' => 'Pending',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                    'completed' => 'Completed',
                                ]" :value="request('status', 'all')" />
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
                            <x-date-range name-start="date_from" name-end="date_to" :value-start="request('date_from')"
                                :value-end="request('date_to')" />
                        </div>

                        <div class="flex items-center space-x-2">
                            <x-primary-button label="Apply Filters" type="submit" role="button" />
                            <x-primary-button label="Clear Filters" variant="outlined"
                                route="{{ route('quotations') }}" />
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
                                Quotation ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Patient</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Created</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-100 dark:divide-gray-800">
                        @php
                            $statusClass = function ($status) {
                                $s = strtolower($status ?? '');
                                return match ($s) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            };
                        @endphp

                        @forelse ($quotations ?? collect([]) as $quotation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-900">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ $quotation->id ?? '—' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Prescription #{{ $quotation->prescription_id ?? '—' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        ${{ number_format($quotation->total_price ?? 0, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $quotation->medicineQuotations->count() ?? 0 }} items
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $cls = $statusClass($quotation->status ?? 'Pending'); @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                                        {{ ucfirst($quotation->status ?? 'Pending') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $quotation->created_at->format('M j, Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ isset($quotation) ? route('quotations.show', $quotation) : '#' }}"
                                            class="text-blue-600 hover:text-blue-800 dark:hover:text-neutral-200 dark:text-white">
                                            View Details
                                        </a>
                                        @if (Auth::user()->userRole === 'admin' && $quotation->status === 'pending')
                                            <form action="{{ route('quotations.destroy', $quotation) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    onclick="return confirm('Are you sure you want to delete this quotation?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12">
                                    <x-empty-state title="{{ __('No quotations yet') }}"
                                        description="{{ __('No quotations have been created yet. Quotations will appear here once prescriptions are processed.') }}"
                                        icon="calculator" :button-label="__('Go to Prescriptions')" :button-route="route('prescriptions')"
                                        button-icon="document-text" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
