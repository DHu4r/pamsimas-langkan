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
        Schema::table('penggunaan_airs', function (Blueprint $table) {
            $table->uuid('dicatat_oleh')->nullable()->after('periode_tahun');

            $table->foreign('dicatat_oleh')
                  ->references('id')->on('penggunas')
                  ->onDelete('set null'); // kalau penggunas dihapus, nilai jadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggunaan_airs', function (Blueprint $table) {
            $table->dropForeign(['dicatat_oleh']);
            $table->dropColumn('dicatat_oleh');
        });
    }
};
