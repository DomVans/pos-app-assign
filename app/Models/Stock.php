<?php

namespace App\Models;

use Filament\Panel\Concerns\HasFavicon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'batch_code'];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'stock_items')
                    ->withPivot(['price', 'quantity', 'barcode'])
                    ->withTimestamps();
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

}
