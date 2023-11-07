<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionHeader extends Model
{
    use HasFactory;
    protected $table = 'distribution_header';
    protected $primaryKey = 'id_distribution_header';
    protected $guarded = [];

    public function distributionLines()
    {
        return $this->hasMany(DistributionLine::class, 'id_distribution_header');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id_city');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id_client');
    }

    public function truckCategory()
    {
        return $this->belongsTo(TruckCategory::class, 'id_truck_category', 'id');
    }

    public function distributionType()
    {
        return $this->belongsTo(DistributionType::class, 'id_type_distribution');
    }
}
