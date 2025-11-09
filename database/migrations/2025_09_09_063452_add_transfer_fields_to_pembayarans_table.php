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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('nama_rekening')->nullable()->after('dibayar_oleh');
            $table->string('nama_bank')->nullable()->after('nama_rekening');
            $table->string('file_path')->nullable()->after('nama_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['nama_rekening', 'nama_bank', 'file_path']);
        });
    }
};
