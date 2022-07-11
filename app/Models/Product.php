<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Sử dụng trong bài Commands
    use HasFactory;
    protected $fillable = [
        'id',
        'domain_shop',
        'title',
        'body_html',
        'vendor',
        'product_type',
        'handle',
        'published_at',
        'template_suffix',
        'status',
        'published_scope',
        'tags',
        'image',
    ];

    public function productVariant()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id');
    }
    public function productOption()
    {
        return $this->hasMany(Option::class, 'product_id', 'id');
    }
    public function productImageFirst()
    {
        return $this->hasMany(imageFirst::class, 'product_id', 'id');
    }
    public function productImages()
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }
}
