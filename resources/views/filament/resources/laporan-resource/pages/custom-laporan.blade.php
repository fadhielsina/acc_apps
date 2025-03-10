<x-filament-panels::page>
    <style>
        .table-responsive {
            overflow-x: auto;
            /* Aktifkan scroll horizontal jika layar kecil */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling untuk mobile */
        }

        table {
            width: 100%;
            /* Pastikan tabel mengambil seluruh lebar */
            border-collapse: collapse;
            /* Hapus ruang antar border */
        }

        th,
        td {
            padding: 10px;
            /* Beri ruang antar teks dengan tepi sel */
            border: 1px solid #ddd;
            /* Tambahkan border abu-abu */
            text-align: left;
            /* Rata kiri untuk teks */
            white-space: nowrap;
            /* Mencegah teks terpotong */
        }

        @media (max-width: 768px) {

            th,
            td {
                font-size: 14px;
                /* Ukuran teks lebih kecil di layar kecil */
                padding: 8px;
            }

            th {
                white-space: normal;
                /* Agar teks header tidak kepanjangan */
            }
        }
    </style>

    <style>
        #exportExcel {
            background-color: #28a745;
            /* Warna hijau khas Excel */
            color: white;
            /* Warna teks putih */
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        #exportExcel:hover {
            background-color: #218838;
            /* Warna hijau lebih gelap saat hover */
            transform: scale(1.05);
            /* Efek sedikit membesar saat hover */
        }

        #exportExcel:active {
            background-color: #1e7e34;
            /* Warna lebih gelap saat ditekan */
            transform: scale(0.98);
            /* Efek sedikit mengecil saat ditekan */
        }

        #exportExcel i {
            margin-right: 8px;
            /* Jarak ikon dengan teks */
        }
    </style>

    <div class="d-flex justify-content-end mb-3">
        <button id="exportExcel" class="btn btn-success">Export to Excel</button>
    </div>


    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="text-align: center;">Category</th>
                    @foreach ($laporan['bulanList'] as $bulan)
                    <th style="text-align: center;">{{ $bulan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan['data'] as $kategori)
                <tr>
                    <td class="fw-bold">{{ $kategori->nama }}</td>
                    @foreach ($laporan['bulanList'] as $bulan)
                    @php
                    $dataBulan = collect($laporan['list_data'])->firstWhere(function ($item) use ($kategori, $bulan) {
                    return $item['kategori'] == $kategori->nama && $item['bulan'] == $bulan;
                    });

                    // Menentukan nilai yang akan ditampilkan
                    $nilai = $dataBulan
                    ? ($dataBulan['total_kredit'] != 0 ? $dataBulan['total_kredit'] : $dataBulan['total_debit'])
                    : 0;
                    @endphp
                    <td>{{ number_format($nilai, 0, '.', '.') }}</td>
                    @endforeach
                </tr>
                @endforeach

                {{-- Baris Total Income --}}
                <tr class="table-success fw-bold">
                    <td style="color: green; text-align:center;">Total Income</td>
                    @foreach ($laporan['bulanList'] as $bulan)
                    @php
                    $totalIncome = collect($laporan['list_data'])
                    ->where('bulan', $bulan)
                    ->sum('total_kredit');
                    @endphp
                    <td style="color: green;text-align:center;">{{ number_format($totalIncome, 0, '.', '.') }}</td>
                    @endforeach
                </tr>

                {{-- Baris Total Expense --}}
                <tr class="table-danger fw-bold">
                    <td style="color: red; text-align:center;">Total Expense</td>
                    @foreach ($laporan['bulanList'] as $bulan)
                    @php
                    $totalExpense = collect($laporan['list_data'])
                    ->where('bulan', $bulan)
                    ->sum('total_debit');
                    @endphp
                    <td style="color: red; text-align:center;">{{ number_format($totalExpense, 0, '.', '.') }}</td>
                    @endforeach
                </tr>

                {{-- Baris Net Income --}}
                <tr class="table-warning fw-bold">
                    <td style="text-align:center;">Net Income</td>
                    @foreach ($laporan['bulanList'] as $bulan)
                    @php
                    $totalIncome = collect($laporan['list_data'])
                    ->where('bulan', $bulan)
                    ->sum('total_kredit');

                    $totalExpense = collect($laporan['list_data'])
                    ->where('bulan', $bulan)
                    ->sum('total_debit');

                    $netIncome = $totalIncome - $totalExpense;
                    @endphp
                    <td style="text-align:center;">{{ number_format($netIncome, 0, '.', '.') }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById("exportExcel").addEventListener("click", function() {
            let table = document.querySelector("table"); // Ambil tabel
            let wb = XLSX.utils.book_new(); // Buat workbook baru
            let ws = XLSX.utils.table_to_sheet(table); // Konversi tabel ke sheet Excel

            XLSX.utils.book_append_sheet(wb, ws, "Laporan"); // Tambahkan sheet ke workbook

            // Simpan file dengan nama "laporan.xlsx"
            XLSX.writeFile(wb, "laporan.xlsx");
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


</x-filament-panels::page>