<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\MasterCoa;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MasterKategoriCoa;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\MasterCoaExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\MasterCoaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MasterCoaResource\RelationManagers;

class MasterCoaResource extends Resource
{
    protected static ?string $model = MasterCoa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $modelLabel = 'COA';
    protected static ?string $navigationLabel = 'Master COA';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Kode COA'),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama COA'),
                Select::make('id_master_kategori_coa')
                    ->label('Kategori COA')
                    ->options(MasterKategoriCoa::pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->searchable()->label('Kode COA'),
                TextColumn::make('nama')->searchable()->label('Nama COA'),
                TextColumn::make('kategoriCoa.nama')->label('Kategori COA'),
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
                    ->exporter(MasterCoaExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
                    ->exporter(MasterCoaExporter::class)->formats([
                        ExportFormat::Xlsx,
                    ])
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
            'index' => Pages\ListMasterCoas::route('/'),
            'create' => Pages\CreateMasterCoa::route('/create'),
            'edit' => Pages\EditMasterCoa::route('/{record}/edit'),
        ];
    }
}
