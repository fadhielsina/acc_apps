<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\MasterKategoriCoa;
use App\Models\Transaksi;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LaporanResource extends Resource
{
    protected static ?string $model = MasterKategoriCoa::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Profit & Loss';
    protected static ?string $navigationGroup = 'Keuangan';

    public static function table(Tables\Table $table): Tables\Table
    {
        // Ambil daftar bulan unik dari transaksi
        $bulanList = Transaksi::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') AS bulan")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('bulan')
            ->toArray();

        return $table
            ->query(
                MasterKategoriCoa::query()
                    ->with(['coa.transaksi' => function ($query) {
                        $query->selectRaw("
                            master_coa.id_master_kategori_coa,
                            DATE_FORMAT(transaksis.tanggal, '%Y-%m') AS bulan,
                            SUM(transaksis.debit) - SUM(transaksis.kredit) AS amount
                        ")
                            ->join('master_coa', 'transaksis.id_master_coa', '=', 'master_coa.id')
                            ->groupBy('master_coa.id_master_kategori_coa', 'bulan');
                    }])
            )
            ->columns(
                array_merge(
                    [
                        TextColumn::make('nama')
                            ->label('Category')
                            ->weight('bold')
                            ->color('primary'),
                    ],
                    array_map(function ($bulan) {
                        return TextColumn::make("bulan_{$bulan}")
                            ->label($bulan)
                            ->formatStateUsing(function ($record) use ($bulan) {
                                return number_format(300000);
                            })
                            ->alignEnd();
                    }, $bulanList)
                )
            )
            ->defaultSort('id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CustomLaporan::route('/'),
        ];
    }
}
