<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class EditWorkOrder extends EditRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),

            Actions\Action::make('close')
            ->label('Close')
            ->icon('heroicon-o-x-mark')
            ->color('gray')
            ->url(fn () => static::getResource()::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function beforeSave(): void
    {
        if ($this->record->status !== 'released') return;

        $this->record->load('items.item');

        $errors = [];

        foreach ($this->record->items as $item) {

            $available = $item->item->getStock() - $item->item->getReservedStock();

            if ($item->qty_required > $available) {
                $errors[] = $item->item->name;
            }
        }

        if (!empty($errors)) {

            throw ValidationException::withMessages([
                'items' => 'Stock tidak cukup untuk: ' . implode(', ', $errors)
            ]);
        }
    }

    protected function afterSave(): void
    {
        // hanya jalan kalau DONE
        if ($this->record->status !== 'done') {
            return;
        }

        // 🔥 anti double OUT
        $exists = \App\Models\StockMovement::where('reference_type', 'WORK_ORDER')
            ->where('reference_id', $this->record->id)
            ->exists();

        if ($exists) return;

        // 🔥 load relasi biar aman
        $this->record->load('items.item');

        foreach ($this->record->items as $item) {

            $qtyNeeded = $item->qty_required;

            $batches = $item->item->getStockByBatch();

            foreach ($batches as $batch) {

                if ($qtyNeeded <= 0) break;

                $takeQty = min($qtyNeeded, $batch->stock);

                \App\Models\StockMovement::create([
                    'item_id' => $item->item_id,
                    'type' => 'OUT',
                    'qty' => $takeQty,
                    'batch_number' => $batch->batch_number,
                    'expired_at' => $batch->expired_at,
                    'date' => now(),
                    'source' => 'production',
                    'reference_type' => 'WORK_ORDER',
                    'reference_id' => $this->record->id,
                ]);

                $qtyNeeded -= $takeQty;
            }
        }
    }
    // protected function afterSave(): void
    // {
    //     $oldStatus = $this->record->status;
    //     $newStatus = $this->data['status'] ?? null;

    //     // ❌ kalau sebelumnya DONE → ga boleh diubah ke apapun
    //     if ($oldStatus === 'Done' && $newStatus !== 'Done') {

    //         \Filament\Notifications\Notification::make()
    //             ->title('WO sudah selesai')
    //             ->body('Work Order yang sudah DONE tidak bisa diubah')
    //             ->danger()
    //             ->send();

    //         $this->halt();
    //     }
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === 'released') {

            $this->record->load('items.item');

            $errors = [];

            foreach ($this->record->items as $item) {

                $available = $item->item->getStock() - $item->item->getReservedStock();

                if ($item->qty_required > $available) {
                    $errors[] = $item->item->name;
                }
            }

            if (!empty($errors)) {

                // 🔥 NOTIF (ini yang lo tanya)
                Notification::make()
                    ->title('Stock tidak cukup')
                    ->body('Item : ' . implode(', ', $errors))
                    ->danger()
                    ->send();

                // 🔥 BLOCK SAVE
                throw ValidationException::withMessages([
                    'items' => 'Stock tidak cukup'
                ]);
            }
        }

        return $data;
    }

}
