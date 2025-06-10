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
        'purifier_type', // 'ro' or 'alkaline'
        'litres',
        'price',
        'duration_in_days',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'litres' => 'integer',
        'duration_in_days' => 'integer',
        'is_active' => 'boolean'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
} 