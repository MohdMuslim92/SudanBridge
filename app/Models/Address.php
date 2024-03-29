<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;
use App\Models\Locality;

class Address extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street',
        'state_id',
        'locality_id',
        'details',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class, 'locality_id');
    }
}
