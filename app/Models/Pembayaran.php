<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\TanggalIndo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use TanggalIndo;
    use HasFactory, HasUuids;

    protected $fillable = [
        'penggunaan_air_id',
        'jumlah',
        'metode',
        'dibayar_oleh',
        'nama_rekening',
        'nama_bank',
        'file_path',
        'role_pembayar',
        'biaya_admin',
        'no_rekening'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'biaya_admin' => 'integer',
    ];

    public function penggunaanAir()
    {
        return $this->belongsTo(PenggunaanAir::class, 'penggunaan_air_id');
    }

    public function dibayarOleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibayar_oleh', 'id');
    }

}
