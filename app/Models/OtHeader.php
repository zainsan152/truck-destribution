<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtHeader extends Model
{
    use HasFactory;
    protected $table = 'ot_header';
    protected $primaryKey = 'id_ot_header';
    protected $guarded = [];

    public function OtLines()
    {
        return $this->hasMany(OtLine::class, 'id_ot_header');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id_city');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id_client', 'id_client');
    }

    public function truckCategory()
    {
        return $this->belongsTo(TruckCategory::class, 'id_truck_category', 'id');
    }

    public function otType()
    {
        return $this->belongsTo(DistributionType::class, 'id_type_ot');
    }
}
