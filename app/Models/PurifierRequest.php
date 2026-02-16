<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurifierRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'purifier_type',
        'latitude',
        'longitude',
        'location_address',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}