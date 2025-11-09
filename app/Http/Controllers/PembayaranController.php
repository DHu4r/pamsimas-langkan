<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $user =Auth::user();
        if ($user->role === 'Mitra Pembayaran'){
            $query = Pembayaran::with(['penggunaanAir.penggunas', 'dibayarOleh'])
            ->whereHas('dibayarOleh', function($q) use ($user){
                $q->where('id', $user->id);
            });
        }else{
            $query = Pembayaran::with('penggunaanAir.penggunas','dibayarOleh');
        }

        // Filter periode bulan
        if ($request->filled('periode_bulan')) {
            $query->whereHas('penggunaanAir', function ($q) use ($request) {
                $q->where('periode_bulan', $request->periode_bulan);
            });
        }

        //filter periode tahun
        if($request->filled('periode_tahun')){
            $query->whereHas('penggunaanAir', function ($q) use ($request){
                $q->where('periode_tahun', $request->periode_tahun);
            });
        }

        // 🔍 Filter pencarian (free text)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jumlah', 'like', "%{$search}%") // kolom dari tabel pembayaran
                ->orWhere('nama_bank', 'like', "%{$search}%")
                ->orWhere('metode', 'like', "%{$search}%") // jika kamu punya kolom ini
                // 🔽 relasi penggunaanAir
                ->orWhereHas('penggunaanAir', function ($q2) use ($search) {
                    $q2->where('periode_bulan', 'like', "%{$search}%")
                        ->orWhere('periode_tahun', 'like', "%{$search}%")
                    // 🔽 relasi penggunas (dalam penggunaanAir)
                    ->orWhereHas('penggunas', function ($q3) use ($search) {
                        $q3->where('nama', 'like', "%{$search}%");
                    });
                })
                // 🔽 relasi dibayarOleh (pembayar)
                ->orWhereHas('dibayarOleh', function ($q4) use ($search) {
                    $q4->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $pembayarans = $query->get();

        return view('pembayaran', [
            'tittle' => 'History Pembayaran',
            'pembayarans' => $pembayarans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        //
    }
}
