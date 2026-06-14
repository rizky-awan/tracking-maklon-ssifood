<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $table = 'productions';

    protected $fillable = [
        'client_master_id',
        'brand_name',
        'client_name',
        'product_type',
        'variant',
        'dp_date',
        'balanced',
        'balanced_date',
        'mixing',
        'mixing_date',
        'filling',
        'filling_date',
        'packing',
        'packing_date',
        'estimasi_ready',
        'sending',
        'sending_date',
        'client_receive',
        'status',
    ];

    public function clientMaster()
    {
        return $this->belongsTo(ClientMaster::class);
    }

    public function getProgressAttribute()
    {
        $fields = [
            $this->balanced,
            $this->mixing,
            $this->filling,
            $this->packing,
            $this->sending,
            $this->client_receive,
        ];

        return (collect($fields)->filter()->count() / 6) * 100;
    }
}
