<?php

namespace App\Filament\Resources\FormulaResource\Pages;

use App\Filament\Resources\FormulaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormula extends EditRecord
{
    protected static string $resource = FormulaResource::class;

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
