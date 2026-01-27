<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\PenggunaanAir;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\Events\PenggunaanAirApproved;

class PenggunaanAirDetail extends Component
{
    public $showModal = false;
    public PenggunaanAir|null $penggunaanAir = null;
    public string $catatanVerifikasi = '';

    public $currentUser;

    public function mount()
    {
        $this->currentUser = Auth::user();
    }

    #[On('show-detail')] // Livewire v3 pakai attribute event
    public function showDetail(string $id)
    {
        $this->penggunaanAir = PenggunaanAir::find($id);
        $this->catatanVerifikasi = '';
        $this->showModal = true;

        // Emit ke browser untuk close loading
        $this->dispatch('modal-loaded'); 
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

     //VERIFIKASI / APPROVE
    public function approve()
    {
        // if (! $this->penggunaanAir) {
        //     return;
        // }

        // $id = $this->penggunaanAir->id;

        // PenggunaanAir::where('id', $id)->update([
        //     'status'        => 'approved',
        //     'approved_by'  => Auth::id(),
        //     'approved_at'  => now(),
        //     'catatan_verifikasi' => null,
        // ]);

        // //GANTI INSTANCE, JANGAN refresh()
        // $this->penggunaanAir = PenggunaanAir::find($id);

        // // Jangan tutup modal
        if (! $this->penggunaanAir) {
        return;
        }

        $pa = PenggunaanAir::find($this->penggunaanAir->id);

        $pa->update([
            'status'        => 'approved',
            'approved_by'  => Auth::id(),
            'approved_at'  => now(),
            'catatan_verifikasi' => null,
        ]);

        $this->penggunaanAir = $pa;

        // // 🔔 trigger event approval
        // event(new PenggunaanAirApproved($pa));
    }




    //TOLAK
    public function reject()
    {
        if (! $this->penggunaanAir) {
            return;
        }

        if (trim($this->catatanVerifikasi) === '') {
            $this->addError('catatanVerifikasi', 'Catatan wajib diisi jika menolak.');
            return;
        }

        $id = $this->penggunaanAir->id;

        PenggunaanAir::where('id', $id)->update([
            'status'          => 'rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
            'catatan_verifikasi' => $this->catatanVerifikasi,
        ]);

        $this->penggunaanAir = PenggunaanAir::find($id);
    }
}
