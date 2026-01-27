<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use App\Models\PenggunaanAir;
use Illuminate\Support\Facades\Auth;
 
class TagihanAirController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PenggunaanAir::with('penggunas')
        ->where('status', 'approved') 
        ->orderByDesc('tahun')
        ->orderByDesc('bulan')
        ->orderByDesc('tahun');

        // Jika role Pelanggan → filter hanya miliknya
        if ($user->role === 'Pelanggan') {
            $query->where('penggunas_id', $user->id);
        }

        //Filter status bayar
        if ($request->filled('status_bayar')) {
            $query->where('sudah_bayar', $request->status_bayar);
        }

        //filter periode bulan
        if($request->filled('periode_bulan')){
            $query->where('periode_bulan', $request->periode_bulan);
        }

        //filter periode tahun
        if($request->filled('periode_tahun')){
            $query->where('periode_tahun', $request->periode_tahun);
        }

        // filter search (freetext)
        if ($request->filled('search')) {
        $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('konsumsi', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(tanggal_catat, '-', periode_bulan, '-', periode_tahun) LIKE ?", ["%$search%"])
                  ->orWhere('meter_baca_awal', 'like', '%' . $search . '%')
                  ->orWhere('meter_baca_akhir', 'like', '%' . $search . '%')
                  ->orWhereHas('penggunas', function ($q2) use ($search) {
                      $q2->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $penggunaan_airs = $query->get();


        return view('tagihan_air.index', [
            'tittle' => 'Tagihan Air',
            'penggunaan_airs' => $penggunaan_airs
        ]);
    }

    public function cetakPDF($id)
    {
        $penggunaanAir = PenggunaanAir::with('penggunas', 'pembayarans')->findOrFail($id);

        // ambil role pembayar
        $rolePembayar = $penggunaanAir->pembayarans->dibayarOleh->role ?? null;

        $authUser = Auth::user();
        $tanggalCetak = Carbon::now()->format('d-m-Y H:i:s'); // tanggal server saat cetak

        // Render HTML terlebih dahulu
        $html = view('pdf.rekening-air', compact('penggunaanAir','authUser', 'tanggalCetak', 'rolePembayar'))->render();

        // Konfigurasi DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Kirim PDF ke browser
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="rekening-air.pdf"');
    }
}
