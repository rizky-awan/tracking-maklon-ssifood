<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legal extends Model
{
    use HasFactory;

    protected $table = 'legals';

    protected $fillable = [
        'client_master_id',
        'brand_name',
        'client_name',
        'product_type',
        'variant',
        'contract_kirim',
        'contract_terima',
        'lab_test',
        'ingredients',
        'nutrition_fact',
        'checking_label',
        'status_legal',
        'bpom',
        'barcode',
        'status_label',
        'print1',
        'print2',
    ];

    public function clientMaster()
    {
        return $this->belongsTo(ClientMaster::class);
    }

    public function getProgressAttribute()
    {
        $fields = [
            $this->contract_kirim === 'SUDAH',
            $this->contract_terima === 'SUDAH',
            $this->lab_test === 'DONE',
            $this->ingredients === 'SUDAH',
            $this->nutrition_fact === 'SUDAH',
            $this->checking_label === 'SUDAH',
            $this->status_legal === 'NIE PASS',
            $this->bpom === 'SUDAH',
            $this->barcode === 'SUDAH',
            $this->status_label === 'APPROVE',
            $this->print1 === 'APPROVE',
            $this->print2 === 'APPROVE',
        ];

        return (collect($fields)->filter()->count() / 12) * 100;
    }
}
