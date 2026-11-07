<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;

class AdminSongController extends Controller
{
    /** Daftar lagu menunggu moderasi */
    public function index()
    {
        $menunggu = Song::with('user')
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->get();

        $antrian = Song::with('user')
            ->whereIn('status', ['disetujui', 'diputar'])
            ->orderByRaw("CASE status WHEN 'diputar' THEN 1 WHEN 'disetujui' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->get();

        $ditolak = Song::with('user')
            ->where('status', 'ditolak')
            ->whereDate('updated_at', today())
            ->orderByDesc('updated_at')
            ->get();

        $totalPermintaanHariIni = Song::whereDate('created_at', today())->count();
        $totalDurasiAntrianMnt = round($antrian->sum('durasi') / 60);

        return view('admin.songs.index', compact(
            'menunggu',
            'antrian',
            'ditolak',
            'totalPermintaanHariIni',
            'totalDurasiAntrianMnt'
        ));
    }

    /** Setujui lagu */
    public function approve(Song $song)
    {
        if ($song->status !== 'menunggu') {
            return back()->with('error', 'Lagu ini tidak dalam status menunggu.');
        }

        // Tempatkan di akhir antrian
        $urutanTerakhir = Song::whereIn('status', ['disetujui', 'diputar'])->max('urutan') ?? 0;

        $song->update([
            'status' => 'disetujui',
            'urutan' => $urutanTerakhir + 1,
            'alasan_ditolak' => null,
        ]);

        return back()->with('success', "Lagu \"{$song->judul}\" disetujui.");
    }

    /** Tolak lagu */
    public function reject(Request $request, Song $song)
    {
        $request->validate([
            'alasan' => ['nullable', 'string', 'max:200'],
        ]);

        $song->update([
            'status'         => 'ditolak',
            'alasan_ditolak' => $request->alasan ?: 'Tidak memenuhi kriteria.',
        ]);

        return back()->with('success', "Lagu \"{$song->judul}\" ditolak.");
    }

    /** Hapus lagu dari antrian */
    public function destroy(Song $song)
    {
        $judul = $song->judul;
        $song->delete();

        return back()->with('success', "Lagu \"{$judul}\" dihapus.");
    }

    /** Pindahkan ke atas dalam antrian */
    public function moveUp(Song $song)
    {
        $prev = Song::whereIn('status', ['disetujui'])
            ->where('urutan', '<', $song->urutan)
            ->orderByDesc('urutan')
            ->first();

        if ($prev) {
            [$song->urutan, $prev->urutan] = [$prev->urutan, $song->urutan];
            $song->save();
            $prev->save();
        }

        return back()->with('success', 'Urutan diperbarui.');
    }

    /** Pindahkan ke bawah dalam antrian */
    public function moveDown(Song $song)
    {
        $next = Song::whereIn('status', ['disetujui'])
            ->where('urutan', '>', $song->urutan)
            ->orderBy('urutan')
            ->first();

        if ($next) {
            [$song->urutan, $next->urutan] = [$next->urutan, $song->urutan];
            $song->save();
            $next->save();
        }

        return back()->with('success', 'Urutan diperbarui.');
    }

    /** Skip (tandai gagal, lanjut ke berikutnya) */
    public function skip(Song $song)
    {
        $song->update(['status' => 'gagal', 'alasan_ditolak' => 'Di-skip oleh admin.']);
        return back()->with('success', "Lagu \"{$song->judul}\" di-skip.");
    }
}
