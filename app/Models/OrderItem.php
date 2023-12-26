<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    protected $table ="orderItems";
    protected $fillable =[
        "order_id",
        "product_id",
        "quantity",
        "name",
        "size",
        "color",
        "price",
        "image",
        "payment",
    ];
    
    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function order():BelongsTo{
        return $this->belongsTo(Order::class,'orderItem_id');
    }




}
