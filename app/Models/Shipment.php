<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'sender_id',
        'recipient_id',
        'user_id',
        'tracking_token',
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }
    public function sender()
    {
        return $this->hasOne(Sender::class);
    }
    public function recipient()
    {
        return $this->hasOne(Recipient::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
