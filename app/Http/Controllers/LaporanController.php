<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Laporan;
use App\Models\Pengguna;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Models\PenggunaanAir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PSpell\Config;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Laporan::query();

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
                $q->where('catatan', 'like', '%' . $search . '%')
                  ->orWhere('jumlah_pelanggan', 'like', '%' . $search . '%')
                  ->orWhere('total_piutang', 'like', '%' . $search . '%')
                  ->orWhere('total_pemasukan', 'like', '%' . $search . '%')
                  ;
            });
        }
        
        $laporans = $query->get();

        return view('keuangan.laporan',[
            'tittle' => 'Kelola Laporan',
            'laporans' => $laporans,
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
        $bulan = $request->bulan_laporan;
        $tahun = $request->tahun_laporan;

        //Cek apakah laporan periode ini sudah ada
        $laporanAda = Laporan::where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->exists();
        
        if ($laporanAda) {
            return back()->withErrors([
                'periode' => "Laporan untuk periode {$bulan}-{$tahun} sudah ada."
            ]);
        }

        $request->validate([
            'bulan_laporan' => 'required|integer|min:1|max:12',
            'tahun_laporan' => 'required|integer|min:2000',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Ambil semua data penggunaan air sesuai periode
        $penggunaanAir = PenggunaanAir::with(['penggunas', 'pembayarans'])
            ->where('periode_bulan', $request->bulan_laporan)
            ->where('periode_tahun', $request->tahun_laporan)
            ->get();

        if ($penggunaanAir->isEmpty()) {
            return back()->with('error', 'Tidak ada data penggunaan air untuk periode tersebut.');
        }

        // Transform data untuk tampilan laporan
        $data = $penggunaanAir->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nama' => $item->penggunas->nama ?? '-',
                'komplek' => $item->penggunas->komplek ?? '-',
                'meter_awal' => $item->meter_baca_awal,
                'meter_akhir' => $item->meter_baca_akhir,
                'konsumsi' => $item->konsumsi,
                'status_catat' => $item->tanggal_catat ? '✅ Dicatat' : '❌ Belum Dicatat',
                'status_bayar' => $item->sudah_bayar ? '✅ Sudah Bayar' : '❌ Belum Bayar',
                'tanggal_catat' => $item->tanggal_catat ? date('d-m-Y', strtotime($item->tanggal_catat)) : '-',
                'tanggal_bayar' => optional($item->pembayarans)->created_at
                    ? date('d-m-Y', strtotime($item->pembayarans->created_at))
                    : '-',
                'jumlah' => optional($item->pembayarans)->jumlah ?? 0,
            ];
        });

        //Ambil total pelanggan dari data pengguna
        $total_pelanggan = Pengguna::where('role', 'Pelanggan')->count();

        //ambil data semua pelanggan
        $semua_pelanggan = Pengguna::where('role', 'Pelanggan')->get();

        $totalKonsumsi = PenggunaanAir::where('periode_bulan', $request->bulan_laporan)
            ->where('periode_tahun', $request->tahun_laporan)
            ->sum('konsumsi');
        
        $totalTagihan = $totalKonsumsi * Config('tarif.harga_per_m3');

        $totalDibayar = Pembayaran::whereHas('penggunaanAir', function ($query) use ($request) {
                $query->where('periode_bulan', $request->bulan_laporan)
                    ->where('periode_tahun', $request->tahun_laporan);
            })
            ->sum('jumlah');

        $totalPiutang = $totalTagihan - $totalDibayar;

        // Simpan laporan ke database
        $laporan = Laporan::create([
            'pembuat_id' => Auth::user()->id,
            'periode_bulan' => $request->bulan_laporan,
            'periode_tahun' => $request->tahun_laporan,
            'catatan' => $request->catatan,
            'tanggal_generate' => Carbon::now()->toDateString(),
            'jumlah_pelanggan' => $total_pelanggan,
            'total_piutang' => $totalPiutang,
            'total_pemasukan' => $totalDibayar,
        ]);

        // Generate PDF pakai DOMPDF
        $pdf = Dompdf::class;
        $dompdf = new $pdf();
        $html = view('pdf.laporan', [
            'laporan' => $laporan,
            'penggunaan_airs' => $penggunaanAir,
            'pelanggans' => $semua_pelanggan
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Simpan PDF ke storage
        // Nama file
        $filename = 'laporan_' . $request->bulan_laporan . '_' . $request->tahun_laporan . '.pdf';

        // Path dalam disk
        $pathInDisk = 'laporans/' . $filename;

        // Simpan PDF ke disk default_public_disk
        Storage::disk(config('filesystems.default_public_disk'))
                ->put($pathInDisk, $dompdf->output());

        // Update database
        $laporan->update([
            'file_pdf_path' => $pathInDisk
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat, silahkan lihat hasil laporan');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::where('id', $id)->first();

        // Ambil path PDF dari storage
        $disk = Storage::disk(config('filesystems.default_public_disk'));

        if (! $disk->exists($laporan->file_pdf_path)) {
            abort(404, 'File laporan tidak ditemukan');
        }

        //Ambil path full
        $path = $disk->path($laporan->file_pdf_path);

        // Bisa langsung stream ke browser
        return response()->file($path);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil data laporan berdasarkan ID
        $laporan = Laporan::findOrFail($id);

        // Pastikan file PDF-nya ada
        if ($laporan->file_pdf_path && Storage::disk(config('filesystems.default_public_disk'))->exists($laporan->file_pdf_path)) {
            // Hapus file PDF dari storage
            Storage::disk(config('filesystems.default_public_disk'))->delete($laporan->file_pdf_path);
        }

        // Hapus record dari database
        $laporan->delete();

        // Redirect balik dengan pesan sukses
        return redirect()->route('laporan.index')
            ->with('success', 'Laporan dan file PDF berhasil dihapus.');
    }
}
