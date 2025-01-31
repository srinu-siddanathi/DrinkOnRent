<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'litres_per_month',
        'price',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
} 