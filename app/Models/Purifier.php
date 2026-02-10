<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Purifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'serial_number',
        'model',
        'type',
        'installation_date',
        'last_service_date',
        'next_service_date',
        'latitude',
        'longitude',
        'location_address',
        'has_rtc_error',
        'rtc_error_updated_at'
    ];

    protected $casts = [
        'installation_date' => 'datetime',
        'last_service_date' => 'datetime',
        'next_service_date' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'has_rtc_error' => 'boolean',
        'rtc_error_updated_at' => 'datetime'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($purifier) {
            if (empty($purifier->serial_number)) {
                // Get the last serial number and increment it
                $lastPurifier = self::orderBy('id', 'desc')->first();
                $lastNumber = $lastPurifier ? intval(substr($lastPurifier->serial_number, 0)) : 9999;
                $purifier->serial_number = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTypeNameAttribute()
    {
        return ucfirst($this->type);
    }
} 