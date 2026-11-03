<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('jam_mulai')->get();
        return view('admin.schedules.index', compact('schedules'));
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

        $data['jam_mulai']   .= ':00';
        $data['jam_selesai'] .= ':00';
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
        ]);

        $data['jam_mulai']   .= ':00';
        $data['jam_selesai'] .= ':00';
        $data['is_active'] = $request->boolean('is_active', true);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')
            ->with('success', "Jadwal \"{$schedule->nama_sesi}\" diperbarui.");
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
