<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function arrival_types()
    {
        return $this->belongsTo(ArrivalType::class, 'arrival_type_id');
    }

    public function clients()
    {
        return $this->hasOne(Client::class, 'id_client', 'client_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function drivers()
    {
        return $this->belongsTo(Driver::class);
    }
    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
