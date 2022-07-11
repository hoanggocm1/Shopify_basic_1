<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'position',
        'alt',
        'width',
        'height',
        'src',
        'variant_ids',
    ];
}
