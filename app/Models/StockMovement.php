<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = ['stock_item_id', 'type', 'quantity', 'reason'];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    protected static function booted()
    {
        static::creating(function ($movement) {
            $stockItem = $movement->stockItem;

            if ($movement->type === 'in') {
                $stockItem->quantity += $movement->quantity;
            } elseif ($movement->type === 'out') {
                $stockItem->quantity -= $movement->quantity;
            }

            $stockItem->save();
        });

        static::updating(function ($movement) {
            $original = $movement->getOriginal();

            $stockItem = $movement->stockItem;

            if ($original['type'] === 'in') {
                $stockItem->quantity -= $original['quantity'];
            } elseif ($original['type'] === 'out') {
                $stockItem->quantity += $original['quantity'];
            }

            if ($movement->type === 'in') {
                $stockItem->quantity += $movement->quantity;
            } elseif ($movement->type === 'out') {
                $stockItem->quantity -= $movement->quantity;
            }

            $stockItem->save();
        });

        static::deleting(function ($movement) {
            $stockItem = $movement->stockItem;

            if ($movement->type === 'in') {
                $stockItem->quantity -= $movement->quantity;
            } elseif ($movement->type === 'out') {
                $stockItem->quantity += $movement->quantity;
            }

            $stockItem->save();
        });
    }
}


