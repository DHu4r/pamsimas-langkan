<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penggunaan_airs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('penggunas_id');
            $table->unsignedBigInteger('meter_baca_awal');
            $table->unsignedBigInteger('meter_baca_akhir');
            $table->unsignedBigInteger('konsumsi')->default(0);
            $table->date('tanggal_catat');
            $table->unsignedTinyInteger('periode_bulan');
            $table->smallInteger('periode_tahun');
            $table->boolean('sudah_bayar')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_airs');
    }
};
