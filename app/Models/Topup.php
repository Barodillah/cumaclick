<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'payload',
        'is_processed',
    ];

    protected $casts = [
        'payload' => 'array',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
