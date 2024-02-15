<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'near_facility_id',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'near_facility_id');
    }
}
