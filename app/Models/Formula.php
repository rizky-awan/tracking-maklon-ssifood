<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;

    protected $table = 'formulas';

    protected $fillable = [
    'client_master_id', 

    'brand_name',
    'client_name',
    'product_type',
    'variant',

    'formula_progress',
    'availability',
    'status',
    'cpb_status',
];

    public function clientMaster()
    {
        return $this->belongsTo(ClientMaster::class);
    }

     public function getProgressAttribute()
    {
        $score = 0;

        if ($this->formula_progress === 'Ready') $score++;
        if ($this->availability === 'AVAILABLE') $score++;
        if ($this->status === 'Sample Approove') $score++;
        if ($this->cpb_status === 'DONE') $score++;

        return ($score / 4) * 100;
    }
}
