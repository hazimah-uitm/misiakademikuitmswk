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
        $responseAt = $this->parseExcelDateTime($row['timestamp'] ?? null);

        $data = [
            'response_at'    => $responseAt,
            'full_name'      => $this->nullIfNA($row['nama_penuh_huruf_besar'] ?? null),
            'phone'          => $this->nullIfNA($row['no_telefon_bimbit'] ?? null),
            'email'          => $this->normalizeEmail($row['alamat_emel'] ?? null),
            'program_bidang' => $this->nullIfNA($row['senarai_program_bidang_pengajian'] ?? null),
            'lokasi'         => $this->nullIfNA($row['lokasi'] ?? null),
        ];

        // Tetapkan "kunci" untuk cari rekod sedia ada
        $attrs = $this->buildUniqueAttrs($data);

        // Update jika jumpa; kalau tak, create
        return Visitor::updateOrCreate($attrs, $data);
    }

    private function buildUniqueAttrs(array $data): array
    {
        // Utama: email + response_at
        if (!empty($data['email']) && !empty($data['response_at'])) {
            return [
                'email'       => $data['email'],
                'response_at' => $data['response_at'],
            ];
        }

        // Fallback: full_name + phone + response_at
        return [
            'full_name'   => $data['full_name'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'response_at' => $data['response_at'] ?? null,
        ];
    }

    private function normalizeEmail($value)
    {
        if ($value === null) return null;
        $trim = trim((string)$value);
        if ($trim === '' || strtoupper($trim) === 'NA') return null;
        return strtolower($trim);
    }

    private function nullIfNA($value)
    {
        if ($value === null) return null;
        $trim = trim((string)$value);
        if ($trim === '' || strtoupper($trim) === 'NA') return null;
        return $trim;
    }

    private function parseExcelDateTime($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$value);
                return Carbon::instance($dateTime)->format('Y-m-d H:i:s');
            } catch (\Throwable $e) {
                // fall through
            }
        }

        try {
            return Carbon::parse((string)$value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
