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
        'support_request_id',
        'service_date',
        'next_service_reminder',
        'expiry_date',
        'spare_parts',
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

    public function supportRequest(): BelongsTo
    {
        return $this->belongsTo(SupportRequest::class);
    }
}
