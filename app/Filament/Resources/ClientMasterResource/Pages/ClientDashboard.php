<?php

namespace App\Filament\Resources\ClientMasterResource\Pages;

use App\Filament\Resources\ClientMasterResource;
use Filament\Resources\Pages\Page;
use App\Models\ClientMaster;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ClientDashboard extends ViewRecord
{
    protected static string $resource = ClientMasterResource::class;

    protected static string $view = 'filament.resources.client-master-resource.pages.client-dashboard';
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

     protected function getFooterActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->url(static::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
