<?php

namespace App\Filament\Resources\FormulaResource\Pages;

use App\Filament\Resources\FormulaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormulas extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static string $resource = FormulaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
