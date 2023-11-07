<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionType extends Model
{
    use HasFactory;
    protected $table = 'type_distribution';
    protected $guarded = [];

    public function distributionHeader()
    {
        return $this->belongsTo(DistributionHeader::class, 'id_type_distribution');
    }
}
