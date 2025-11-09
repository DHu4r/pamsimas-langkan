<?php

namespace App\Imports;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class PenggunaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0] === 'nama') return null;

        return new Pengguna([
            'nama' => $row[0],
            'komplek' => $row[1],
            'no_hp' => $row[2],
            'role' => 'Pelanggan',
            'password' => Hash::make('12345678') // default password
        ]);
    }
}
