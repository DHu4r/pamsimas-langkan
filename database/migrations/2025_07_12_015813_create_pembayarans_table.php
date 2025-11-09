<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penggunaan_air_id');
            $table->integer('jumlah');
            $table->string('metode')->default('cash');
            $table->uuid('dibayar_oleh');

            $table->timestamps();

            // Foreign keys
            $table->foreign('penggunaan_air_id')->references('id')->on('penggunaan_airs')->onDelete('cascade');
            $table->foreign('dibayar_oleh')->references('id')->on('penggunas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
