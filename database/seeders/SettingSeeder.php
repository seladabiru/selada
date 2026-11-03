<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'        => 'batas_request_per_hari',
                'value'      => '1',
                'deskripsi'  => 'Maksimal request lagu per siswa per hari',
            ],
            [
                'key'        => 'maks_durasi_detik',
                'value'      => '600',
                'deskripsi'  => 'Durasi maksimal lagu dalam detik (600 = 10 menit)',
            ],
            [
                'key'        => 'blacklist_kata',
                'value'      => '',
                'deskripsi'  => 'Kata yang dilarang di judul lagu, pisahkan dengan koma',
            ],
            [
                'key'        => 'mode_siaran',
                'value'      => 'standby',
                'deskripsi'  => 'Status siaran: standby atau live',
            ],
            [
                'key'        => 'moderasi_otomatis',
                'value'      => '0',
                'deskripsi'  => 'Jika 1, lagu langsung disetujui tanpa moderasi admin',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
