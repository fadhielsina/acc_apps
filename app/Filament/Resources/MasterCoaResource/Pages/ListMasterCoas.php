<?php

namespace App\Filament\Resources\MasterCoaResource\Pages;

use App\Filament\Resources\MasterCoaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterCoas extends ListRecords
{
    protected static string $resource = MasterCoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
