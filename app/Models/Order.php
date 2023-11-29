<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
     
    ];

public function User():BelongsTo{
    return $this->belongsTo(User::class,'user_id');
}    

public function OrderItems():HasMany{
    return $this->hasMany(OrderItem::class);
}    


}
