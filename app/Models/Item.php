<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'unit',
        'min_stock',
        'track_expiry',
        'producer',
    ];

    public function getStockByBatch()
    {
        return \DB::table('stock_movements')
            ->select(
                'batch_number',
                'expired_at', // 🔥 WAJIB INI
                \DB::raw("
                    SUM(CASE WHEN type = 'IN' THEN qty ELSE 0 END) -
                    SUM(CASE WHEN type = 'OUT' THEN qty ELSE 0 END)
                    as stock
                ")
            )
            ->where('item_id', $this->id)
            ->groupBy('batch_number', 'expired_at') // 🔥 HARUS INCLUDE
            ->having('stock', '>', 0)
            ->orderBy('expired_at', 'asc') // FEFO
            ->get();
    }

    public function getBatchWithStatus()
    {
        return $this->getStockByBatch()->map(function ($batch) {

            $days = now()->diffInDays($batch->expired_at, false);

            if ($days < 0) {
                $batch->status = 'expired';
            } elseif ($days <= 30) {
                $batch->status = 'near_expiry';
            } else {
                $batch->status = 'safe';
            }

            return $batch;
        });
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStock(): int
    {
        return
            $this->movements()->where('type', 'IN')->sum('qty')
            -
            $this->movements()->where('type', 'OUT')->sum('qty');
    }

    public function getReservedStock(): int
    {
        return \App\Models\WorkOrderItem::where('item_id', $this->id)
            ->whereHas('workOrder', function ($q) {
                $q->whereIn('status', ['released', 'in_progress']);
            })
            ->sum('qty_required');
    }

    public function getAvailableStock(): int
    {
        return $this->getStock() - $this->getReservedStock();
    }
}
