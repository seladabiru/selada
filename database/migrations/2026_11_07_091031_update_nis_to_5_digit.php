<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah NIS yang lebih dari 5 digit menjadi 5 digit acak (unik).
     */
    public function up(): void
    {
        // Ambil semua user yang NIS-nya lebih dari 5 karakter (8 digit dll)
        // Skip admin yang NIS-nya "admin"
        $users = DB::table('users')
            ->whereRaw('LENGTH(nis) > 5')
            ->get();

        // Kumpulkan NIS 5 digit yang sudah dipakai (yang sudah 5 digit)
        $usedNis = DB::table('users')
            ->whereRaw('LENGTH(nis) <= 5')
            ->pluck('nis')
            ->toArray();

        foreach ($users as $user) {
            // Generate 5 digit acak yang unik
            do {
                $newNis = (string) random_int(10000, 99999);
            } while (in_array($newNis, $usedNis));

            $usedNis[] = $newNis;

            DB::table('users')
                ->where('id', $user->id)
                ->update(['nis' => $newNis]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak bisa di-reverse karena NIS asli sudah hilang
    }
};
