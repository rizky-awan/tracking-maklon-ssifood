<?php

namespace App\Observers;

use App\Models\ClientMaster;
use App\Models\Formula;
use App\Models\Legal;
use App\Models\Design;
use App\Models\Purchasing;
use App\Models\Production;

class ClientMasterObserver
{
    /**
     * Handle the ClientMaster "created" event.
     */
    public function created(ClientMaster $clientMaster): void
    {
        $payment = $clientMaster->payment_status;
        $category = $clientMaster->category;

        // =========================
        // REPEAT
        // =========================
        if ($category === 'REPEAT') {
            if (in_array($payment, ['DP 50%', 'TERM OF PAYMENT', 'FULL PAYMENT'])) {

                Production::updateOrCreate(
                    ['client_master_id' => $clientMaster->id],
                    $this->mapClient($clientMaster)
                );

                Purchasing::updateOrCreate(
                ['client_master_id' => $clientMaster->id],
                $this->mapClient($clientMaster)
            );
            }
            return;
        }

        // =========================
        // FORMULA
        // =========================
        if (in_array($payment, [
            'SAMPLE PAYMENT',
            'FREE SAMPLE',
            'LAB, LEGAL + 25% DP',
            'DP 50%',
            'TERM OF PAYMENT',
            'FULL PAYMENT'
        ])) {

            Formula::updateOrCreate(
                ['client_master_id' => $clientMaster->id],
                array_merge($this->mapClient($clientMaster), [
                    'formula_progress' => 'Queue'
                ])
            );
        }

        // =========================
        // LEGAL + PURCHASING + DESIGN
        // =========================
        if (in_array($payment, [
            'LAB, LEGAL + 25% DP',
            'DP 50%',
            'TERM OF PAYMENT',
            'FULL PAYMENT'
        ])) {

            Legal::updateOrCreate(
                ['client_master_id' => $clientMaster->id],
                $this->mapClient($clientMaster)
            );

            Purchasing::updateOrCreate(
                ['client_master_id' => $clientMaster->id],
                $this->mapClient($clientMaster)
            );

            if ($clientMaster->design_from !== 'CLIENT') {
                Design::updateOrCreate(
                    ['client_master_id' => $clientMaster->id],
                    $this->mapClient($clientMaster)
                );
            }
        }

        // =========================
        // PRODUCTION
        // =========================
        if (in_array($payment, [
            'DP 50%',
            'TERM OF PAYMENT',
            'FULL PAYMENT'
        ])) {

            Production::updateOrCreate(
                ['client_master_id' => $clientMaster->id],
                $this->mapClient($clientMaster)
            );
        }
    }

    /**
     * Handle the ClientMaster "updated" event.
     */
    public function updated(ClientMaster $clientMasterMaster): void
    {
        //
    }

    /**
     * Handle the ClientMaster "deleted" event.
     */
    public function deleted(ClientMaster $clientMasterMaster): void
    {
        //
    }

    /**
     * Handle the ClientMaster "restored" event.
     */
    public function restored(ClientMaster $clientMasterMaster): void
    {
        //
    }

    /**
     * Handle the ClientMaster "force deleted" event.
     */
    public function forceDeleted(ClientMaster $clientMasterMaster): void
    {
        //
    }

    private function mapClient(ClientMaster $clientMaster)
    {
        return [
            'brand_name' => $clientMaster->brand_name,
            'client_name' => $clientMaster->client_name,
            'product_type' => $clientMaster->product_type,
            'variant' => $clientMaster->variant,
            'category' => $clientMaster->category,
            'payment_status' => $clientMaster->payment_status,
            'pic' => $clientMaster->pic,
            'design_from' => $clientMaster->design_from,

            // payment dates
            'sample_payment_date' => $clientMaster->sample_payment_date,
            'lab_legal_dp_date' => $clientMaster->lab_legal_dp_date,
            'dp_50_date' => $clientMaster->dp_50_date,
            'full_payment_date' => $clientMaster->full_payment_date,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->total_progress == 100) {
                $model->status = 'DONE';
            }
        });
    }
}
