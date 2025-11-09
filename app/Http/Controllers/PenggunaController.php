<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $query = Pengguna::query();
        
        //filter komplek
        if($request->filled('komplek')){
            $query->where('komplek', $request->komplek);
        }

        //filter role
        if($request->filled('role')){
            $query->where('role', $request->role);
        }

        if($request->has('search') && $request->search != ''){
            $search = $request->search;

            $query->where(function($q) use($search){
                $q->where('nama', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
                ->orWhere('komplek', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%");
            });
        }
        
        $penggunas = $query->latest()->get();

        return view('pengguna.index', [
            'penggunas' => $penggunas,
            'tittle' => 'Kelola Pengguna',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('pengguna.create', ['tittle'=>'Tambah Pengguna']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'komplek' => 'required',
            'role' => 'required',
            'no_hp' => 'required|numeric',
            'password' => 'required'
        ]);

        //Cek nomor hp ke DB
        $cek_no_hp = Pengguna::where('no_hp', $validated['no_hp'])->first();
        if($cek_no_hp){
            return redirect()->route('pengguna.index')
                ->withErrors([
                    'no_hp' => 'Nomor handpone yang anda masukan sudah terdaftar atas nama ' . $cek_no_hp->nama
                ]);
        }

         // Hash password
        $validated['password'] = bcrypt($validated['password']);
    
        Pengguna::create($validated);
        return redirect()->route('pengguna.index')->with('success', 'Pengguna Berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $pengguna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'komplek' => 'required',
            'role' => 'required',
            'no_hp' => 'required',
            'password' => 'nullable|string|min:6' // password boleh kosong
        ]);

        // Cek apakah password dikirim
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // biar tidak ditimpa dengan null
        }
        
        $pengguna->update($validated);
        return redirect()->route('pengguna.index')->with('success', 'Pengguna diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna dihapus!');
    }

    public function list_pelanggan(Request $request){

        $now = Carbon::now(); 

        $bulan = $now->month;
        $nama_bulan = $now->translatedFormat('F');
        $tahun = $now->year;

        // Ambil semua pelanggan + relasi penggunaan air bulan & tahun ini
        $query = Pengguna::where('role', 'Pelanggan')
            ->with(['penggunaan_airs' => function ($q) use ($bulan, $tahun) {
                $q->where('periode_bulan', $bulan)
                ->where('periode_tahun', $tahun);
            }]);

        //filter komplek
        if($request->filled('komplek')){
            $query->where('komplek', $request->komplek);
        }

        //filter status catat
        // Filter status catat meteran
        if ($request->filled('status_catat')) {
            if ($request->status_catat === 'Sudah Catat') {
                // pelanggan yang punya penggunaan air bulan & tahun ini
                $query->whereHas('penggunaan_airs', function ($q) use ($bulan, $tahun) {
                    $q->where('periode_bulan', $bulan)
                    ->where('periode_tahun', $tahun);
                });
            } elseif ($request->status_catat === 'Belum Catat') {
                // pelanggan yang TIDAK punya penggunaan air bulan & tahun ini
                $query->whereDoesntHave('penggunaan_airs', function ($q) use ($bulan, $tahun) {
                    $q->where('periode_bulan', $bulan)
                    ->where('periode_tahun', $tahun);
                });
            }
        }

        //filter form text cari
        if($request->has('search') && $request->search != ''){
            $search = $request->search;

            $query->where(function($q) use($search){
                $q->where('nama', 'like', "%$search%")
                ->orWhere('no_hp', 'like', "%$search%")
                ->orWhere('komplek', 'like', "%$search%")
                ->orWhere('periode_bulan', 'like', "%$search%")
                ->orWhere('periode_tahun', 'like', "%$search%");
            });
        }

        $pelanggans = $query->get();

        return view('pengguna.pelanggan', [
            'pelanggans' => $pelanggans,
            'tittle' => 'Daftar Pelanggan',
            'nama_bulan' => $nama_bulan,
            'tahun' => $tahun
        ]);
    }
}
