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
        return $this->hasOne(Address::class);
    }

    public function facility()
    {
        return $this->hasOne(Facility::class);
    }
}
