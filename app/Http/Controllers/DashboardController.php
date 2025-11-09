<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use App\Models\PenggunaanAir;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $pengguna_id = Auth::user()->id;

        switch ($role) {
            case 'Pengurus':
                $now = Carbon::now();
                $bulan = $now->month;
                $tahun = $now->year;

                $total_pelanggan = Pengguna::where('role', 'Pelanggan')->count();

                $id_saya = Auth::user()->id;
                
                // Ambil semua penggunaan air periode ini beserta pembayarannya
                    $air_tercatat_periode_ini = PenggunaanAir::where('periode_bulan', $bulan)
                        ->where('periode_tahun', $tahun)
                        ->with('pembayarans')   
                        ->get();

                // Hitung total nominal
                    $total_nominal = $air_tercatat_periode_ini->sum(function ($item) {
                        return optional($item->pembayarans)->jumlah ?? 0;
                    });
                
                
                $air_tercatat_periode_ini = PenggunaanAir::where('periode_bulan', $bulan || 'periode_tahun', $tahun)->count();

                // Ambil semua penggunaan air periode ini beserta pembayarannya
                    $konsumsi_air_belum_lunas_periode_ini = PenggunaanAir::where('periode_bulan', $bulan)
                        ->where('periode_tahun', $tahun)
                        ->where('sudah_bayar', 0)   
                        ->sum('konsumsi');
                    
                    $air_belum_lunas_periode_ini = $konsumsi_air_belum_lunas_periode_ini * config('tarif.harga_per_m3');

                return view('dashboard_pengurus', [
                    'tittle' => 'Dashboard Pengurus',
                    'air_tercatat_periode_ini' => $air_tercatat_periode_ini,
                    'total_pelanggan' => $total_pelanggan,
                    'total_nominal' => $total_nominal,
                    'air_belum_lunas_periode_ini' => $air_belum_lunas_periode_ini
                ]);

            case 'Petugas Lapangan':
                $now = Carbon::now();
                $bulan = $now->month;
                $tahun = $now->year;
                $air_tercatat_periode_ini = PenggunaanAir::where('periode_bulan', $bulan || 'periode_tahun', $tahun)->count();

                $total_pelanggan = Pengguna::where('role', 'Pelanggan')->count();

                $air_belum_tercatat_periode_ini = $total_pelanggan - $air_tercatat_periode_ini;

                $jumlah_inputan_saya = PenggunaanAir::where('dicatat_oleh', $pengguna_id)->count();
 
                return view('dashboard_petugas', [
                    'tittle' => 'Dashboard Petugas Lapangan',
                    'air_tercatat_periode_ini' => $air_tercatat_periode_ini,
                    'total_pelanggan' => $total_pelanggan,
                    'jumlah_inputan_saya' => $jumlah_inputan_saya,
                    'air_belum_tercatat_periode_ini' => $air_belum_tercatat_periode_ini
                ]);

            case 'Mitra Pembayaran':
                $id_saya = Auth::user()->id;

                // Tagihan air terbayar periode ini
                    $now = Carbon::now();
                    $bulan = $now->month;
                    $tahun = $now->year;
                // Ambil semua penggunaan air periode ini beserta pembayarannya
                    $air_tercatat_periode_ini = PenggunaanAir::where('periode_bulan', $bulan)
                        ->where('periode_tahun', $tahun)
                        ->with('pembayarans')   
                        ->get();

                // Hitung total nominal
                    $total_nominal = $air_tercatat_periode_ini->sum(function ($item) {
                        return optional($item->pembayarans)->jumlah ?? 0;
                    });

                // Jumlah pelanggan
                    $total_pelanggan = Pengguna::where('role', 'Pelanggan')->count();

                // History Pembayaran saya
                    $pembayaran_saya = Pembayaran::where('dibayar_oleh', $id_saya)->count();

                // Ambil semua penggunaan air periode ini beserta pembayarannya
                    $konsumsi_air_belum_lunas_periode_ini = PenggunaanAir::where('periode_bulan', $bulan)
                        ->where('periode_tahun', $tahun)
                        ->where('sudah_bayar', 0)   
                        ->sum('konsumsi');
                    
                    $air_belum_lunas_periode_ini = $konsumsi_air_belum_lunas_periode_ini * config('tarif.harga_per_m3');

                return view('dashboard_mitra', [
                    'tittle' => 'Dashboard Mitra Pembayaran',
                    'total_nominal' => $total_nominal,
                    'total_pelanggan' => $total_pelanggan,
                    'pembayaran_saya' => $pembayaran_saya,
                    'air_belum_lunas_periode_ini' => $air_belum_lunas_periode_ini
                ]);

            case 'Pelanggan':
                $total_penggunaan_air = PenggunaanAir::where('penggunas_id', $pengguna_id)->sum('konsumsi');
                // Ambil semua penggunaan air pelanggan yang sudah lunas
                $penggunaan_id = PenggunaanAir::where('penggunas_id', $pengguna_id)
                    ->where('sudah_bayar', 1)
                    ->pluck('id');
                // Jumlahkan nominal pembayaran untuk penggunaan air tersebut
                $total_terbayar = Pembayaran::whereIn('penggunaan_air_id', $penggunaan_id)
                    ->sum('jumlah');
                
                //Angka meteran air terakhir
                $penggunaan_terakhir = PenggunaanAir::where('penggunas_id', $pengguna_id)
                    ->latest('tanggal_catat') // urutkan dari yang paling baru
                    ->first();

                $meter_akhir = $penggunaan_terakhir->meter_baca_akhir ?? 0;

                // Ambil semua penggunaan air pelanggan yang belum lunas
                $penggunaan_tidak_bayar = PenggunaanAir::where('penggunas_id', $pengguna_id)
                    ->where('sudah_bayar', 0)
                    ->sum('konsumsi');
                $total_belum_bayar = $penggunaan_tidak_bayar * config('tarif.harga_per_m3');

                return view('dashboard_pelanggan', [
                    'tittle' => 'Dashboard Pelanggan',
                    'total_penggunaan_air' => $total_penggunaan_air,
                    'total_terbayar' => $total_terbayar,
                    'meter_akhir' => $meter_akhir,
                    'total_belum_bayar' => $total_belum_bayar
                ]);

            default:
                abort(403, 'Unauthorized.');
        }
    }
}
