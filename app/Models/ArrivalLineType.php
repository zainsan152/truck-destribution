<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalLineType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function arrival_lines()
    {
        $this->hasMany(ArrivalLine::class);
    }
}
