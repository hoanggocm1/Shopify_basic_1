<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'name',
        'position',
    ];
    public function valueOption()
    {
        return $this->hasMany(Image::class, 'id_options', 'id');
    }
}
