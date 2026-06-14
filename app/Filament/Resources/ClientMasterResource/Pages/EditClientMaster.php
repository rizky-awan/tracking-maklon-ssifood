<?php

namespace App\Filament\Resources\ClientMasterResource\Pages;

use App\Filament\Resources\ClientMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientMaster extends EditRecord
{
    protected static string $resource = ClientMasterResource::class;

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
