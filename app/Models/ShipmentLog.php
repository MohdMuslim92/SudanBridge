<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'token',
        'user_id',
        'action',
        'old_data',
        'new_data'
    ];

    public function shipment() {
        return $this->belongsTo(Shipment::class);
    }

}
