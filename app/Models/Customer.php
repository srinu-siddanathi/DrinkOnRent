<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'is_phone_verified',
    ];

    protected $casts = [
        'is_phone_verified' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->latest('end_date');
    }

    public function supportRequests()
    {
        return $this->hasMany(SupportRequest::class);
    }

    public function purifiers(): HasMany
    {
        return $this->hasMany(Purifier::class);
    }
} 