<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'opname_number',
        'opname_date',
        'status',
        'notes',
    ];

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
}
