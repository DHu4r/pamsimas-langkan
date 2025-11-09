<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $query = Jurnal::with(['debitAccount', 'kreditAccount'])->latest();

        //Filter bulan
        if ($request->filled('periode_bulan')) {
            $query->whereMonth('tanggal', $request->periode_bulan);
        }

        //Filter tahun
        if ($request->filled('periode_tahun')) {
            $query->whereYear('tanggal', $request->periode_tahun);
        }

        //Filter akun keuangan
        if ($request->filled('akun_d')) {
            $query->where('debit_account_id', $request->akun_d);
        }

        //Filter akun keuangan
        if ($request->filled('akun_k')) {
            $query->where('kredit_account_id', $request->akun_k);
        }

        //Filter pencarian Freetext
        if($request->filled('search')){
            $search = $request->search;

            $query->where(function ($q) use ($search){
                $q->where('tanggal' , 'like', '%' . $search . '%')
                ->orWhere('deskripsi' , 'like', '%' . $search . '%')
                ->orWhere('nominal' , 'like', '%' . $search . '%')
                ->orWhereHas('debitAccount', function ($q2) use ($search) {
                      $q2->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $akuns = Account::with('penggunas')->latest()->get();

        $jurnals = $query->get();

        return view('keuangan.jurnal.index',[
            'tittle' => 'Pencatatan / jurnal',
            'jurnals' => $jurnals,
            'akuns' => $akuns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('keuangan.jurnal.create',[
            'tittle' => 'Pencatatan / jurnal',
        ]);
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
    public function show(Jurnal $jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurnal $jurnal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurnal $jurnal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurnal $jurnal)
    {
        //
    }
}
