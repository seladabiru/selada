<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $hariSekolah = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        Schedule::updateOrCreate(
            ['nama_sesi' => 'Istirahat 1'],
            [
                'jam_mulai'   => '09:30:00',
                'jam_selesai' => '09:45:00',
                'hari_aktif'  => $hariSekolah,
                'is_active'   => true,
            ]
        );

        Schedule::updateOrCreate(
            ['nama_sesi' => 'Istirahat 2'],
            [
                'jam_mulai'   => '12:00:00',
                'jam_selesai' => '12:30:00',
                'hari_aktif'  => $hariSekolah,
                'is_active'   => true,
            ]
        );
    }
}
