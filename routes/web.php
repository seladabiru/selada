<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\Admin\AdminSongController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ── Redirect root ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('songs.index');
})->middleware('auth');

// ── Dashboard (redirect sesuai role) ─────────────────────────────────────────
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('songs.index');
})->middleware('auth')->name('dashboard');

// ── Halaman siswa & pengguna login ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/songs',               [SongController::class, 'index'])->name('songs.index');
    Route::get('/songs/create',        [SongController::class, 'create'])->name('songs.create');
    Route::get('/top-hits',            [SongController::class, 'topHits'])->name('songs.top-hits');
    Route::post('/songs',              [SongController::class, 'store'])->name('songs.store');

    // Profil & Kritik Saran
    Route::get('/profile',             [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/feedback',   [ProfileController::class, 'storeFeedback'])->name('profile.feedback.store');
});

// ── Panel admin ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Pemutar Siaran Musik
    Route::get('player',                   [PlayerController::class, 'index'])->name('player.index');
    Route::post('player/{song}/play',      [PlayerController::class, 'play'])->name('player.play');
    Route::post('player/{song}/finish',    [PlayerController::class, 'finish'])->name('player.finish');
    Route::post('player/{song}/skip',      [PlayerController::class, 'skip'])->name('player.skip');
    Route::get('player/current',           [PlayerController::class, 'currentStatus'])->name('player.status');
    Route::post('player/manual',           [PlayerController::class, 'manualStore'])->name('player.manual');

    // Moderasi lagu
    Route::get('songs',                     [AdminSongController::class, 'index'])->name('songs.index');
    Route::post('songs/{song}/approve',     [AdminSongController::class, 'approve'])->name('songs.approve');
    Route::post('songs/{song}/reject',      [AdminSongController::class, 'reject'])->name('songs.reject');
    Route::delete('songs/{song}',           [AdminSongController::class, 'destroy'])->name('songs.destroy');
    Route::post('songs/{song}/move-up',     [AdminSongController::class, 'moveUp'])->name('songs.moveUp');
    Route::post('songs/{song}/move-down',   [AdminSongController::class, 'moveDown'])->name('songs.moveDown');
    Route::post('songs/{song}/skip',        [AdminSongController::class, 'skip'])->name('songs.skip');

    // Kritik & Saran Siswa
    Route::get('feedbacks',                 [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/export',          [AdminFeedbackController::class, 'export'])->name('feedbacks.export');
    Route::post('feedbacks/mark-all-read',   [AdminFeedbackController::class, 'markAllRead'])->name('feedbacks.markAllRead');
    Route::post('feedbacks/{feedback}/toggle', [AdminFeedbackController::class, 'toggleRead'])->name('feedbacks.toggle');
    Route::post('feedbacks/{feedback}/reply',  [AdminFeedbackController::class, 'reply'])->name('feedbacks.reply');
    Route::delete('feedbacks/{feedback}',   [AdminFeedbackController::class, 'destroy'])->name('feedbacks.destroy');

    // Jadwal
    Route::get('schedules',                 [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create',          [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('schedules',                [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::patch('schedules/{schedule}',    [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('schedules/{schedule}',   [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::post('schedules/{schedule}/toggle', [ScheduleController::class, 'toggle'])->name('schedules.toggle');

    // Pengaturan
    Route::get('settings',                  [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings',                 [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/reset',           [SettingController::class, 'reset'])->name('settings.reset');
    Route::get('settings/download-db',      [SettingController::class, 'downloadDb'])->name('settings.downloadDb');
    Route::get('settings/download-logs',    [SettingController::class, 'downloadLogs'])->name('settings.downloadLogs');

    // Laporan & Rekapitulasi Siaran
    Route::get('reports',                   [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/print',             [ReportController::class, 'print'])->name('reports.print');

    // Manajemen Pengguna (Siswa & Admin)
    Route::get('users/export',              [UserController::class, 'export'])->name('users.export');
    Route::post('users/bulk-action',        [UserController::class, 'bulkAction'])->name('users.bulkAction');
    Route::get('users/bulk',                [UserController::class, 'bulkCreate'])->name('users.bulkCreate');
    Route::post('users/bulk',               [UserController::class, 'bulkStore'])->name('users.bulkStore');
    Route::get('users/template',            [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
