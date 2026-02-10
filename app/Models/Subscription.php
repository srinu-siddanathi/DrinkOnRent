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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            // If status is active but no start_date is set, set it to now
            if ($subscription->status === 'active' && !$subscription->start_date) {
                $subscription->start_date = now();
            }

            // If status is active but no end_date is set, calculate it from plan
            if ($subscription->status === 'active' && !$subscription->end_date && $subscription->plan) {
                $subscription->end_date = $subscription->start_date->addDays($subscription->plan->duration_in_days);
            }

            // If litres_remaining is not set, get it from the plan
            if (!$subscription->litres_remaining && $subscription->plan) {
                $subscription->litres_remaining = $subscription->plan->litres;
            }
        });
    }

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