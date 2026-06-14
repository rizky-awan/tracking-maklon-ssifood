<?php

namespace App\Filament\Resources\ClientMasterResource\Pages;

use App\Filament\Resources\ClientMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientMasters extends ListRecords
{
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static string $resource = ClientMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
