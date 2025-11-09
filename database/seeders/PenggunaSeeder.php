<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::insert([
            [
                'id' => Str::uuid(),
                'nama' => 'Pak Open',
                'komplek' => 'Desa Anyar',
                'role' => 'Pelanggan',
                'no_hp' => '081234567890',
                'password' => bcrypt('12345678')
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Nyoman Wejaya',
                'komplek' => 'Dalem',
                'role' => 'Pelanggan',
                'no_hp' => '081234567891',
                'password' => bcrypt('12345678')
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'I Nyoman Rauh Adnyana',
                'komplek' => 'Banjar Kaja',
                'role' => 'Pelanggan',
                'no_hp' => '081234567892',
                'password' => bcrypt('12345678')
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Pak Tana',
                'komplek' => 'Desa Anyar',
                'role' => 'Petugas Lapangan',
                'no_hp' => '081234567890',
                'password' => bcrypt('12345678')
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Budayasa',
                'komplek' => 'Dalem',
                'role' => 'Mitra Pembayaran',
                'no_hp' => '081234567891',
                'password' => bcrypt('12345678')
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Ketut Adipta',
                'komplek' => 'Banjar Kaja',
                'role' => 'Pengurus',
                'no_hp' => '081234567892',
                'password' => bcrypt('12345678')
            ],
        ]);
    }
}
