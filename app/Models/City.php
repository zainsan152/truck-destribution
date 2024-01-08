<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'city';
    protected $primaryKey = 'id_city';

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_city', 'id_city');
    }

    public function distributionHeader()
    {
        return $this->belongsTo(DistributionHeader::class, 'id_city');
    }

    public function arrivals()
    {
        $this->hasMany(Arrival::class);
    }
}
