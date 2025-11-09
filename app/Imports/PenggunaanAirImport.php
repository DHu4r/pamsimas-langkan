<?php

namespace App\Imports;

use App\Models\PenggunaanAir;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class PenggunaanAirImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Konversi tanggal dari Excel ke format Y-m-d
        $tanggalCatat = $row['tanggal_catat'];
        if (is_numeric($tanggalCatat)) {
            $tanggalCatat = Carbon::instance(ExcelDate::excelToDateTimeObject($tanggalCatat));
        } else {
            $tanggalCatat = Carbon::parse($tanggalCatat);
        }

        return new PenggunaanAir([
            'id'               => (string) Str::uuid(), // 🔥 WAJIB agar tidak null
            'penggunas_id'     => $row['penggunas_id'],
            'meter_baca_awal'  => $row['meter_baca_awal'],
            'meter_baca_akhir' => $row['meter_baca_akhir'],
            'konsumsi'         => $row['konsumsi'],
            'tanggal_catat'    => $tanggalCatat->format('Y-m-d'),
            'periode_bulan'    => $row['periode_bulan'],
            'periode_tahun'    => $row['periode_tahun'],
            'sudah_bayar'      => $row['sudah_bayar'] ?? 0,
            'foto_meter'       => $row['foto_meter'] ?? null,
            'dicatat_oleh'     => $row['dicatat_oleh'] ?? null,
        ]);
    }
}
