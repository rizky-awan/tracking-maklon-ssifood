<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMaster extends Model
{
    use HasFactory;

    public function isDone($value)
{
    return in_array(trim(strtoupper($value)), [
        'CLIENT APPROVE',
        'APPROVE',
        'DONE',
        'WITHOUT',
        'NOT USE/FROM CLIENT',
    ]);
}

    // =========================
    // FILLABLE
    // =========================
    protected $fillable = [
        'brand_name',
        'client_name',
        'product_type',
        'variant',
        'category',
        'payment_status',
        'pic',
        'design_from',

        // payment dates
        'sample_payment_date',
        'lab_legal_dp_date',
        'dp_50_date',
        'full_payment_date',
        'status',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function formula()
    {
        return $this->hasOne(Formula::class);
    }

    public function legal()
    {
        return $this->hasOne(Legal::class);
    }

    public function design()
    {
        return $this->hasOne(Design::class);
    }

    public function purchasing()
    {
        return $this->hasOne(Purchasing::class);
    }

    public function production()
    {
        return $this->hasOne(Production::class);
    }

    // =========================
    // PROGRESS: FORMULA
    // =========================
    public function getFormulaProgressAttribute()
    {
        if ($this->category === 'REPEAT') {
        return 100;
        }

        if (!$this->formula) return 0;

        $f = $this->formula;

        $fields = [
            $f->formula_progress === 'Ready',
            $f->availability === 'AVAILABLE',
            $f->status === 'Sample Approove',
            $f->cpb_status === 'DONE',
        ];

        return (collect($fields)->filter()->count() / count($fields)) * 100;
    }

    // =========================
    // PROGRESS: LEGAL
    // =========================
    public function getLegalProgressAttribute()
    {
        if ($this->category === 'REPEAT') {
        return 100;
        }

        if (!$this->legal) return 0;

        $l = $this->legal;

        $fields = [
            $l->contract_kirim === 'SUDAH',
            $l->contract_terima === 'SUDAH',
            $l->lab_test === 'DONE',
            $l->ingredients === 'SUDAH',
            $l->nutrition_fact === 'SUDAH',
            $l->checking_label === 'SUDAH',
            $l->status_legal === 'NIE PASS',
            $l->bpom === 'SUDAH',
            $l->barcode === 'SUDAH',
            $l->status_label === 'APPROVE',
            // $l->print1 === 'APPROVE',
            // $l->print2 === 'APPROVE',
            $this->isDone($l->print1),
            $this->isDone($l->print2),
        ];

        return (collect($fields)->filter()->count() / count($fields)) * 100;
    }

    // =========================
    // PROGRESS: DESIGN
    // =========================
    public function getDesignProgressAttribute()
    {
        if ($this->category === 'REPEAT') {
        return 100;
        }

        if (!$this->design) return 0;

        if ($this->design_from === 'CLIENT') return 100;

        $d = $this->design;

        $fields = [
            $d->design_option,
            $d->create_mockup,
            $d->review_client,
            $this->isDone($d->design_1st_packaging),
            $this->isDone($d->design_2nd_packaging),
            $d->regulator_status === 'APPROVE',
            // !empty($d->status),
        ];

        return (collect($fields)->filter()->count() / count($fields)) * 100;
    }

    // =========================
    // PROGRESS: PURCHASING
    // =========================
    public function getPurchasingProgressAttribute()
    {
        if (!$this->purchasing) return 0;

        $p = $this->purchasing;
        $score = 0;

        if ($p->raw_material === 'AVAILABLE') $score++;
        if ($p->price_1st_packaging === 'PRICE CONFIRMED') $score++;
        if ($p->price_2nd_packaging === 'PRICE CONFIRMED') $score++;
        if ($p->dummy_1) $score++;
        if ($p->dummy_2) $score++;
        if ($p->approve_dummy_1 === 'APPROVE') $score++;
        if ($p->approve_dummy_2 === 'APPROVE') $score++;
        if ($p->final_design === 'SUBMIT') $score++;
        if ($p->po_status === 'PURCHASE ORDER') $score++;
        if ($p->printing_approve === 'APPROVE') $score++;

        return ($score / 10) * 100;
    }

    // =========================
    // PROGRESS: PRODUCTION
    // =========================
    public function getProductionProgressAttribute()
    {
        if (!$this->production) return 0;

        $p = $this->production;
        $score = 0;

        if ($p->balanced) $score++;
        if ($p->mixing) $score++;
        if ($p->filling) $score++;
        if ($p->packing) $score++;
        if ($p->sending) $score++;
        if ($p->client_receive) $score++;

        return ($score / 6) * 100;
    }

    // =========================
    // TOTAL PROGRESS
    // =========================
    public function getTotalProgressAttribute()
    {
        return round((
            $this->formula_progress +
            $this->legal_progress +
            $this->design_progress +
            $this->purchasing_progress +
            $this->production_progress
        ) / 5, 2);
    }

    public function getStatusAttribute($value)
    {
        if ($this->total_progress == 100) {
            return 'DONE';
        }

        return $value;
    }
}