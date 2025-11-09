<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\PenggunaanAir;
use Illuminate\Auth\Access\Response;

class PenggunaanAirPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Pengguna $pengguna): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Pengguna $pengguna, PenggunaanAir $penggunaanAir): bool
    {
        if ($pengguna->role === 'Pelanggan') {
            return $penggunaanAir->penggunas_id === $pengguna->id;
        }

        return in_array($pengguna->role, ['Pengurus', 'Petugas Lapangan']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Pengguna $pengguna): bool
    {
        return in_array($pengguna->role, ['Pelanggan', 'Petugas Lapangan']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Pengguna $pengguna, PenggunaanAir $penggunaanAir): bool
    {
        return in_array($pengguna->role, ['Pengurus', 'Petugas Lapangan']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Pengguna $pengguna, PenggunaanAir $penggunaanAir): bool
    {
        return in_array($pengguna->role, ['Pengurus', 'Petugas Lapangan']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Pengguna $pengguna, PenggunaanAir $penggunaanAir): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Pengguna $pengguna, PenggunaanAir $penggunaanAir): bool
    {
        return false;
    }
}
