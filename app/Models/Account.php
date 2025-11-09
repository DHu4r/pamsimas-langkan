<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false; // ← penting untuk UUID
    protected $keyType = 'string'; // ← UUID adalah string
    
    protected $fillable =[
        'id',
        'kode',
        'nama',
        'tipe',
        'keterangan',
        'pengguna_id'
    ];

    public function penggunas()
    {
        return $this->belongsTo(Pengguna::class);
    }
    public function jurnalDebits()
    {
        return $this->hasMany(Jurnal::class, 'debit_account_id');
    }

    public function jurnalKredits()
    {
        return $this->hasMany(Jurnal::class, 'kredit_account_id');
    }

    public function getSaldoAttribute()
    {
        $debit  = $this->total_debit ?? 0;
        $kredit = $this->total_kredit ?? 0;

        switch (strtolower($this->tipe)) {
            case 'aset':
            case 'beban':
                return $debit - $kredit;

            case 'kewajiban':
            case 'ekuitas':
            case 'pendapatan':
                return $kredit - $debit;

            default:
                return $debit - $kredit; // fallback default
        }
    }
}
