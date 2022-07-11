<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'title',
        'price',
        'sku',
        'position',
        'inventory_policy',
        'compare_at_price',
        'fulfillment_service',
        'inventory_management',
        'option1',
        'option2',
        'option3',
        'taxable',
        'barcode',
        'image_id',
        'weight',
        'weight_unit',
        'inventory_item_id',
        'inventory_quantity',
        'old_inventory_quantity',
        'requires_shipping',
    ];
}
