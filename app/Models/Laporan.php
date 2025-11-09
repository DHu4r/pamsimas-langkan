<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Laporan extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'pembuat_id',
        'periode_bulan',
        'periode_tahun',
        'catatan',
        'tanggal_generate',
        'file_pdf_path',
        'jumlah_pelanggan',
        'total_piutang',
        'total_pemasukan',
    ];

    protected $casts = [
        'tanggal_generate' => 'date',
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'jumlah_pelanggan' => 'integer',
        'total_piutang' => 'integer',
        'total_pemasukan' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function getNamaBulanAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $namaBulan[$this->periode_bulan] ?? 'Tidak valid';
    }

}

