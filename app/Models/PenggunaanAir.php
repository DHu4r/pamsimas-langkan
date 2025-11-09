<?php

namespace App\Models;

use App\Traits\TanggalIndo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenggunaanAir extends Model
{
    use HasFactory;
    use TanggalIndo;

    public $incrementing = false; // ← penting untuk UUID
    protected $keyType = 'string'; // ← UUID adalah string
    protected $fillable = [
        'id',
        'penggunas_id',
        'meter_baca_awal',
        'meter_baca_akhir',
        'konsumsi',
        'tanggal_catat',
        'periode_bulan',
        'periode_tahun',
        'sudah_bayar',
        'foto_meter',
    ];

    public function penggunas(){
        return $this->belongsTo(Pengguna::class);
    }

    public function pembayarans()
    {
        return $this->hasOne(Pembayaran::class, 'penggunaan_air_id');
    }

    protected static function booted()
    {
        static::created(function ($penggunaanAir) {
            event(new \App\Events\PenggunaanAirCreated($penggunaanAir));
        });
        
        static::creating(function ($model) {
            $model->konsumsi = max(0, $model->meter_baca_akhir - $model->meter_baca_awal);
        });
    }

    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulan[$this->periode_bulan] ?? 'Tidak valid';
    }

    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }



}
