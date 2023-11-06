<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionLine extends Model
{
    use HasFactory;
    protected $table = 'distribution_lines';
    protected $primaryKey = 'id_distribution_line';
    protected $guarded = [];

    public function distributionHeader()
    {
        return $this->belongsTo(DistributionHeader::class, 'id_distribution_header');
    }
}
