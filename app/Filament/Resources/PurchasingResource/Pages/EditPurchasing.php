<?php

namespace App\Filament\Resources\PurchasingResource\Pages;

use App\Filament\Resources\PurchasingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchasing extends EditRecord
{
    protected static string $resource = PurchasingResource::class;

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
}
