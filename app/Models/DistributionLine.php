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

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($line) {
            // Calculate the change in quantity for the line
            $quantityChange = $line->qty_line - $line->getOriginal('qty_line');
            $volumeChange = $line->volume_line - $line->getOriginal('volume_line');

            // Update the header's total_quantity column by adding the change
            $header = $line->distributionHeader;

            // If the quantityChange is positive (added), then add it to the total_quantity
            if ($quantityChange > 0) {
                $header->qty += $quantityChange;
            } elseif ($quantityChange < 0) { // If the quantityChange is negative (subtracted), then subtract it from the total_quantity
                $header->qty -= abs($quantityChange);
            }
            if ($volumeChange > 0) {
                $header->volume += $volumeChange;
            } elseif ($volumeChange < 0) {
                $header->volume -= abs($volumeChange);
            }
            $header->save();
        });
    }

    public function distributionHeader()
    {
        return $this->belongsTo(DistributionHeader::class, 'id_distribution_header');
    }
}
