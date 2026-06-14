<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;

    protected $table = 'designs';

    protected $fillable = [
        'client_master_id',
        'brand_name',
        'client_name',
        'product_type',
        'variant',
        'design_option',
        'create_mockup',
        'review_client',
        'design_1st_packaging',
        'design_2nd_packaging',
        'regulator_status',
    ];

    public function clientMaster()
    {
        return $this->belongsTo(ClientMaster::class);
    }

    public function getProgressAttribute()
    {
        if ($this->clientMaster?->design_from === 'CLIENT') {
            return 100;
        }

        $fields = [
            $this->design_option,
            $this->create_mockup,
            $this->review_client,
            $this->design_1st_packaging === 'CLIENT APPROVE',
            $this->design_2nd_packaging === 'CLIENT APPROVE',
            $this->regulator_status === 'APPROVE',
        ];

        return (collect($fields)->filter()->count() / 6) * 100;
    }
}
