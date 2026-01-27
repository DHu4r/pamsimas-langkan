<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Models\Jurnal;
use App\Models\Account;
use Illuminate\Support\Str;
use App\Events\PenggunaanAirApproved;

class CreateJurnalForPenggunaanAirApproved
{
    public function handle(PenggunaanAirApproved $event): void
    {

        $pa = $event->penggunaanAir;

        Log::info('Listener jalan', ['pa_id' => $pa->id]);

        // safety: jangan dobel jurnal
        if (Jurnal::where('penggunaan_air_id', $pa->id)->exists()) {
            return;
        }

        $tarif   = config('tarif.harga_per_m3');
        $amount  = $pa->konsumsi * $tarif;

        $akunPiutang    = Account::where('kode', 'PTNPLG01')->firstOrFail();
        $akunPendapatan = Account::where('kode', 'PNDPMS01')->firstOrFail();

        Jurnal::create([
            'id'                => Str::uuid(),
            'tanggal'           => now(),
            'deskripsi'         => sprintf(
                'Tagihan air %s %02d/%d',
                $pa->penggunas->nama,
                $pa->periode_bulan,
                $pa->periode_tahun
            ),
            'debit_account_id'  => $akunPiutang->id,
            'kredit_account_id' => $akunPendapatan->id,
            'nominal'           => $amount,
            'penggunaan_air_id' => $pa->id,
        ]);
    }
}
