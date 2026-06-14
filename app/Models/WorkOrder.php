<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'client_master_id',
        'product_name',
        'status',
        
    ];

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function clientMaster()
    {
        return $this->belongsTo(\App\Models\ClientMaster::class);
    }

}
