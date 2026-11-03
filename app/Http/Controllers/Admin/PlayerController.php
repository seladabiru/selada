<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Song;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlayerController extends Controller
{
    /**
     * Halaman utama pemutar musik / siaran radio sekolah.
     */
    public function index(): View
    {
        $sedangDiputar = Song::with('user')->where('status', 'diputar')->first();

        $antrian = Song::with('user')
            ->where('status', 'disetujui')
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->get();

        $semuaJadwal = Schedule::orderBy('jam_mulai')->get();
        $jadwalAktifSekarang = $semuaJadwal->first(fn ($j) => $j->isNowActive());

        return view('admin.player', compact('sedangDiputar', 'antrian', 'semuaJadwal', 'jadwalAktifSekarang'));
    }

    /**
     * Memulai pemutaran lagu tertentu.
     */
    public function play(Song $song): JsonResponse
    {
        // Tandai lagu yang sedang diputar sebelumnya menjadi 'selesai'
        Song::where('status', 'diputar')
            ->where('id', '!=', $song->id)
            ->update(['status' => 'selesai']);

        // Ubah lagu terpilih menjadi 'diputar'
        $song->update(['status' => 'diputar']);

        return response()->json([
            'success' => true,
            'message' => "Memutar lagu: {$song->judul}",
            'song'    => [
                'id'         => $song->id,
                'youtube_id' => $song->youtube_id,
                'judul'      => $song->judul,
                'channel'    => $song->channel,
                'thumbnail'  => $song->thumbnail,
                'durasi'     => $song->durasi,
                'pesan'      => $song->pesan,
                'user'       => $song->user?->name,
                'kelas'      => $song->user?->kelas,
            ],
        ]);
    }

    /**
     * Dipanggil saat sebuah lagu selesai diputar (otomatis dari YouTube player).
     * Tandai lagu ini 'selesai' dan otomatis ambil lagu disetujui berikutnya.
     */
    public function finish(Song $song): JsonResponse
    {
        $song->update(['status' => 'selesai']);

        // Cari lagu disetujui berikutnya
        $nextSong = Song::with('user')
            ->where('status', 'disetujui')
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->first();

        if ($nextSong) {
            $nextSong->update(['status' => 'diputar']);

            return response()->json([
                'success'   => true,
                'has_next'  => true,
                'message'   => "Lanjut memutar: {$nextSong->judul}",
                'song'      => [
                    'id'         => $nextSong->id,
                    'youtube_id' => $nextSong->youtube_id,
                    'judul'      => $nextSong->judul,
                    'channel'    => $nextSong->channel,
                    'thumbnail'  => $nextSong->thumbnail,
                    'durasi'     => $nextSong->durasi,
                    'pesan'      => $nextSong->pesan,
                    'user'       => $nextSong->user?->name,
                    'kelas'      => $nextSong->user?->kelas,
                ],
            ]);
        }

        return response()->json([
            'success'  => true,
            'has_next' => false,
            'message'  => 'Antrian lagu selesai.',
            'song'     => null,
        ]);
    }

    /**
     * Skip lagu (karena di-skip operator atau error di pemutar YouTube).
     */
    public function skip(Request $request, Song $song): JsonResponse
    {
        $alasan = $request->input('alasan', 'Di-skip oleh admin/player');
        $status = $request->input('error') ? 'gagal' : 'selesai';

        $song->update([
            'status'         => $status,
            'alasan_ditolak' => $alasan,
        ]);

        // Cari lagu disetujui berikutnya
        $nextSong = Song::with('user')
            ->where('status', 'disetujui')
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->first();

        if ($nextSong) {
            $nextSong->update(['status' => 'diputar']);

            return response()->json([
                'success'   => true,
                'has_next'  => true,
                'message'   => "Lanjut memutar: {$nextSong->judul}",
                'song'      => [
                    'id'         => $nextSong->id,
                    'youtube_id' => $nextSong->youtube_id,
                    'judul'      => $nextSong->judul,
                    'channel'    => $nextSong->channel,
                    'thumbnail'  => $nextSong->thumbnail,
                    'durasi'     => $nextSong->durasi,
                    'pesan'      => $nextSong->pesan,
                    'user'       => $nextSong->user?->name,
                    'kelas'      => $nextSong->user?->kelas,
                ],
            ]);
        }

        return response()->json([
            'success'  => true,
            'has_next' => false,
            'message'  => 'Tidak ada lagu berikutnya dalam antrian.',
            'song'     => null,
        ]);
    }

    /**
     * Endpoint status pemutar & antrian terkini untuk AJAX sync.
     */
    public function currentStatus(): JsonResponse
    {
        $sedangDiputar = Song::with('user')->where('status', 'diputar')->first();

        $antrian = Song::with('user')
            ->where('status', 'disetujui')
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->get();

        $semuaJadwal = Schedule::orderBy('jam_mulai')->get();
        $jadwalAktifSekarang = $semuaJadwal->first(fn ($j) => $j->isNowActive());

        return response()->json([
            'sedang_diputar' => $sedangDiputar ? [
                'id'         => $sedangDiputar->id,
                'youtube_id' => $sedangDiputar->youtube_id,
                'judul'      => $sedangDiputar->judul,
                'channel'    => $sedangDiputar->channel,
                'thumbnail'  => $sedangDiputar->thumbnail,
                'durasi'     => $sedangDiputar->durasi,
                'durasi_fmt' => $sedangDiputar->durasiFormatted(),
                'pesan'      => $sedangDiputar->pesan,
                'user'       => $sedangDiputar->user?->name,
                'kelas'      => $sedangDiputar->user?->kelas,
            ] : null,
            'antrian_count' => $antrian->count(),
            'jadwal_aktif'  => $jadwalAktifSekarang ? [
                'nama'        => $jadwalAktifSekarang->nama_sesi,
                'jam_mulai'   => substr($jadwalAktifSekarang->jam_mulai, 0, 5),
                'jam_selesai' => substr($jadwalAktifSekarang->jam_selesai, 0, 5),
            ] : null,
        ]);
    }
}

