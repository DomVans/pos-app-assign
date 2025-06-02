<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use App\Models\Product;

class StockItem extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'stock_id', 'price', 'quantity', 'barcode'];
    protected $table = 'stock_items';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }   

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

}
