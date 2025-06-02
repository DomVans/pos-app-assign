<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use App\Models\StockItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.point-of-sale';
    protected static ?string $navigationLabel = 'Point of Sale';
    protected static ?string $navigationGroup = 'POS';

    public array $cart = [];
    public string $search = '';
    public array $quantities = [];
    public array $discounts = [];
    public array $selectedStocks = [];

    public function getFilteredProductsProperty()
{
    $search = trim($this->search);
    
    if (empty($search)) {
        return Product::with('stockItems.stock')->limit(50)->get();
    }
    
    return Product::with('stockItems.stock')
        ->where(function ($query) use ($search) {
            // Text search with ILIKE (PostgreSQL case-insensitive)
            $query->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('description', 'ILIKE', "%{$search}%");
            
            // Numeric search
            if (is_numeric($search)) {
                $query->orWhereHas('stockItems', function ($q) use ($search) {
                    $q->where('id', $search)
                      ->orWhere('price', $search)
                      ->orWhere('quantity', $search);
                });
            }
            
            // Batch code search
            $query->orWhereHas('stockItems.stock', function ($q) use ($search) {
                $q->where('batch_code', 'ILIKE', "%{$search}%");
            });
        })
        ->orderBy('name') // Better organization of results
        ->get();
}

    public function addToCart($productId)
    {
        $stockItemId = $this->selectedStocks[$productId] ?? null;
        if (!$stockItemId) {
            Notification::make()
                ->title('Select Stock')
                ->body('Please select a stock batch for this product.')
                ->danger()
                ->send();
            return;
        }

        $stockItem = StockItem::with(['product', 'stock'])->find($stockItemId);
        if (!$stockItem) {
            Notification::make()
                ->title('Invalid Stock')
                ->body('Selected stock batch not found.')
                ->danger()
                ->send();
            return;
        }

        $quantity = $this->quantities[$productId] ?? 1;
        $discountId = $this->discounts[$productId] ?? null;

        if ($quantity < 1) {
            Notification::make()
                ->title('Invalid Quantity')
                ->body('Quantity must be at least 1.')
                ->danger()
                ->send();
            return;
        }

        if ($stockItem->quantity < $quantity) {
            Notification::make()
                ->title('Insufficient Stock')
                ->body("Not enough stock available in selected batch. Available: {$stockItem->quantity}.")
                ->danger()
                ->send();
            return;
        }

        $discount = $discountId ? Discount::find($discountId) : null;
        $price = $stockItem->price;
        $discountAmount = 0;

        if ($discount) {
            $discountAmount = $discount->type === 'percentage'
                ? ($price * $discount->value / 100)
                : $discount->value;
        }

        $priceAfterDiscount = max($price - $discountAmount, 0);
        $subtotal = $priceAfterDiscount * $quantity;

        $this->cart[] = [
            'product_id' => $productId,
            'product_name' => $stockItem->product->name,
            'stock_item_id' => $stockItem->id,
            'stock_batch' => $stockItem->stock->batch_code ?? $stockItem->id,
            'price' => $price,
            'quantity' => $quantity,
            'discount_id' => $discountId,
            'discount_name' => $discount?->name,
            'subtotal' => $subtotal,
        ];
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function getTotal(): float
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function placeOrder()
    {
        $order = Order::create();

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'discount_id' => $item['discount_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'stock_item_id' => $item['stock_item_id'],
            ]);

            $stockItem = StockItem::find($item['stock_item_id']);
            if ($stockItem && $stockItem->quantity >= $item['quantity']) {
                $stockItem->quantity -= $item['quantity'];
                $stockItem->save();
            }
        }

        $this->cart = [];
        $this->quantities = [];
        $this->discounts = [];
        $this->selectedStocks = [];

        Notification::make()
            ->title('Order Placed')
            ->body('Your order has been successfully placed.')
            ->success()
            ->send();
    }
}


