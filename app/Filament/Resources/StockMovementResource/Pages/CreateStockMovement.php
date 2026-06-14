<?php

namespace App\Filament\Resources\StockMovementResource\Pages;

use App\Filament\Resources\StockMovementResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;
use App\Models\StockMovement;
use Filament\Notifications\Notification;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 🔴 HANDLE STOCK OUT (FEFO AUTO)
        if ($data['type'] === 'OUT') {

            $item = Item::find($data['item_id']);
            $qtyNeeded = $data['qty'];

            // 🔥 VALIDASI DI DEPAN (WAJIB)
            $totalStock = $item->getStock();

            if ($totalStock < $qtyNeeded) {

                Notification::make()
                    ->title('Stock tidak cukup')
                    ->danger()
                    ->body("Stock tersedia: {$totalStock}, diminta: {$qtyNeeded}")
                    ->send();

                $this->halt();
                return $data;
            }

            // 🔥 BARU FEFO JALAN
            $batches = $item->getStockByBatch()
                ->filter(fn ($b) => $b->stock > 0)
                ->values();

            foreach ($batches as $batch) {

                if ($qtyNeeded <= 0) break;

                $takeQty = min($qtyNeeded, $batch->stock);

                if ($takeQty <= 0) continue;

                StockMovement::create([
                    'item_id' => $item->id,
                    'type' => 'OUT',
                    'qty' => $takeQty,
                    'batch_number' => $batch->batch_number,
                    'expired_at' => $batch->expired_at,
                    'date' => $data['date'],
                    'source' => $data['source'],
                    'notes' => $data['notes'] ?? null,
                ]);

                $qtyNeeded -= $takeQty;
            }

            Notification::make()
                ->title('Stock movement created')
                ->success()
                ->send();

            $this->halt();
            return $data;
        }
    }
}