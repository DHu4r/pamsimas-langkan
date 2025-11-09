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
        Schema::create('laporans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('pembuat_id');
            $table->unsignedTinyInteger('periode_bulan');        // 1..12
            $table->unsignedSmallInteger('periode_tahun');       // ex: 2025
            $table->string('catatan')->nullable();
            $table->date('tanggal_generate')->nullable();
            $table->string('file_pdf_path')->nullable();         // path di disk (public)
            $table->unsignedInteger('jumlah_pelanggan')->default(0);
            $table->bigInteger('total_piutang')->default(0);     // pake bigInt jika nominal besar
            $table->bigInteger('total_pemasukan')->default(0);
            $table->timestamps();

            $table->unique(['periode_bulan', 'periode_tahun']);  // optional: satu laporan per periode
            $table->index(['pembuat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
