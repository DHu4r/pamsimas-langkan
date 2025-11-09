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
use Livewire\WithFileUploads;

class ProsesPembayaranTransfer extends Component
{
    use WithFileUploads;
    public $daftarBank = [];
    public $showModal = false;
    public $penggunaan_air;
    public $tampilkanRekening = false;
    public $pembayaranTerakhir;
    public $step = 1;

    //Field tambahan
    public $nama_rekening;
    public $no_rekening;
    public $nama_bank;
    public $file_bukti;
    public $biaya_admin = 0;
    public $total_bayar;
    public $role_pembayar; 

    public function prosesPembayaranTransfer()
    {
         $validated = $this->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'nama_rekening' => 'required|string|max:150',
            'file_bukti' => 'required|image|max:2048', // <= wajib gambar
        ]);
        
        $pengguna = Auth::user();

        //ambil akun Rekening Pamsimas
        if ($pengguna->role === 'Mitra Pembayaran' || $pengguna->role === 'Pelanggan'){
            $rekAccount = Account::where('kode', 'REKPMS01')->first();
            if (!$rekAccount){
                throw new \Exception("Akun rekening PAMSIMAS (REKPMS01) tidak ditemukan.");
            }
            $rekAccountId = $rekAccount->id;
        }else{
            throw new \Exception("Akun keuangan tidak ditemukan");
        }

        DB::beginTransaction();

        try {
            // hitung total tagihan
            $tarif = config('tarif.harga_per_m3');
            $jumlah_tagihan = $this->penggunaan_air->konsumsi * $tarif;

            // kalau mitra → tambah biaya admin
            $this->role_pembayar = $pengguna->role;
            if ($this->role_pembayar === 'Mitra Pembayaran') {
                $this->biaya_admin = 3000; // contoh fix, bisa ambil dari setting
            }
            $this->total_bayar = $jumlah_tagihan + $this->biaya_admin;

            // simpan ke tabel pembayaran
            $pembayaran = Pembayaran::create([
                'id' => Str::uuid(),
                'penggunaan_air_id' => $this->penggunaan_air->id,
                'dibayar_oleh' => $pengguna->id,
                'role_pembayar' => $this->role_pembayar,
                'metode' => 'transfer',
                'jumlah' => $jumlah_tagihan,
                'biaya_admin' => $this->biaya_admin,
                'total_bayar' => $this->total_bayar,
                'no_rekening' => $this->no_rekening,
                'nama_rekening' => $this->nama_rekening,
                'nama_bank' => $this->nama_bank,
                'file_path' => $this->file_bukti 
                    ? $this->file_bukti->store('bukti_transfer', 'public') 
                    : null,
            ]);

            // update status pembayaran
            $this->penggunaan_air->update(['sudah_bayar' => true]);

            // buat jurnal
            Jurnal::create([
                'id' => Str::uuid(),
                'tanggal' => now(),
                'deskripsi' => 'Pembayaran tagihan air via transfer oleh ' . $this->penggunaan_air->penggunas->nama,
                'debit_account_id' => $rekAccountId,
                'kredit_account_id' => Account::where('kode', 'PTNPLG01')->first()->id,
                'nominal' => $jumlah_tagihan,
            ]);

            DB::commit();

            // tutup modal & beri notifikasi
            $this->dispatch('modal-loaded');
            $this->dispatch('pembayaran-berhasil', [
                'message' => session('success')
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            logger('Gagal bayar transfer', ['error' => $e->getMessage()]);
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pembayaran transfer.');
        }
    }

    #[On('buka-modal-pembayaran-transfer')]
    public function bukaModal($penggunaan_air_id)
    {
        $this->penggunaan_air = PenggunaanAir::with('penggunas')->findOrFail($penggunaan_air_id);
        $this->showModal = true;
        $this->step = 1;

        // Emit ke browser untuk close loading
        $this->dispatch('modal-loaded'); 
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }
    public function mount()
    {
        $this->daftarBank = config('bank');
    }

    public function render()
    {
        return view('livewire.proses-pembayaran-transfer');
    }
}
