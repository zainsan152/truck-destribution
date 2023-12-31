<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function arrivals()
    {
        return $this->hasMany(Arrival::class);
    }
}
