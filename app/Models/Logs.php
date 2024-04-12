<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipment_id',
        'token',
        'action',
        'old_data',
        'new_data'
    ];

    public function user() {
        return $this->belongsTo(User::class);

    }

    public function shipment() {
        return $this->belongsTo(Shipment::class);

    }
}
