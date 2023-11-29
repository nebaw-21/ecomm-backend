<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    protected $fillable =['product_color_id', 'productId','image'];

public function color():BelongsTo{
    return $this->belongsTo(product_color::class, 'product_color_id');
}

}
