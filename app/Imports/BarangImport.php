<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    private array $errors = [];
    private int $imported = 0;
    private ?int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $kodeAset = $row['kode_aset'] ?? null;
                if (empty($kodeAset)) {
                    throw new \Exception('Kode aset tidak boleh kosong');
                }

                $existing = Item::where('kode_aset', $kodeAset)->first();
                if ($existing) {
                    $existing->update([
                        'nama' => $row['nama'] ?? $existing->nama,
                        'deskripsi' => $row['deskripsi'] ?? $existing->deskripsi,
                        'nomor_seri' => $row['nomor_seri'] ?? $existing->nomor_seri,
                        'kondisi' => $row['kondisi'] ?? $existing->kondisi,
                        'status_penggunaan' => $row['status_penggunaan'] ?? $existing->status_penggunaan,
                        'jumlah' => $row['jumlah'] ?? $existing->jumlah,
                        'satuan' => $row['satuan'] ?? $existing->satuan,
                        'updated_by' => $this->userId,
                    ]);
                } else {
                    $kategoriId = null;
                    if (!empty($row['kategori'])) {
                        $kategori = Category::where('slug', Str::slug($row['kategori']))
                            ->orWhere('nama', $row['kategori'])
                            ->first();
                        $kategoriId = $kategori?->id;
                    }

                    $lokasiId = null;
                    if (!empty($row['lokasi'])) {
                        $lokasi = Location::where('kode_lokasi', $row['lokasi'])
                            ->orWhere('nama', $row['lokasi'])
                            ->first();
                        $lokasiId = $lokasi?->id;
                    }

                    $item = Item::create([
                        'kode_aset' => $kodeAset,
                        'nama' => $row['nama'] ?? '',
                        'deskripsi' => $row['deskripsi'] ?? null,
                        'kategori_id' => $kategoriId,
                        'lokasi_id' => $lokasiId,
                        'nomor_seri' => $row['nomor_seri'] ?? null,
                        'tanggal_pengadaan' => !empty($row['tanggal_pengadaan']) ? $this->parseDate($row['tanggal_pengadaan']) : null,
                        'vendor' => $row['vendor'] ?? null,
                        'sumber' => $row['sumber'] ?? null,
                        'harga' => $row['harga'] ?? null,
                        'kondisi' => $row['kondisi'] ?? 'Baik',
                        'status_penggunaan' => $row['status_penggunaan'] ?? 'Idle',
                        'jumlah' => $row['jumlah'] ?? 1,
                        'satuan' => $row['satuan'] ?? 'unit',
                        'catatan' => $row['catatan'] ?? null,
                        'created_by' => $this->userId,
                    ]);

                    try {
                        $qrCode = QrCode::format('png')->size(200)->generate($item->kode_aset);
                        $qrPath = 'qr-codes/' . $item->kode_aset . '.png';
                        \Illuminate\Support\Facades\Storage::put('public/' . $qrPath, $qrCode);
                        $item->update(['qr_code' => $qrPath]);
                    } catch (\Exception $e) {
                        // QR generation is optional
                    }
                }

                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($row->get('row_index', '?') ?? '?') . ": " . $e->getMessage();
            }
        }
    }

    public function rules(): array
    {
        return [
            'kode_aset' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
            'kondisi' => 'nullable|string|in:Baik,Rusak Ringan,Rusak Berat,Dalam Perbaikan,Sudah Diperbaiki,Afkir-Dihapuskan',
            'status_penggunaan' => 'nullable|string|in:Digunakan,Idle,Dipinjam,Dalam Perbaikan,Menunggu Pembuangan',
        ];
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function parseDate($value): ?string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        $stringValue = (string) trim($value);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $stringValue)) {
            $date = \DateTime::createFromFormat('Y-m-d', $stringValue);
            return $date ? $date->format('Y-m-d') : null;
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $stringValue)) {
            $date = \DateTime::createFromFormat('d/m/Y', $stringValue);
            return $date ? $date->format('Y-m-d') : null;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $stringValue)) {
            $date = \DateTime::createFromFormat('d-m-Y', $stringValue);
            return $date ? $date->format('Y-m-d') : null;
        }

        if (is_numeric($stringValue) && $stringValue > 0) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $stringValue)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $date = strtotime($stringValue);
        return $date ? date('Y-m-d', $date) : null;
    }
}
