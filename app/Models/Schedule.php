<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'nama_sesi',
        'jam_mulai',
        'jam_selesai',
        'hari_aktif',
        'is_active',
    ];

    protected $casts = [
        'hari_aktif' => 'array',
        'is_active'  => 'boolean',
    ];

    /**
     * Cek apakah jadwal ini aktif SEKARANG.
     */
    public function isNowActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd'); // "Senin", "Selasa", dst
        $hariEn  = Carbon::now()->format('l');                     // "Monday", dst
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $hariId = $hariMap[$hariEn] ?? $hariEn;

        if (! in_array($hariId, $this->hari_aktif ?? [])) {
            return false;
        }

        $now    = Carbon::now()->format('H:i:s');
        return $now >= $this->jam_mulai && $now <= $this->jam_selesai;
    }

    /**
     * Scope: hanya jadwal yang aktif (is_active = true).
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
