<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'clients';

    protected $primaryKey = 'id_client';

    public function city()
    {
        return $this->hasOne(City::class, 'id_city', 'id_city');
    }

    public function distributionHeader()
    {
        return $this->belongsTo(DistributionHeader::class, 'id_client');
    }
}
