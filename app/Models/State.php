<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Locality;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function localities()
    {
        return $this->hasMany(Locality::class);
    }

}
