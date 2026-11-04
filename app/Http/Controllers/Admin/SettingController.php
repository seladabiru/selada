<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function index()
    {
        $defaultBlacklist = "anjing, babi, bangsat, kontol, memek, sara, teror, bunuh, porno";
        $defaultMarquee = "SELADA Radio OSIS mengudara setiap jam istirahat! Patuhi etika titip salam dan hindari lagu bermuatan SARA/kata kotor.";

        $batasRequest        = (int) Setting::get('batas_request_per_hari', 1);
        $bypassOsis          = (bool) Setting::get('bypass_osis', 1);
        $maksDurasiDetik     = (int) Setting::get('maks_durasi_detik', 600);
        $preventDuplicate    = (bool) Setting::get('prevent_duplicate', 1);
        $charLimit           = (int) Setting::get('char_limit', 255);
        $moderasiOtomatis    = (bool) Setting::get('moderasi_otomatis', 1);
        $youtubeEmbed        = (bool) Setting::get('youtube_embed_required', 1);
        $rejectAgeRestricted = (bool) Setting::get('reject_age_restricted', 1);
        $blacklistKata       = Setting::get('blacklist_kata', $defaultBlacklist) ?: $defaultBlacklist;
        $blacklistAction     = Setting::get('blacklist_action', 'auto_reject');
        $marqueeText         = Setting::get('marquee_text', $defaultMarquee) ?: $defaultMarquee;
        $errorTolerance      = (int) Setting::get('error_tolerance_detik', 3);

        // Convert blacklist string to array
        $blacklistArray = array_values(array_filter(array_map('trim', explode(',', $blacklistKata))));
        $blacklistCount = count($blacklistArray);

        return view('admin.settings.index', compact(
            'batasRequest',
            'bypassOsis',
            'maksDurasiDetik',
            'preventDuplicate',
            'charLimit',
            'moderasiOtomatis',
            'youtubeEmbed',
            'rejectAgeRestricted',
            'blacklistKata',
            'blacklistArray',
            'blacklistCount',
            'blacklistAction',
            'marqueeText',
            'errorTolerance'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'batas_request_per_hari' => ['required', 'integer', 'min:1', 'max:10'],
            'bypass_osis'            => ['nullable', 'boolean'],
            'maks_durasi_detik'      => ['required', 'integer', 'min:120', 'max:900'],
            'prevent_duplicate'      => ['nullable', 'boolean'],
            'char_limit'             => ['required', 'integer', 'min:50', 'max:500'],
            'moderasi_otomatis'      => ['nullable', 'boolean'],
            'youtube_embed_required' => ['nullable', 'boolean'],
            'reject_age_restricted'  => ['nullable', 'boolean'],
            'blacklist_kata'         => ['nullable', 'string', 'max:2000'],
            'blacklist_action'       => ['required', 'string', 'in:auto_reject,flag_review'],
            'marquee_text'           => ['required', 'string', 'max:1000'],
            'error_tolerance_detik'  => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Setting::set('batas_request_per_hari', $validated['batas_request_per_hari']);
        Setting::set('bypass_osis', $request->boolean('bypass_osis') ? '1' : '0');
        Setting::set('maks_durasi_detik', $validated['maks_durasi_detik']);
        Setting::set('prevent_duplicate', $request->boolean('prevent_duplicate') ? '1' : '0');
        Setting::set('char_limit', $validated['char_limit']);
        Setting::set('moderasi_otomatis', $request->boolean('moderasi_otomatis') ? '1' : '0');
        Setting::set('youtube_embed_required', $request->boolean('youtube_embed_required') ? '1' : '0');
        Setting::set('reject_age_restricted', $request->boolean('reject_age_restricted') ? '1' : '0');
        Setting::set('blacklist_kata', $validated['blacklist_kata'] ?? '');
        Setting::set('blacklist_action', $validated['blacklist_action']);
        Setting::set('marquee_text', $validated['marquee_text']);
        Setting::set('error_tolerance_detik', $validated['error_tolerance_detik']);

        return back()->with('success', 'Pengaturan global sistem radio berhasil disimpan & disinkronisasi live!');
    }

    public function reset()
    {
        Setting::set('batas_request_per_hari', 1);
        Setting::set('bypass_osis', '1');
        Setting::set('maks_durasi_detik', 600);
        Setting::set('prevent_duplicate', '1');
        Setting::set('char_limit', 255);
        Setting::set('moderasi_otomatis', '1');
        Setting::set('youtube_embed_required', '1');
        Setting::set('reject_age_restricted', '1');
        Setting::set('blacklist_kata', 'anjing, babi, bangsat, kontol, memek, sara, teror, bunuh, porno');
        Setting::set('blacklist_action', 'auto_reject');
        Setting::set('marquee_text', 'SELADA Radio OSIS mengudara setiap jam istirahat! Patuhi etika titip salam dan hindari lagu bermuatan SARA/kata kotor.');
        Setting::set('error_tolerance_detik', 3);

        return back()->with('success', 'Pengaturan telah di-reset ke nilai default resmi SMAN 1.');
    }

    public function downloadDb()
    {
        $databaseName = config('database.connections.mysql.database', 'selada');
        $filename = "selada_radio_backup_" . date('Ymd_His') . ".json";

        // Export core tables data to structured JSON
        $data = [
            'backup_time' => now()->toIso8601String(),
            'app_version' => 'Console v2.4 Y2K',
            'settings'    => Setting::all(),
            'users'       => DB::table('users')->select('id', 'name', 'nis', 'kelas', 'role', 'email', 'created_at')->get(),
            'schedules'   => DB::table('schedules')->get(),
            'feedbacks'   => DB::table('feedbacks')->get(),
            'songs'       => DB::table('songs')->limit(500)->get(),
        ];

        return Response::make(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function downloadLogs()
    {
        $filename = "audit_siaran_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($output, ['ID', 'Waktu Masuk', 'Pengirim (NIS)', 'Judul Lagu', 'Channel/Artis', 'Status Siaran', 'Durasi (Detik)', 'Pesan Dedikasi']);

            $songs = Song::with('user')->latest()->limit(500)->get();
            foreach ($songs as $s) {
                fputcsv($output, [
                    $s->id,
                    $s->created_at ? $s->created_at->format('Y-m-d H:i:s') : '-',
                    $s->user ? "{$s->user->name} ({$s->user->nis})" : 'Anonim',
                    $s->judul,
                    $s->channel,
                    $s->status,
                    $s->durasi,
                    $s->pesan,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }
}
