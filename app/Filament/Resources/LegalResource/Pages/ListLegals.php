<?php

namespace App\Filament\Resources\LegalResource\Pages;

use App\Filament\Resources\LegalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegals extends ListRecords
{
    

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected static string $resource = LegalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
