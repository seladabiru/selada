<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'batas_request_per_hari' => ['required', 'integer', 'min:1', 'max:10'],
            'maks_durasi_detik'      => ['required', 'integer', 'min:60', 'max:3600'],
            'blacklist_kata'         => ['nullable', 'string', 'max:500'],
            'moderasi_otomatis'      => ['boolean'],
        ]);

        Setting::set('batas_request_per_hari', $request->batas_request_per_hari);
        Setting::set('maks_durasi_detik',      $request->maks_durasi_detik);
        Setting::set('blacklist_kata',          $request->blacklist_kata ?? '');
        Setting::set('moderasi_otomatis',       $request->boolean('moderasi_otomatis') ? '1' : '0');

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
