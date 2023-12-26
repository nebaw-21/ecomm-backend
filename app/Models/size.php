<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class size extends Model
{
    use HasFactory;
protected $fillable = ['product_color_id', 'ProductId','size', 'published'];
public function color():BelongsTo{
    return $this->belongsTo(product_color::class, 'product_color_id');
}    




}
