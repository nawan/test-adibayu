<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'code',
        'total_amount',
        'status',
        'paid_amount',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $sale->code = 'SALE' . date('Ymd') . str_pad(Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
