<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_id', 20);
            $table->string('judul');
            $table->string('channel')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedSmallInteger('durasi')->default(0); // dalam detik
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'diputar', 'selesai', 'gagal'])
                  ->default('menunggu');
            $table->text('alasan_ditolak')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0); // urutan dalam antrian
            $table->timestamps();

            // Satu youtube_id tidak boleh ada dua kali di antrian aktif
            // Pengecekan duplikat dilakukan di controller, bukan unique constraint
            // karena lagu yang sudah selesai boleh direquest ulang
            $table->index(['status', 'urutan']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
