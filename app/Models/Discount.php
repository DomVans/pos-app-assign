<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'value'];
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
