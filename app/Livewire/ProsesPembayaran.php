<?php

namespace App\Livewire;

use App\Models\Jurnal;
use App\Models\Account;
use Livewire\Component;
use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\PenggunaanAir;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProsesPembayaran extends Component
{
    public $showModal = false;
    public $penggunaan_air;
    public $tampilkanRekening = false;
    public $pembayaranTerakhir;

    public function prosesPembayaran(){
 
        $pengguna = Auth::user();

        // Cek role pengguna
        if ($pengguna->role === 'Pengurus') {
            // Ambil ID akun kas Pamsimas (misal KASPMS01)
            $kasAccount = Account::where('kode', 'KASPMS01')->first();

            if (!$kasAccount) {
                throw new \Exception("Akun kas Pamsimas (KASPMS01) tidak ditemukan.");
            }

            $kasAccountId = $kasAccount->id;
        } else {
                throw new \Exception("Akun keuangan tidak ditemukan");
        }

        DB::beginTransaction();

        try {
            // Hitung Total
            $tarif = config('tarif.harga_per_m3');
            $total = ($this->penggunaan_air->konsumsi * $tarif);

            // Simpan ke tabel pembayaran
            $pembayaran = Pembayaran::create([
                'id' => Str::uuid(),
                'penggunaan_air_id' => $this->penggunaan_air->id,
                'dibayar_oleh' => Auth::user()->id,
                'metode' => 'cash',
                'jumlah' => $this->penggunaan_air->konsumsi * config('tarif.harga_per_m3'),
            ]);
            
            // Update status pembayaran
            $this->penggunaan_air->update(['sudah_bayar' => true]);

            // Buat Jurnal
            Jurnal::create([
                'id' => Str::uuid(),
                'tanggal' => now(),
                'deskripsi' => 'Pembayaran tagihan air oleh ' . $this->penggunaan_air->penggunas->nama,
                'debit_account_id' => $kasAccountId,
                'kredit_account_id' => Account::where('kode', 'PTNPLG01')->first()->id,
                'nominal' => $total,
            ]);

            DB::commit();

            // Tutup modal & beri notifikasi (bisa pakai emit)
            $this->dispatch('modal-loaded');
            // $this->showModal = false;

            session()->flash('success', 'Pembayaran berhasil diproses.');
            $this->dispatch('pembayaran-berhasil', [
                'message' => session('success')
            ]);

        }catch (\Throwable $e) {
            DB::rollBack();
            logger('Gagal bayar', ['error' => $e->getMessage()]);
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pembayaran.');
        }
    }
 
    #[On('buka-modal-pembayaran')]
    public function bukaModal($penggunaan_air_id)
    {
        $this->penggunaan_air = PenggunaanAir::with('penggunas')->findOrFail($penggunaan_air_id);
        $this->showModal = true;

        // Emit ke browser untuk close loading
        $this->dispatch('modal-loaded'); 
    }
    public function render()
    {
        return view('livewire.proses-pembayaran');
    }
}
