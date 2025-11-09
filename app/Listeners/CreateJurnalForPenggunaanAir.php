<?php

namespace App\Listeners;

use App\Events\PenggunaanAirCreated;
use App\Models\Account;
use App\Models\Jurnal;
use Illuminate\Support\Str;

class CreateJurnalForPenggunaanAir
{
    public function handle(PenggunaanAirCreated $event): void
    {
        $pa = $event->penggunaanAir;

        // Nominal piutang = konsumsi x tarif (biasanya tanpa biaya admin)
        $tarif   = config('tarif.harga_per_m3');
        $amount  = $pa->konsumsi * $tarif;

        // Akun: Piutang (debit) vs Pendapatan (kredit)
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
        ]);
    }
}
