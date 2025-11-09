<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $query = Account::with(['penggunas'])
        ->withSum('jurnalDebits as total_debit', 'nominal')
        ->withSum('jurnalKredits as total_kredit', 'nominal')
        ->latest();

        if($request->filled('search')){
            $search = $request->search;

            $query->where(function ($q) use ($search){
                $q->where('kode', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%')
                ->orWhere('tipe', 'like', '%' . $search . '%')
                ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        };

        $akuns = $query->get();

        return view('keuangan.akun.index', [
            'tittle' => 'Akun Keuangan',
            'akuns' => $akuns
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
        $validated = $request->validate([
            'kode' => 'required|unique:accounts,kode',
            'nama' => 'required|unique:accounts,nama',
            'tipe' => 'required',
            'keterangan' => 'required'
        ],[
            'kode.unique' => 'Kode akun sudah digunakan!',
            'nama.unique' => 'Nama akun sudah digunakan!',
        ]);

        $validated['id'] = (string) Str::uuid();

        Account::create($validated);
        return redirect()->route('akun.index')->with('success', 'Akun Keuangan Berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $akun)
    {
        $validated = $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'tipe' => 'required',
            'keterangan' => 'required'
        ]);

        $akun->update($validated);
        return redirect()->route('akun.index')->with('success', 'Akun keuangan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $akun)
    {
        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}
