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
        Schema::table('assets', function (Blueprint $table) {
            // Tambahkan kolom baru untuk menyimpan PATH file QR Code
            $table->string('qr_code')->nullable()->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn('qr_code');
        });
    }
};