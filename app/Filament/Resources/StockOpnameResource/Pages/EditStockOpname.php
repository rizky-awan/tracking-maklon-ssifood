<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Item;
use App\Models\StockOpnameDetail;
use Filament\Notifications\Notification;
use App\Models\StockMovement;
use Filament\Actions\Action;

class EditStockOpname extends EditRecord
{
    protected static string $resource = StockOpnameResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [

            // Generate Items
            Actions\Action::make('generateItems')
                ->label('Generate Items')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    foreach (Item::all() as $item) {

                        StockOpnameDetail::firstOrCreate(
                            [
                                'stock_opname_id' => $this->record->id,
                                'item_id' => $item->id,
                            ],
                            [
                                'system_qty' => $item->getStock(),
                                'physical_qty' => 0,
                                'difference' => 0,
                            ]
                        );
                    }

                    Notification::make()
                        ->title('Items generated successfully')
                        ->success()
                        ->send();

                    $this->redirect(
                        StockOpnameResource::getUrl('edit', [
                            'record' => $this->record,
                        ])
                    );
            }),

            // Approve Opname
            Actions\Action::make('approve')
                ->label('Approve Opname')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    foreach ($this->record->details as $detail) {

                        if ($detail->difference == 0) {
                            continue;
                        }

                        \App\Models\StockMovement::create([
                            'item_id' => $detail->item_id,

                            'type' => $detail->difference > 0
                                ? 'IN'
                                : 'OUT',

                            'qty' => abs($detail->difference),

                            'date' => now(),

                            'source' => 'stock opname',

                            'notes' => 'Adjustment Opname #' .
                                $this->record->opname_number,
                        ]);
                    }

                    $this->record->update([
                        'status' => 'approved',
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Stock Opname Approved')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
