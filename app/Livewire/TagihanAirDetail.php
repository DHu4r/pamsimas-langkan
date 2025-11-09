<?php

namespace App\Livewire;

use App\Models\Pembayaran;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PenggunaanAir;

class TagihanAirDetail extends Component
{
    public $showModal = false;
    public PenggunaanAir|null $penggunaan_air = null;
    public $pembayaran = null;
 
    #[On('show-tagihan-detail')] // Livewire v3 pakai attribute event
    public function showTagihanDetail(string $id)
    {
        $this->penggunaan_air = PenggunaanAir::with(['pembayarans.dibayarOleh'])->find($id);
        $this->pembayaran = $this->penggunaan_air->pembayarans;
        $this->showModal = true;

        // Emit ke browser untuk close loading
        $this->dispatch('modal-loaded'); 
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.tagihan-air-detail');
    }
}
