<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name',
        'image',
        'price',
        'description',
        'stock',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
