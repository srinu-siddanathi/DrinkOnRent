<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plan_id',
        'purifier_id',
        'payment_status',
        'start_date',
        'end_date',
        'status',
        'litres_consumed',
        'litres_remaining',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'litres_consumed' => 'integer',
        'litres_remaining' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function purifier(): BelongsTo
    {
        return $this->belongsTo(Purifier::class);
    }
} 