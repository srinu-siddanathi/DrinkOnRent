<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_date',
        'next_service_reminder',
        'expiry_date',
        'spare_parts',
        'total_amount',
        'payment_mode',
        'images',
    ];

    protected $casts = [
        'spare_parts' => 'array',
        'images' => 'array',
        'service_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
