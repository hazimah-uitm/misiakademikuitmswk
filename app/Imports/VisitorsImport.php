<?php

namespace App\Imports;

use App\Models\Visitor;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VisitorsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Kalau nak semak key yang Maatwebsite hasilkan:
        // dd(array_keys($row));

        // Excel headers (after slug by Maatwebsite):
        // 'timestamp'
        // 'nama_penuh_huruf_besar'
        // 'no_telefon_bimbit'
        // 'alamat_emel'
        // 'senarai_program_bidang_pengajian'
        // 'lokasi'

        $responseAt = $this->parseExcelDateTime($row['timestamp'] ?? null);

        return new Visitor([
            'response_at'    => $responseAt, // timestamp (nullable)
            'full_name'      => $this->nullIfNA($row['nama_penuh_huruf_besar'] ?? null),
            'phone'          => $this->nullIfNA($row['no_telefon_bimbit'] ?? null),
            'email'          => $this->nullIfNA($row['alamat_emel'] ?? null),
            'program_bidang' => $this->nullIfNA($row['senarai_program_bidang_pengajian'] ?? null),
            'lokasi'         => $this->nullIfNA($row['lokasi'] ?? null),
        ]);
    }

    /**
     * Tukar 'NA' atau string kosong kepada null
     */
    private function nullIfNA($value)
    {
        if ($value === null) return null;
        $trim = trim((string)$value);
        if ($trim === '' || strtoupper($trim) === 'NA') return null;
        return $trim;
    }

    /**
     * Sokong 3 kes:
     * 1) Excel serial number (numeric)
     * 2) String datetime standard (Carbon boleh parse)
     * 3) Kosong → null
     */
    private function parseExcelDateTime($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Jika numeric: kemungkinan Excel serial number
        if (is_numeric($value)) {
            try {
                $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$value);
                // Format Y-m-d H:i:s untuk simpanan MySQL timestamp
                return Carbon::instance($dateTime)->format('Y-m-d H:i:s');
            } catch (\Throwable $e) {
                // fallback cuba parse biasa
            }
        }

        // Cuba parse sebagai string datetime
        try {
            return Carbon::parse((string)$value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            // Kalau parse gagal, biar null supaya validation DB tak pecah
            return null;
        }
    }  
}
