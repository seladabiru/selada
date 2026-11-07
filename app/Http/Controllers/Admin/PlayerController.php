<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Song;
use App\Services\YouTubeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $totalPutarHariIni = Song::whereDate('updated_at', today())->whereIn('status', ['diputar', 'selesai'])->count();
        $totalDurasiDetik = Song::whereDate('updated_at', today())->whereIn('status', ['diputar', 'selesai'])->sum('durasi');
        $totalDurasiMnt = round($totalDurasiDetik / 60);
        $sisaAntrianSesi = $antrian->count();

        return view('admin.player', compact(
            'sedangDiputar',
            'antrian',
            'semuaJadwal',
            'jadwalAktifSekarang',
            'totalPutarHariIni',
            'totalDurasiMnt',
            'sisaAntrianSesi'
        ));
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
                'id'               => $song->id,
                'youtube_id'       => $song->youtube_id,
                'judul'            => $song->judul,
                'channel'          => $song->channel,
                'thumbnail'        => $song->thumbnail,
                'durasi'           => $song->durasi,
                'durasi_formatted' => $song->durasiFormatted(),
                'pesan'            => $song->pesan,
                'user'             => $song->user?->name,
                'kelas'            => $song->user?->kelas,
                'nis'              => $song->user?->nis,
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
                    'id'               => $nextSong->id,
                    'youtube_id'       => $nextSong->youtube_id,
                    'judul'            => $nextSong->judul,
                    'channel'          => $nextSong->channel,
                    'thumbnail'        => $nextSong->thumbnail,
                    'durasi'           => $nextSong->durasi,
                    'durasi_formatted' => $nextSong->durasiFormatted(),
                    'pesan'            => $nextSong->pesan,
                    'user'             => $nextSong->user?->name,
                    'kelas'            => $nextSong->user?->kelas,
                    'nis'              => $nextSong->user?->nis,
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
                    'id'               => $nextSong->id,
                    'youtube_id'       => $nextSong->youtube_id,
                    'judul'            => $nextSong->judul,
                    'channel'          => $nextSong->channel,
                    'thumbnail'        => $nextSong->thumbnail,
                    'durasi'           => $nextSong->durasi,
                    'durasi_formatted' => $nextSong->durasiFormatted(),
                    'pesan'            => $nextSong->pesan,
                    'user'             => $nextSong->user?->name,
                    'kelas'            => $nextSong->user?->kelas,
                    'nis'              => $nextSong->user?->nis,
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
     * Input manual lagu dari DJ Player (tanpa moderasi, langsung disetujui).
     */
    public function manualStore(Request $request): JsonResponse
    {
        $request->validate([
            'youtube_url' => 'required|string',
            'judul'       => 'nullable|string|max:255',
            'pesan'      => 'nullable|string|max:500',
        ]);

        // Extract YouTube ID dari URL
        $url = $request->input('youtube_url');
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        $videoId = $matches[1] ?? null;

        if (! $videoId) {
            if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
                $videoId = $url;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'URL atau ID YouTube tidak valid.',
                ], 422);
            }
        }

        // Cek duplikat di antrian aktif
        $sudahAda = Song::where('youtube_id', $videoId)
            ->whereIn('status', ['menunggu', 'disetujui', 'diputar'])
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'message' => 'Lagu ini sudah ada di antrian aktif.',
            ], 422);
        }

        // Auto-detect judul, channel, dan thumbnail via YouTube oEmbed API (gratis, tanpa API key)
        $judul = $request->input('judul');
        $channel = 'Input Manual DJ';
        $thumbnail = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
        $durasi = 0;

        if (! $judul) {
            try {
                $oembedResponse = Http::timeout(8)->get("https://www.youtube.com/oembed", [
                    'url'    => "https://www.youtube.com/watch?v={$videoId}",
                    'format' => 'json',
                ]);

                if ($oembedResponse->successful()) {
                    $oembedData = $oembedResponse->json();
                    $judul = $oembedData['title'] ?? null;
                    $channel = $oembedData['author_name'] ?? $channel;
                    $thumbnail = $oembedData['thumbnail_url'] ?? $thumbnail;
                }
            } catch (\Exception $e) {
                // Fallback kalau oEmbed gagal
            }

            // Kalau masih kosong, coba pakai YouTube Data API
            if (! $judul) {
                $youtube = new YouTubeService();
                $detail = $youtube->getVideoDetails($videoId);
                if ($detail) {
                    $judul = $detail['judul'];
                    $channel = $detail['channel'] ?: $channel;
                    $thumbnail = $detail['thumbnail'] ?: $thumbnail;
                    $durasi = $detail['durasi'];
                }
            }

            // Kalau masih kosong, pakai fallback
            if (! $judul) {
                $judul = 'Lagu Manual - ' . $videoId;
            }
        }

        // Ambil urutan terakhir
        $urutanTerakhir = Song::whereIn('status', ['menunggu', 'disetujui'])
            ->max('urutan') ?? 0;

        // Buat lagu baru dengan status langsung disetujui (bypass moderasi)
        $song = Song::create([
            'user_id'    => $request->user()->id,
            'youtube_id' => $videoId,
            'judul'      => $judul,
            'channel'    => $channel,
            'thumbnail'  => $thumbnail,
            'pesan'      => $request->input('pesan'),
            'durasi'     => $durasi,
            'status'     => 'disetujui',
            'urutan'     => $urutanTerakhir + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Lagu \"{$song->judul}\" berhasil ditambahkan ke antrian!",
            'song'    => [
                'id'               => $song->id,
                'youtube_id'       => $song->youtube_id,
                'judul'            => $song->judul,
                'channel'          => $song->channel,
                'thumbnail'        => $song->thumbnail,
                'durasi'           => $song->durasi,
                'durasi_formatted' => $song->durasiFormatted(),
                'pesan'            => $song->pesan,
                'user'             => $song->user?->name,
                'kelas'            => $song->user?->kelas,
                'nis'              => $song->user?->nis,
                'urutan'           => $song->urutan,
            ],
        ]);
    }

    /**
     * Endpoint status pemutar & antrian terkini untuk AJAX sync.
     * Digunakan untuk real-time dashboard updates & player sync.
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

        // Stats untuk dashboard
        $menungguCount  = Song::where('status', 'menunggu')->count();
        $antrianCount   = Song::whereIn('status', ['disetujui', 'diputar'])->count();
        $totalPutarHariIni = Song::whereDate('updated_at', today())->whereIn('status', ['diputar', 'selesai'])->count();
        $totalDurasiDetik = Song::whereDate('updated_at', today())->whereIn('status', ['diputar', 'selesai'])->sum('durasi');
        $totalDurasiMnt = round($totalDurasiDetik / 60);
        $unreadFeedback = \App\Models\Feedback::where('is_read', false)->count();

        // Calculate current playback time (estimate based on when song started playing)
        $currentTime = 0;
        if ($sedangDiputar && $sedangDiputar->updated_at) {
            $elapsedSeconds = now()->diffInSeconds($sedangDiputar->updated_at);
            $currentTime = min($elapsedSeconds, $sedangDiputar->durasi ?? $elapsedSeconds);
        }

        return response()->json([
            'sedang_diputar' => $sedangDiputar ? [
                'id'           => $sedangDiputar->id,
                'youtube_id'   => $sedangDiputar->youtube_id,
                'judul'        => $sedangDiputar->judul,
                'channel'      => $sedangDiputar->channel,
                'thumbnail'    => $sedangDiputar->thumbnail,
                'durasi'       => $sedangDiputar->durasi,
                'durasi_fmt'   => $sedangDiputar->durasiFormatted(),
                'current_time' => $currentTime,
                'pesan'        => $sedangDiputar->pesan,
                'user'         => $sedangDiputar->user?->name,
                'kelas'        => $sedangDiputar->user?->kelas,
                'nis'          => $sedangDiputar->user?->nis,
            ] : null,
            'antrian_count' => $antrian->count(),
            'antrian_next' => $antrian->take(3)->map(fn($s) => [
                'id'       => $s->id,
                'judul'    => $s->judul,
                'user'     => $s->user?->name,
                'kelas'    => $s->user?->kelas,
                'pesan'    => $s->pesan,
                'thumbnail' => $s->thumbnail,
            ])->toArray(),
            'jadwal_aktif'  => $jadwalAktifSekarang ? [
                'nama'        => $jadwalAktifSekarang->nama_sesi,
                'jam_mulai'   => substr($jadwalAktifSekarang->jam_mulai, 0, 5),
                'jam_selesai' => substr($jadwalAktifSekarang->jam_selesai, 0, 5),
                'sisa_menit'  => $jadwalAktifSekarang->sisaMenit(),
            ] : null,
            'stats' => [
                'menunggu'     => $menungguCount,
                'antrian'      => $antrianCount,
                'putar_hari'   => $totalPutarHariIni,
                'durasi_mnt'   => $totalDurasiMnt,
                'feedback'     => $unreadFeedback,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

