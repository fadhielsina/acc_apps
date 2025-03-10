<?php

namespace App\Filament\Resources\MasterKategoriCoaResource\Pages;

use App\Filament\Resources\MasterKategoriCoaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterKategoriCoa extends EditRecord
{
    protected static string $resource = MasterKategoriCoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
