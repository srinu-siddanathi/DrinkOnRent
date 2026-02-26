<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'phone',
        'email',
        'address',
        'area',
        'next_service_reminder',
        'id_proof',
        'is_phone_verified',
    ];

    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

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

    public function purifierRequests()
    {
        return $this->hasMany(PurifierRequest::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function latestService(): HasOne
    {
        return $this->hasOne(Service::class)->latest('service_date');
    }
}
