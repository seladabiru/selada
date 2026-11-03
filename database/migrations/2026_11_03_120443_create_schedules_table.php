<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi');                    // mis. "Istirahat 1"
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            // Hari aktif: JSON array, mis. ["Senin","Selasa","Rabu","Kamis","Jumat"]
            $table->json('hari_aktif');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
