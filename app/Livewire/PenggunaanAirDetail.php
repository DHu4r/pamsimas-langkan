<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\PenggunaanAir;
use Livewire\Attributes\On;

class PenggunaanAirDetail extends Component
{
    public $showModal = false;
    public PenggunaanAir|null $penggunaanAir = null;

    #[On('show-detail')] // Livewire v3 pakai attribute event
    public function showDetail(string $id)
    {
        $this->penggunaanAir = PenggunaanAir::find($id);
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
        return view('livewire.penggunaan-air-detail');
    }
}
