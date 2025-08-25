<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryZone extends Model
{
     use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'type',
        'coordinates',
        'center_lat',
        'center_lng',
        'radius_meters',
        'name',
        'is_active',
        'min_lat','max_lat','min_lng','max_lng',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
