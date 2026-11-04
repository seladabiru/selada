<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('jam_mulai')->get();
        
        $totalSesi = $schedules->count();
        $totalAktif = $schedules->where('is_active', true)->count();
        
        // Menghitung total alokasi menit on-air dari sesi yang aktif
        $totalMenitOnAir = $schedules->where('is_active', true)->sum(function ($s) {
            $start = strtotime($s->jam_mulai);
            $end = strtotime($s->jam_selesai);
            return max(0, round(($end - $start) / 60));
        });

        // Kapasitas lagu harian (estimasi 3.8 menit per lagu)
        $kapasitasLagu = $totalMenitOnAir > 0 ? (int) round($totalMenitOnAir / 3.8) : 0;

        // Cari sesi yang aktif saat ini
        $activeNow = $schedules->first(fn($s) => $s->isNowActive());

        // Jika tidak ada yang aktif detik ini, ambil sesi aktif pertama untuk preview studio
        $displaySession = $activeNow ?? $schedules->where('is_active', true)->first() ?? $schedules->first();

        // Cari lagu yang sedang diputar atau antrian pertama
        $currentSong = Song::where('status', 'diputar')->first() 
            ?? Song::where('status', 'disetujui')->orderBy('urutan')->first();

        // Sisa detik siaran untuk timer jika ada sesi aktif
        $secondsRemaining = 0;
        if ($activeNow) {
            $now = Carbon::now();
            $endTime = Carbon::parse($activeNow->jam_selesai);
            if ($endTime->greaterThan($now)) {
                $secondsRemaining = $now->diffInSeconds($endTime);
            }
        } elseif ($displaySession) {
            // Default simulasi 18m 42s sesuai desain Stitch
            $secondsRemaining = 18 * 60 + 42;
        }

        return view('admin.schedules.index', compact(
            'schedules',
            'totalSesi',
            'totalAktif',
            'totalMenitOnAir',
            'kapasitasLagu',
            'activeNow',
            'displaySession',
            'currentSong',
            'secondsRemaining'
        ));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sesi'   => ['required', 'string', 'max:100'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'hari_aktif'  => ['required', 'array', 'min:1'],
            'hari_aktif.*'=> ['in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'is_active'   => ['boolean'],
        ]);

        $data['jam_mulai']   = substr($data['jam_mulai'], 0, 5) . ':00';
        $data['jam_selesai'] = substr($data['jam_selesai'], 0, 5) . ':00';
        $data['is_active'] = $request->boolean('is_active', true);

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')
            ->with('success', "Jadwal \"{$data['nama_sesi']}\" berhasil ditambahkan.");
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'nama_sesi'   => ['required', 'string', 'max:100'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'hari_aktif'  => ['required', 'array', 'min:1'],
            'hari_aktif.*'=> ['in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'is_active'   => ['boolean'],
        ]);

        $data['jam_mulai']   = substr($data['jam_mulai'], 0, 5) . ':00';
        $data['jam_selesai'] = substr($data['jam_selesai'], 0, 5) . ':00';
        $data['is_active'] = $request->boolean('is_active', true);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')
            ->with('success', "Jadwal \"{$schedule->nama_sesi}\" berhasil diperbarui.");
    }

    public function destroy(Schedule $schedule)
    {
        $nama = $schedule->nama_sesi;
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', "Jadwal \"{$nama}\" dihapus.");
    }

    public function toggle(Schedule $schedule)
    {
        $schedule->update(['is_active' => ! $schedule->is_active]);
        $status = $schedule->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Jadwal \"{$schedule->nama_sesi}\" {$status}.");
    }
}
