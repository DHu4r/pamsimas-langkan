<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penggunaan_airs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('dicatat_oleh');

            $table->uuid('approved_by')
                  ->nullable()
                  ->after('status');

            $table->timestamp('approved_at')
                  ->nullable()
                  ->after('approved_by');

            $table->text('catatan_verifikasi')
                  ->nullable()
                  ->after('approved_at');

            // Optional: foreign key (kalau mau strict)
            // $table->foreign('approved_by')
            //       ->references('id')
            //       ->on('penggunas')
            //       ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penggunaan_airs', function (Blueprint $table) {

            // Kalau pakai FK, drop dulu
            // $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'status',
                'approved_by',
                'approved_at',
                'catatan_verifikasi',
            ]);
        });
    }
};
