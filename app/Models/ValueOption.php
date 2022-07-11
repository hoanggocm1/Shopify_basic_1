<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'id_options',
        'stt',
        'name',
    ];
}
