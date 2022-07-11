<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class infoShop extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name_shop',
        'domain',
        'email',
        'shopify_domain',
        'access_token',
        'plan',
    ];
}
