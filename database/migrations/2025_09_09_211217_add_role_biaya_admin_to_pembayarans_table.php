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
            Schema::table('pembayarans', function (Blueprint $table){
                $table->string('role_pembayar')->nullable()->after('dibayar_oleh');
                $table->unsignedInteger('biaya_admin')->default(0)->after('jumlah');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            Schema::table('pembayarans', function (Blueprint $table){
                $table->dropColumn(['role_pembayar', 'biaya_admin']);
            });
        });
    }
};
