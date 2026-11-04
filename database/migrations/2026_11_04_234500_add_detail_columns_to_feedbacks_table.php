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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->string('judul')->nullable()->after('kategori');
            $table->string('pic_osis')->nullable()->default('DJ OSIS Audio')->after('balasan');
            $table->string('status_tindak_lanjut')->default('Menunggu Tinjauan')->after('pic_osis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['judul', 'pic_osis', 'status_tindak_lanjut']);
        });
    }
};

