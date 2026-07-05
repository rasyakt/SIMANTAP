<?php

namespace App\Http\Controllers;

use App\Imports\BarangImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ImportController extends Controller
{
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerLabels = [
            'kode_aset', 'nama', 'deskripsi', 'kategori', 'lokasi',
            'nomor_seri', 'tanggal_pengadaan', 'vendor', 'sumber', 'harga',
            'kondisi', 'status_penggunaan', 'jumlah', 'satuan', 'catatan'
        ];

        $exampleData = [
            'LAB01-PC-0021', 'Komputer Desktop', 'PC Standard untuk Lab', 'Komputer', 'LAB-TKJ-01',
            'SN-DELL-0021', '2024-01-15', 'PT Teknologi Maju', 'Pembelian', '8500000',
            'Baik', 'Digunakan', '1', 'unit', 'Barang baru untuk lab komputer'
        ];

        $columnWidths = [20, 30, 35, 18, 22, 20, 20, 25, 15, 15, 18, 20, 10, 10, 30];

        foreach ($headerLabels as $col => $label) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
            $cell->setValue($label);
            $cell->getStyle()->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E79']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ]);
            $sheet->getColumnDimension($cell->getColumn())->setWidth($columnWidths[$col]);
        }

        foreach ($exampleData as $col => $value) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 2);
            $cell->setValue($value);
            $cell->getStyle()->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->freezePane('A2');
        $sheet->setTitle('Template Import Barang');

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(false);

        ob_clean();
        $tempPath = tempnam(sys_get_temp_dir(), 'template_import_');
        $writer->save($tempPath);

        $content = file_get_contents($tempPath);
        @unlink($tempPath);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template-import-barang.xlsx"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $import = new BarangImport(auth()->id());
        Excel::import($import, $request->file('file'));

        $importedCount = $import->getImportedCount();

        activity('barang')
            ->causedBy(auth()->user())
            ->event('imported')
            ->withProperties([
                'imported_count' => $importedCount,
                'errors' => $import->getErrors(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Mengimpor {$importedCount} barang dari file Excel.");

        return response()->json([
            'success' => true,
            'message' => "Import selesai: {$importedCount} baris berhasil diimpor.",
            'errors' => $import->getErrors(),
            'imported' => $importedCount,
        ]);
    }
}
