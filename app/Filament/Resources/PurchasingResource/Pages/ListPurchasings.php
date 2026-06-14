<?php

namespace App\Filament\Resources\PurchasingResource\Pages;

use App\Filament\Resources\PurchasingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchasings extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
