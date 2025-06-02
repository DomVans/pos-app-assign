<x-filament::page>
    <div class="space-y-6">
        {{-- Search Bar --}}
        <div class="w-full">
            <input 
                wire:model.debounce.500ms="search" 
                type="text"
                placeholder="Search for products..." 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
            />
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($this->filteredProducts as $product)
                <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-md border dark:border-gray-700 hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h2>

                    {{-- Stock Select --}}
                    <select
                        wire:model="selectedStocks.{{ $product->id }}"
                        class="w-full mt-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
                    >
                        <option value="">Select Stock</option>
                        @foreach ($product->stockItems as $stockItem)
                            <option value="{{ $stockItem->id }}">
                                Batch: {{ $stockItem->stock->batch_code ?? $stockItem->id }} — 
                                Rs {{ number_format($stockItem->price, 2) }} ({{ $stockItem->quantity }} left)
                            </option>
                        @endforeach
                    </select>

                    @php
                        $selectedStockId = $selectedStocks[$product->id] ?? null;
                        $selectedStockItem = $product->stockItems->firstWhere('id', (int)$selectedStockId);
                    @endphp

                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                        <p>
                            <strong>Price:</strong>
                            @if($selectedStockItem)
                                Rs {{ number_format($selectedStockItem->price, 2) }}
                            @else
                                <span class="text-gray-400 dark:text-gray-500">Select stock</span>
                            @endif
                        </p>
                        <p><strong>Total Stock:</strong> {{ $product->stockItems->sum('quantity') }}</p>
                    </div>

                    {{-- Quantity Input --}}
                    <input
                        type="number"
                        min="1"
                        wire:model.defer="quantities.{{ $product->id }}"
                        placeholder="Quantity"
                        class="mt-2 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
                    />

                    {{-- Discount Dropdown --}}
                    <select
                        wire:model.defer="discounts.{{ $product->id }}"
                        class="w-full mt-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
                    >
                        <option value="">No Discount</option>
                        @foreach (\App\Models\Discount::all() as $discount)
                            <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                        @endforeach
                    </select>

                    {{-- Add to Cart Button --}}
                    <button 
                        wire:click="addToCart({{ $product->id }})"
                        class="w-full mt-4 px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition"
                    >
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Cart --}}
        <div class="mt-8 bg-white dark:bg-gray-900 shadow-lg rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">🛒 Cart</h2>
            </div>

            @if (count($cart) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Product</th>
                                <th class="px-4 py-3 text-left">Stock</th>
                                <th class="px-4 py-3 text-left">Qty</th>
                                <th class="px-4 py-3 text-left">Price</th>
                                <th class="px-4 py-3 text-left">Discount</th>
                                <th class="px-4 py-3 text-left">Subtotal</th>
                                <th class="px-4 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($cart as $index => $item)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-white">{{ $item['product_name'] }}</td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $item['stock_batch'] }}</td>
                                    <td class="px-4 py-2">{{ $item['quantity'] }}</td>
                                    <td class="px-4 py-2">Rs {{ number_format($item['price'], 2) }}</td>
                                    <td class="px-4 py-2">{{ $item['discount_name'] ?? '—' }}</td>
                                    <td class="px-4 py-2 font-semibold text-green-600 dark:text-green-400">Rs {{ number_format($item['subtotal'], 2) }}</td>
                                    <td class="px-4 py-2">
                                        <button 
                                            wire:click="removeFromCart({{ $index }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Total & Place Order --}}
                <div class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-lg font-bold text-gray-800 dark:text-white">
                        Total: Rs {{ number_format($this->getTotal(), 2) }}
                    </div>
                    <button 
                        wire:click="placeOrder"
                        class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition"
                    >
                        ✅ Place Order
                    </button>
                </div>
            @else
                <div class="p-4 text-gray-500 dark:text-gray-400">
                    Your cart is empty.
                </div>
            @endif
        </div>
    </div>
</x-filament::page>





