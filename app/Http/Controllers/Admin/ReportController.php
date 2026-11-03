<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilan rekapitulasi & laporan siaran lagu.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->toDateString());
        $endDate   = $request->input('end_date', Carbon::today()->toDateString());
        $status    = $request->input('status', 'semua');
        $search    = $request->input('q', '');

        $query = Song::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status !== 'semua' && in_array($status, ['menunggu', 'disetujui', 'diputar', 'selesai', 'ditolak', 'gagal'])) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('channel', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%");
                  });
            });
        }

        // Ambil data untuk tabel dengan pagination
        $songs = (clone $query)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan Statistik
        $baseStatQuery = Song::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRequest   = (clone $baseStatQuery)->count();
        $totalSelesai   = (clone $baseStatQuery)->where('status', 'selesai')->count();
        $totalDitolak   = (clone $baseStatQuery)->where('status', 'ditolak')->count();
        $totalDurasiDetik = (clone $baseStatQuery)->where('status', 'selesai')->sum('durasi');
        $totalDurasiMenit = intdiv($totalDurasiDetik, 60);

        // Top 5 Siswa Teraktif Request Lagu
        $topSiswa = User::whereHas('songs', function ($q) use ($startDate, $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            })
            ->withCount(['songs' => function ($q) use ($startDate, $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }])
            ->orderByDesc('songs_count')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'songs',
            'startDate',
            'endDate',
            'status',
            'search',
            'totalRequest',
            'totalSelesai',
            'totalDitolak',
            'totalDurasiMenit',
            'topSiswa'
        ));
    }

    /**
     * Halaman cetak laporan resmi yang rapi untuk arsip sekolah.
     */
    public function print(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::today()->subDays(30)->toDateString());
        $endDate   = $request->input('end_date', Carbon::today()->toDateString());
        $status    = $request->input('status', 'semua');

        $query = Song::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($status !== 'semua' && in_array($status, ['menunggu', 'disetujui', 'diputar', 'selesai', 'ditolak', 'gagal'])) {
            $query->where('status', $status);
        }

        $songs = $query->orderBy('created_at')->get();

        $totalRequest   = $songs->count();
        $totalSelesai   = $songs->where('status', 'selesai')->count();
        $totalDitolak   = $songs->where('status', 'ditolak')->count();
        $totalDurasi    = intdiv($songs->where('status', 'selesai')->sum('durasi'), 60);

        return view('admin.reports.print', compact(
            'songs',
            'startDate',
            'endDate',
            'status',
            'totalRequest',
            'totalSelesai',
            'totalDitolak',
            'totalDurasi'
        ));
    }
}

