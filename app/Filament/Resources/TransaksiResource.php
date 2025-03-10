<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\MasterCoa;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->label('Tanggal Transaksi')
                    ->required(),

                // Select::make('id_master_coa')
                //     ->label('COA')
                //     ->options(MasterCoa::pluck('nama', 'id'))
                //     ->searchable()
                //     ->required(),

                Select::make('id_master_coa')
                    ->label('COA')
                    ->options(function () {
                        $coas = MasterCoa::all()->groupBy(function ($coa) {
                            if (str_starts_with($coa->kode, '4')) {
                                return 'Income';
                            } elseif (str_starts_with($coa->kode, '6')) {
                                return 'Expense';
                            }
                            return 'Other';
                        });

                        return $coas->mapWithKeys(function ($items, $group) {
                            return [$group => $items->mapWithKeys(fn($coa) => [
                                $coa->id => "{$coa->kode} - {$coa->nama}"
                            ])];
                        })->toArray();
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('debit')
                    ->label('Debit')
                    ->numeric()
                    ->inputMode('decimal') // Mode input angka
                    ->prefix('Rp') // Menampilkan prefix mata uang
                    ->default(0)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->required(),

                TextInput::make('kredit')
                    ->label('Kredit')
                    ->numeric()
                    ->inputMode('decimal') // Mode input angka
                    ->prefix('Rp') // Menampilkan prefix mata uang
                    ->default(0)
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->required(),


                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')->label('Tanggal')->sortable()->date('d/m/Y'),
                TextColumn::make('masterCoa.kode')->label('COA Kode')->sortable(),
                TextColumn::make('masterCoa.nama')->label('COA Nama')->sortable(),
                TextColumn::make('deskripsi')->label('Deskripsi')->limit(50),
                TextColumn::make('debit')
                    ->label('Debit')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')) // Format tanpa desimal & tanpa IDR
                    ->alignEnd(), // Rata kanan agar lebih rapi

                TextColumn::make('kredit')
                    ->label('Kredit')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')) // Format tanpa desimal & tanpa IDR
                    ->alignEnd(),
            ])->defaultSort('tanggal', 'ASC')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Tombol Hapus
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
