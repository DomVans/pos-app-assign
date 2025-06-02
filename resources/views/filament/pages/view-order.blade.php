<x-filament::page>
    <x-filament::card>
        <div class="px-4 py-2">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Order History
            </h2>
        </div>

        @if($this->orders->isEmpty())
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                No orders found.
            </div>
        @else
            <div class="space-y-4">
                @foreach($this->orders as $order)
                    <x-filament::card>
                        <div class="flex flex-col space-y-4 p-4">
                            <!-- Order Header -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Order Items Table -->
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Product
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Qty
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Discount
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($order->orderItems ?? [] as $item)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    {{ $item->product->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    Rs {{ number_format($item->price, 2) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $item->discount->name ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-primary-600 dark:text-primary-400">
                                                    Rs {{ number_format($item->subtotal, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                                    No items in this order
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </x-filament::card>
                @endforeach
            </div>
        @endif
    </x-filament::card>
</x-filament::page>

