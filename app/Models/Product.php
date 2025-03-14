<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}