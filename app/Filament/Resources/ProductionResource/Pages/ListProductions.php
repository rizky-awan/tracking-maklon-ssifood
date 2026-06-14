<?php

namespace App\Filament\Resources\ProductionResource\Pages;

use App\Filament\Resources\ProductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductions extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
