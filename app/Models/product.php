<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class product extends Model
{
    use HasFactory;
protected $fillable = [
    'name', 'price', 'description', 'categoryId','is_recommended',
];

public function category():BelongsTo {
    return $this->belongsTo(Category::class, 'categoryId');
}

public function colors():HasMany {
    return $this->hasMany(product_color::class, 'productId');
}

public function sizes():HasMany{
    return $this->hasMany(size::class, 'productId');
}

public function images():HasMany{
    return $this->hasMany(Image::class, 'productId');
}

public function OrderItemsProduct():HasMany{
    return $this->hasMany(OrderItem::class);
}






}
