<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_opname_id',
        'item_id',
        'system_qty',
        'physical_qty',
        'difference',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    protected static function booted()
    {
        static::saving(function ($detail) {
            $detail->difference =
                $detail->physical_qty - $detail->system_qty;
        });
    }
}
