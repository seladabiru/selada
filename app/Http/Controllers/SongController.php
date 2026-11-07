<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSongRequest;
use App\Models\Setting;
use App\Models\Song;
use App\Services\YouTubeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SongController extends Controller
{
    public function __construct(protected YouTubeService $youtube) {}

    // ──────────────────────────────────────────────────────
    //  Halaman antrian & request (Siswa & Admin)
    // ──────────────────────────────────────────────────────

    public function index(Request $request): View|Response
    {
        $user = $request->user();

        // 1. Antrian lagu aktif (hanya yang sudah disetujui atau sedang diputar)
        $antrian = Song::with('user')
            ->whereIn('status', ['disetujui', 'diputar'])
            ->orderByRaw("CASE status WHEN 'diputar' THEN 1 WHEN 'disetujui' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->get();

        // Respons parsial untuk live AJAX polling antrian
        if ($request->ajax() && $request->has('poll')) {
            return response()->view('songs._antrian_list', compact('antrian'));
        }

        // 2. Riwayat pemutaran umum (20 lagu terakhir selesai/ditolak)
        $riwayat = Song::with('user')
            ->whereIn('status', ['selesai', 'ditolak', 'gagal'])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        // 3. Riwayat khusus siswa yang sedang login
        $mySongs = Song::with('user')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // 4. Statistik Lagu Terpopuler (Top 5 berdasarkan total request)
        // Hanya hitung lagu yang sudah diputar SAMPAI HABIS (status = 'selesai')
        $topSongs = Song::select('youtube_id', 'judul', 'channel', 'thumbnail')
            ->selectRaw('count(*) as request_count')
            ->where('status', 'selesai')
            ->groupBy('youtube_id', 'judul', 'channel', 'thumbnail')
            ->orderByDesc('request_count')
            ->limit(5)
            ->get();

        // Cek kuota request hari ini untuk user
        $batasPerHari = (int) Setting::get('batas_request_per_hari', 1);
        $requestHariIni = Song::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();
        $sisaKuota = max(0, $batasPerHari - $requestHariIni);

        return view('songs.index', compact(
            'antrian',
            'riwayat',
            'mySongs',
            'topSongs',
            'batasPerHari',
            'requestHariIni',
            'sisaKuota'
        ));
    }

    /**
     * Halaman Top Hits & Tangga Lagu Sekolah (Hall of Fame)
     * Hanya menampilkan lagu yang sudah diputar SAMPAI HABIS (status = 'selesai')
     */
    public function topHits(Request $request): View
    {
        $period = $request->input('period', 'week');

        // Query lagu berdasarkan jumlah request - hanya lagu yang sudah SELESAI diputar
        $query = Song::with('user')
            ->where('status', 'selesai');

        if ($period === 'week') {
            $query->where('created_at', '>=', now()->startOfWeek());
        } elseif ($period === 'month') {
            $query->where('created_at', '>=', now()->startOfMonth());
        }

        $rankedSongs = $query->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Jika data dalam periode kosong, fallback ambil semua lagu yang selesai diputar
        if ($rankedSongs->isEmpty()) {
            $rankedSongs = Song::with('user')
                ->where('status', 'selesai')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        // Top 1, 2, 3 podium
        $top1 = $rankedSongs->get(0);
        $top2 = $rankedSongs->get(1);
        $top3 = $rankedSongs->get(2);

        // Leaderboard rank 4 - 8
        $leaderboard = $rankedSongs->slice(3, 5);

        // Top 3 Requesters (Siswa Paling Hits) - hanya hitung lagu yang selesai
        $topRequesters = \App\Models\User::withCount(['songs' => function ($q) {
                $q->where('status', 'selesai');
            }])
            ->where('role', 'siswa')
            ->orderByDesc('songs_count')
            ->limit(3)
            ->get();

        return view('songs.top_hits', compact(
            'period',
            'top1',
            'top2',
            'top3',
            'leaderboard',
            'topRequesters'
        ));
    }

    // ──────────────────────────────────────────────────────
    //  Form request lagu
    // ──────────────────────────────────────────────────────

    public function create(Request $request): View
    {
        $user = $request->user();
        $batasPerHari = (int) Setting::get('batas_request_per_hari', 2);
        $maksDurasi = (int) Setting::get('maks_durasi_detik', 600);
        $requestHariIni = Song::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();
        $sisaKuota = max(0, $batasPerHari - $requestHariIni);

        return view('songs.create', compact('batasPerHari', 'maksDurasi', 'requestHariIni', 'sisaKuota'));
    }

    // ──────────────────────────────────────────────────────
    //  Simpan request lagu
    // ──────────────────────────────────────────────────────

    public function store(StoreSongRequest $request): RedirectResponse
    {
        $user = $request->user();

        // ── 1. Cek batas request harian ──────────────────
        $batasPerHari = (int) Setting::get('batas_request_per_hari', 1);
        $requestHariIni = Song::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        if ($requestHariIni >= $batasPerHari) {
            return back()
                ->withInput()
                ->with('error', "Kamu sudah request {$batasPerHari} lagu hari ini. Coba lagi besok.");
        }

        // ── 2. Parsing URL / ID YouTube ──────────────────
        $videoId = $this->youtube->extractVideoId($request->youtube_url);

        if (! $videoId) {
            return back()
                ->withInput()
                ->with('error', 'Link YouTube tidak valid. Pastikan kamu menempelkan link yang benar.');
        }

        // ── 3. Cek duplikat di antrian aktif ─────────────
        $sudahAda = Song::where('youtube_id', $videoId)
            ->whereIn('status', ['menunggu', 'disetujui', 'diputar'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with('error', 'Lagu ini sudah ada di antrian. Pilih lagu lain.');
        }

        // ── 4. Ambil detail video dari YouTube API ────────
        $detail = $this->youtube->getVideoDetails($videoId);

        // Kalau API key belum diisi, pakai data minimal
        if (! $detail) {
            $detail = [
                'youtube_id' => $videoId,
                'judul'      => 'Judul belum terverifikasi',
                'channel'    => '',
                'thumbnail'  => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
                'durasi'     => 0,
                'embeddable' => true,
            ];
        }

        // ── 5. Validasi video bisa di-embed ──────────────
        if (! $detail['embeddable']) {
            return back()
                ->withInput()
                ->with('error', 'Video ini tidak bisa diputar (mungkin karena pembatasan izin dari pemiliknya). Pilih lagu lain.');
        }

        // ── 6. Validasi durasi (maks setting detik) ──────
        $maksDurasi = (int) Setting::get('maks_durasi_detik', 600);
        if ($detail['durasi'] > 0 && $detail['durasi'] > $maksDurasi) {
            $menit = intdiv($maksDurasi, 60);
            return back()
                ->withInput()
                ->with('error', "Durasi lagu terlalu panjang. Maksimal {$menit} menit.");
        }

        // ── 7. Cek blacklist judul/channel/pesan ─────────
        $blacklist = Setting::get('blacklist_kata', '');
        if ($blacklist) {
            $kataBlacklist = array_filter(array_map('trim', explode(',', strtolower($blacklist))));
            $judulLower    = strtolower($detail['judul']);
            $pesanLower    = strtolower($request->input('pesan', ''));
            foreach ($kataBlacklist as $kata) {
                if ($kata && (str_contains($judulLower, $kata) || str_contains($pesanLower, $kata))) {
                    return back()
                        ->withInput()
                        ->with('error', 'Lagu atau pesan ini mengandung kata yang dilarang oleh kebijakan sekolah.');
                }
            }
        }

        // ── 8. Tentukan urutan antrian ────────────────────
        $urutanTerakhir = Song::whereIn('status', ['menunggu', 'disetujui'])
            ->max('urutan') ?? 0;

        // ── 9. Tentukan status awal (moderasi otomatis?) ──
        $moderasiOtomatis = Setting::get('moderasi_otomatis', '0') === '1';
        $statusAwal = $moderasiOtomatis ? 'disetujui' : 'menunggu';

        // ── 10. Simpan ke database ────────────────────────
        $song = Song::create([
            'user_id'    => $user->id,
            'youtube_id' => $detail['youtube_id'],
            'judul'      => $detail['judul'],
            'channel'    => $detail['channel'],
            'thumbnail'  => $detail['thumbnail'],
            'pesan'      => $request->input('pesan'),
            'durasi'     => $detail['durasi'],
            'status'     => $statusAwal,
            'urutan'     => $urutanTerakhir + 1,
        ]);

        $pesan = $moderasiOtomatis
            ? "Lagu \"{$song->judul}\" berhasil masuk antrian!"
            : "Request lagu \"{$song->judul}\" berhasil dikirim! Menunggu persetujuan admin.";

        return redirect()->route('songs.index')->with('success', $pesan);
    }
}
