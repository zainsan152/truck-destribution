<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalLine extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function arrival_line_types()
    {
        $this->belongsTo(ArrivalLineType::class);
    }
}
