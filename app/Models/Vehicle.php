<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle_fleet';
    protected $primaryKey = 'id_vehicle';
    protected $guarded = [];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'mapping_driver_vehicle');
    }

    public function arrivals()
    {
        $this->hasMany(Arrival::class);
    }
}
