<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'unique_id',
        'product_name',
        'sku',
        'price',
        'description',
        'encrypted_data',
        'qr_image'
    ];
}
