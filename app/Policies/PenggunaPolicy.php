<?php

namespace App\Policies;

use App\Models\Pengguna;

class PenggunaPolicy
{
    /**
     * Create a new policy instance.
     */

    //Lihat daftar pengguna
    public function viewAny(Pengguna $authPengguna): bool
    {
        return $authPengguna->role === 'Pengurus';
    }

    //update data pengguna
    public function update(Pengguna $authPengguna, Pengguna $targetPengguna): bool
    {
        //Hanya pengurus yang boleh update data pengguna
        return $authPengguna->role === 'Pengurus';
    }

    //Hapus data pengguna
    public function delete(Pengguna $authPengguna, Pengguna $targetPengguna): bool 
    {
        //hanya pengurus yang bisa delete
        if($authPengguna->role !== 'Pengurus'){
            return false;
        }

        //Pengurus tidak boleh hapus dirinya sendiri
        return $authPengguna->id !== $targetPengguna->id;
    }
}
