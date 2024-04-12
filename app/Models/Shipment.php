<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function ($shipment) {
            // Manually delete related records from other tables

            // Delete the sender's address
            $shipment->sender->address()->delete();

            // Delete the recipient's address
            $shipment->recipient->address()->delete();

            // Delete the related item record
            $shipment->item()->delete();

            // Delete the related sender record
            $shipment->sender()->delete();

            // Delete the related recipient record
            $shipment->recipient()->delete();

        });
    }

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
        'status_id',
        'origin_facility_id',
        'current_facility_id',
        'tracking_token',
        'qr_code_image',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'origin_facility_id');
    }

    public function currentFacility()
    {
        return $this->belongsTo(Facility::class, 'current_facility_id');
    }

    public function logs() {
        return $this->hasMany(Logs::class);
    }

    public function createLog($userId, $shipmentId, $shipmentToken, $action, $oldData, $newData) {
        $this->logs()->create([
            'user_id' => $userId,
            'shipment_id' => $shipmentId,
            'token' => $shipmentToken,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

}
