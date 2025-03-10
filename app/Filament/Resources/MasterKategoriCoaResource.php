<?php

namespace App\Filament\Resources;

use App\Filament\Exports\MasterKategoriCoaExporter;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MasterKategoriCoa;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MasterKategoriCoaResource\Pages;
use App\Filament\Resources\MasterKategoriCoaResource\RelationManagers;

class MasterKategoriCoaResource extends Resource
{
    protected static ?string $model = MasterKategoriCoa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $modelLabel = 'Kategori COA';
    protected static ?string $navigationLabel = 'Master Kategori COA';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Tombol Hapus
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(MasterKategoriCoaExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterKategoriCoas::route('/'),
            'create' => Pages\CreateMasterKategoriCoa::route('/create'),
            'edit' => Pages\EditMasterKategoriCoa::route('/{record}/edit'),
        ];
    }
}
