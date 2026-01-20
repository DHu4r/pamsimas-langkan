<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\Account;
use App\Models\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PenggunaanAir;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class PenggunaanAirController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        // Ambil data penggunaan air + relasi penggunanya
        $query = PenggunaanAir::with('penggunas')
        ->orderByDesc('tanggal_catat');

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
                  ->orWhereRaw("CONCAT(tanggal_catat) LIKE ?", ["%$search%"])
                  ->orWhere('meter_baca_awal', 'like', '%' . $search . '%')
                  ->orWhere('meter_baca_akhir', 'like', '%' . $search . '%')
                  ->orWhereHas('penggunas', function ($q2) use ($search) {
                      $q2->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $penggunaan_airs = $query->get();

        return view('penggunaan_air.index', [
            'tittle' => 'Penggunaan Air',
            'penggunaan_airs' => $penggunaan_airs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //check policy
        $this->authorize('create', PenggunaanAir::class);

        $penggunas = Pengguna::orderBy('nama')->get(['id','nama']);

        return view('penggunaan_air.create', [
            'tittle' => 'Tambah Penggunaan Air',
            'penggunas' => $penggunas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pencatat = Auth::user();

        $validated = $request->validate([
            'penggunas_id' => 'required|uuid|exists:penggunas,id',
            'meter_baca_awal' => 'required|integer|min:0',
            'meter_baca_akhir' => 'required|integer|gte:meter_baca_awal',
            'konsumsi' => 'required|integer',
            'tanggal_catat' => 'required',
            'periode_tahun' => 'required|integer|min:1900|max:2100',
            'foto_meter'        => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'periode_bulan' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('penggunaan_airs')->where(function ($query) use ($request){
                    return $query
                    ->where('penggunas_id', $request->penggunas_id)
                    ->where('periode_bulan', $request->periode_bulan)
                    ->where('periode_tahun', $request->periode_tahun);
                }),
            ],
        ],[
            // Di sinilah messages() diletakkan (parameter ke-2)
            'meter_baca_akhir.gte' => 'Nilai meteran akhir tidak boleh lebih sedikit dari nilai meteran awal.',
            'periode_bulan.unique' => 'Data untuk bulan dan tahun ini sudah ada untuk pelanggan ini.',
            'foto_meter.max'   => 'Ukuran foto maksimal 10 MB. Silakan ambil foto ulang atau gunakan resolusi lebih kecil.',
            'foto_meter.image' => 'File yang diunggah harus berupa gambar.',
            'foto_meter.mimes' => 'Format foto harus JPG atau PNG.',
        ]);

        //Validasi tanggal sesuai periode
        $periodeAwal = Carbon::create($validated['periode_tahun'], $validated['periode_bulan'], 1)->startOfMonth();
        $periodeAkhir = $periodeAwal->copy()->endOfMonth();
        if(!Carbon::parse($validated['tanggal_catat'])->between($periodeAwal, $periodeAkhir))
        {
            return back()->withErrors([
                'tanggal_catat' => "Tanggal catat harus berada di bulan {$validated['periode_bulan']} tahun {$validated['periode_tahun']}."
            ])->withInput();
        }

        // Cek periode tidak mundur
        $periodeTerakhir = PenggunaanAir::where('penggunas_id', $validated['penggunas_id'])
        ->orderBy('periode_tahun', 'desc')
        ->orderBy('periode_bulan', 'desc')
        ->first();
        if ($periodeTerakhir) {
            $periodeInput = Carbon::create($validated['periode_tahun'], $validated['periode_bulan'], 1);
            $periodeDb = Carbon::create($periodeTerakhir->periode_tahun, $periodeTerakhir->periode_bulan, 1);

            if ($periodeInput->lt($periodeDb)) {
                return back()->withErrors([
                    'periode_bulan' => "Periode tidak boleh mundur dari periode terakhir ({$periodeDb->translatedFormat('F Y')})."
                ])->withInput();
            }
        }

        // //Upload Foto
        // if ($request->hasFile('foto_meter')) {
        //     // simpan ke storage/app/public/meteran
        //     $filename = time().'_'.$request->file('foto_meter')->getClientOriginalName();
        //     $path = $request->file('foto_meter')->storeAs('meteran', $filename, config('filesystems.default_public_disk'));
        //     $validated['foto_meter'] = $path;
        // }

        //Upload foto + compress
        if($request->hasFile('foto_meter')){
            $file = $request->file('foto_meter');

            //nama file
            $filename = time().'_'.$file->getClientOriginalName();

            // Manager v3 (GD driver)
            $manager = new ImageManager(new Driver());

            // Baca image
            $image = $manager->read($file->getRealPath());

            //Resize proporsiojnal (max width 1280)
            $image->scaleDown(1280);

            //Simpan sementara
            $tempPath = sys_get_temp_dir() . '/' . $filename;

            //compress kualitas ke 75%
            $image->save($tempPath, quality: 75);

            //Upload file hasil compress ke disk
            $path  = Storage::disk(config('filesystems.default_public_disk'))
                ->putFileAs('meteran', new \Illuminate\Http\File($tempPath), $filename);

            //Hapus file sementara
            @unlink($tempPath);

            //simpan path ke DB
            $validated['foto_meter'] = $path;
        }
      
        $penggunaan = PenggunaanAir::create([
            'id' => Str::uuid(),
            'penggunas_id' => $validated['penggunas_id'],
            'meter_baca_awal'=> $validated['meter_baca_awal'],
            'meter_baca_akhir'=> $validated['meter_baca_akhir'],
            'konsumsi'       => $validated['konsumsi'],
            'tanggal_catat'  => $validated['tanggal_catat'],
            'periode_bulan'  => $validated['periode_bulan'],
            'periode_tahun'  => $validated['periode_tahun'],
            'sudah_bayar'    => false,
            'foto_meter'     => $validated['foto_meter'] ?? null, // ← simpan path foto
            'dicatat_oleh' => $pencatat->id
        ]);

        return redirect()->route('penggunaan_air.index')->with('success', 'Data penggunaan berhasil disimpan.');
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
    public function edit(PenggunaanAir $penggunaan_air)
    {
        //check policy
        $this->authorize('update', $penggunaan_air);

        $pengguna = Pengguna::find($penggunaan_air->penggunas_id);

        return view('penggunaan_air.edit', [
            'tittle' => 'Perbaharui Penggunaan Air',
            'penggunaan_air' => $penggunaan_air,
            'pengguna' => $pengguna
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penggunaan = PenggunaanAir::findOrFail($id);
        $this->authorize('update', $penggunaan);

        // === Validasi dasar ===
        $rules = [
            'meter_baca_awal' => 'required|integer|min:0',
            'meter_baca_akhir' => 'required|integer|gte:meter_baca_awal',
            'konsumsi' => 'required|integer|min:0',
            'tanggal_catat' => 'required|date',
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer|min:1900|max:2100',
        ];

        // hanya validasi foto kalau memang ada file baru
        if ($request->hasFile('foto_meter')) {
            $rules['foto_meter'] = 'image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($rules);

        // === Cek duplikat ===
        $duplikat = PenggunaanAir::where('penggunas_id', $penggunaan->penggunas_id)
            ->where('periode_bulan', $validated['periode_bulan'])
            ->where('periode_tahun', $validated['periode_tahun'])
            ->where('id', '!=', $id)
            ->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'periode_bulan' => 'Data penggunaan untuk bulan dan tahun ini sudah ada untuk pelanggan ini.'
            ]);
        }

        // === Upload Foto Baru (jika ada) ===
        if ($request->hasFile('foto_meter')) {
            // hapus foto lama kalau ada
            if ($penggunaan->foto_meter && Storage::disk(config('filesystems.default_public_disk'))->exists($penggunaan->foto_meter)) {
                Storage::disk(config('filesystems.default_public_disk'))->delete($penggunaan->foto_meter);
            }

            $filename = time().'_'.$request->file('foto_meter')->getClientOriginalName();
            $path = $request->file('foto_meter')->storeAs('meteran', $filename, config('filesystems.default_public_disk'));
            $validated['foto_meter'] = $path;
        }

        // === Update Data ===
        // kita buat array data yang akan diupdate tanpa menimpa foto jika tidak ada upload baru
        $dataUpdate = [
            'meter_baca_awal' => $validated['meter_baca_awal'],
            'meter_baca_akhir' => $validated['meter_baca_akhir'],
            'konsumsi' => $validated['konsumsi'],
            'tanggal_catat' => $validated['tanggal_catat'],
            'periode_bulan' => $validated['periode_bulan'],
            'periode_tahun' => $validated['periode_tahun'],
        ];

        // hanya tambahkan foto_meter ke array kalau user upload baru
        if (isset($validated['foto_meter'])) {
            $dataUpdate['foto_meter'] = $validated['foto_meter'];
        }

        $penggunaan->update($dataUpdate);

        return redirect()->route('penggunaan_air.index')
            ->with('success', 'Data penggunaan air berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenggunaanAir $penggunaanAir)
    {
        //check policy
        $this->authorize('delete', $penggunaanAir);

        //Hitung nominal piutang
        $tarif = config('tarif.harga_per_m3');
        $nominal = $penggunaanAir->konsumsi * $tarif;

        // Akun: Piutang (debit) vs Pendapatan (kredit)
        $akunPiutang    = Account::where('kode', 'PTNPLG01')->firstOrFail();
        $akunPendapatan = Account::where('kode', 'PNDPMS01')->firstOrFail();

        //Buat jurnal pembalik
        Jurnal::create([
            'id' => Str::uuid(),
            'tanggal' => now(),
            'deskripsi' => "[PEMBALIK] Penggunaan air {$penggunaanAir->penggunas->nama}" .  " Periode {$penggunaanAir->periode_bulan}-{$penggunaanAir->periode_tahun}" . " dihapus oleh " . Auth::user()->nama,
            'debit_account_id' => $akunPendapatan->id,
            'kredit_account_id' => $akunPiutang->id,
            'nominal' => $nominal
        ]);
 
        //Hapus foto meteran kalau ada
        if($penggunaanAir->foto_meter && Storage::disk(config('filesystems.default_public_disk'))->exists($penggunaanAir->foto_meter)){
            Storage::disk(config('filesystems.default_public_disk'))->delete($penggunaanAir->foto_meter);
        }

        $penggunaanAir->delete();

        return redirect()->route('penggunaan_air.index')->with('success', 'Data Penggunaan Air Dihapus dan Jurnal Pembalik berhasil dibuat');
    }
}
