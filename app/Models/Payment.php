<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sale_id',
        'code',
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            $payment->code = 'PAY' . date('Ymd') . str_pad(Payment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
