<?php

namespace App\Http\Controllers;

use App\Imports\BarangImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function downloadTemplate()
    {
        $headers = [
            'kode_aset', 'nama', 'deskripsi', 'kategori', 'lokasi',
            'nomor_seri', 'tanggal_pengadaan', 'vendor', 'sumber', 'harga',
            'kondisi', 'status_penggunaan', 'jumlah', 'satuan', 'catatan'
        ];

        $exampleData = [
            'LAB01-PC-0021', 'Komputer 21', 'PC Desktop Standard', 'Komputer', 'LAB-TKJ-01',
            'SN-DELL-0021', '2024-01-15', 'PT Teknologi Maju', 'Pembelian', '8500000',
            'Baik', 'Digunakan', '1', 'unit', 'Barang baru'
        ];

        $callback = function () use ($headers, $exampleData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers, ',');
            fputcsv($file, $exampleData, ',');
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-barang.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $import = new BarangImport(auth()->id());
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'message' => "Import selesai: {$import->getImportedCount()} baris berhasil diimpor.",
            'errors' => $import->getErrors(),
            'imported' => $import->getImportedCount(),
        ]);
    }
}
