<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;
    public $incrementing = false; // untuk UUID
    protected $keyType = 'string';

    protected $fillable = ['nama', 'komplek', 'role', 'no_hp', 'password'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function penggunaan_airs(){
        return $this->hasMany(PenggunaanAir::class, 'penggunas_id');  
    }
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function penggunaanAirsDicatat()
    {
        return $this->hasMany(PenggunaanAir::class, 'dicatat_oleh');
    }

}
