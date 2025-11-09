<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    /** @use HasFactory<\Database\Factories\JurnalFactory> */
    use HasFactory;
    public $incrementing = false; // untuk UUID
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'tanggal',
        'deskripsi',
        'debit_account_id',
        'kredit_account_id',
        'nominal',
    ];
    public function getSaldoAttribute()
    {
        $debit  = $this->jurnals()->sum('debit');
        $kredit = $this->jurnals()->sum('kredit');

        return $debit - $kredit;
    }

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function kreditAccount()
    {
        return $this->belongsTo(Account::class, 'kredit_account_id');
    }
}
