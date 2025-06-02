<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;

class ViewOrder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static string $view = 'filament.pages.view-order';
    protected static ?string $navigationGroup = 'POS';
    protected static ?string $title = 'View Orders';

    public $orders;

    public function mount()
    {
        $this->orders = Order::with(['orderItems.product', 'orderItems.stock', 'orderItems.discount'])
            ->latest()
            ->get();
    }
}


