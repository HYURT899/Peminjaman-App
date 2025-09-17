<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjams', function (Blueprint $table) {
            $table->id();

            // Ganti user_id dengan nama_peminjam
            $table->string('nama_peminjam');

            // Relasi ke asset tetap ada
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');

            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->text('keperluan');

            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'])->default('menunggu');

            // Ganti foreignId jadi string biasa
            $table->string('disetujui_oleh')->nullable();
            $table->timestamp('disetujui_pada')->nullable();

            $table->text('catatan')->default('-');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
