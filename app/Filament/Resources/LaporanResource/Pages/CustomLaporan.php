<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use App\Models\MasterCoa;
use Filament\Resources\Pages\Page;
use App\Models\Transaksi;
use App\Models\MasterKategoriCoa;
use Carbon\Carbon;
use Carbon\Month;

class CustomLaporan extends Page
{
    protected static string $resource = LaporanResource::class;
    protected static string $view = 'filament.resources.laporan-resource.pages.custom-laporan';

    public $laporan = [];

    public function mount()
    {
        // Ambil daftar bulan dari Januari hingga bulan sekarang
        $bulanList = collect(range(1, Carbon::now()->month))
            ->map(fn($m) => Carbon::create(null, $m, 1)->format('Y-m'))
            ->toArray();

        // Ambil data laporan berdasarkan kategori dan bulan
        $kategori = MasterKategoriCoa::get();
        $list_data = array();

        foreach ($bulanList as $bulan):
            $query = Transaksi::selectRaw("
                master_kategori_coa.nama AS kategori,
                DATE_FORMAT(transaksis.tanggal, '%Y-%m') AS bulan,
                SUM(transaksis.debit) AS total_debit,
                SUM(transaksis.kredit) AS total_kredit")
                ->join('master_coa', 'transaksis.id_master_coa', '=', 'master_coa.id')
                ->join('master_kategori_coa', 'master_coa.id_master_kategori_coa', '=', 'master_kategori_coa.id')
                ->whereMonth('transaksis.tanggal', substr($bulan, 5, 2))
                ->groupBy('master_kategori_coa.id', 'master_kategori_coa.nama', 'bulan')
                ->orderBy('bulan', 'asc')
                ->get();

            foreach ($query as $val):
                $list_data[] = [
                    'kategori'     => $val->kategori,
                    'bulan'        => $val->bulan,
                    'total_debit'  => round($val->total_debit),
                    'total_kredit' => round($val->total_kredit),
                ];
            endforeach;

        endforeach;

        $this->laporan = [
            'bulanList' => $bulanList,
            'data' => $kategori,
            'list_data' => $list_data
        ];
    }
}
