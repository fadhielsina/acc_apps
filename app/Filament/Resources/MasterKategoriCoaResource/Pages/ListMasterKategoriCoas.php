<?php

namespace App\Filament\Resources\MasterKategoriCoaResource\Pages;

use App\Filament\Resources\MasterKategoriCoaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterKategoriCoas extends ListRecords
{
    protected static string $resource = MasterKategoriCoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
