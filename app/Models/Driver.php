<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';
    protected $primaryKey = 'id_driver';
    protected $guarded = [];

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'mapping_driver_vehicle');
    }

    public function arrivals()
    {
        $this->hasMany(Arrival::class);
    }

}
