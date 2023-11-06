<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'truck_category';

    public function distributionHeader()
    {
        return $this->hasOne(DistributionHeader::class, 'id_truck_category', 'id');
    }
}
