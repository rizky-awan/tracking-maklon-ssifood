<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    use HasFactory;

    protected $table = 'purchasings';

    protected $fillable = [
        'client_master_id',
        'brand_name',
        'client_name',
        'product_type',
        'variant',
        'raw_material',
        'price_1st_packaging',
        'price_2nd_packaging',
        'dummy_1',
        'dummy_2',
        'approve_dummy_1',
        'approve_dummy_2',
        'final_design',
        'po_status',
        'printing_approve',
    ];

    public function clientMaster()
    {
        return $this->belongsTo(ClientMaster::class);
    }

    public function getProgressAttribute()
    {
        $fields = [
            $this->raw_material === 'AVAILABLE',
            $this->price_1st_packaging === 'PRICE CONFIRMED',
            $this->price_2nd_packaging === 'PRICE CONFIRMED',
            $this->dummy_1,
            $this->dummy_2,
            $this->approve_dummy_1 === 'APPROVE',
            $this->approve_dummy_2 === 'APPROVE',
            $this->final_design === 'SUBMIT',
            $this->po_status === 'PURCHASE ORDER',
            $this->printing_approve === 'APPROVE',
        ];

        return (collect($fields)->filter()->count() / 10) * 100;
    }
}
