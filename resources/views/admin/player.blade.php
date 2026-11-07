<x-admin-layout>
{{--
    DJ Player / Studio Siaran OSIS — SELADA Broadcast Booth
    Design source: Stitch (bce7037b4ce442e0973b384afa89a90a)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen stitch-bg-surface overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="player" />

    {{-- ═══════════════════════════════════════
         MAIN AREA
    ═══════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ── TOP HEADER BAR ── --}}
        <header class="h-20 stitch-bg-surface border-b-2 stitch-border-on-surface shadow-stitch-top z-40 flex items-center justify-between px-6 flex-shrink-0">

            {{-- Left: Countdown + Mic --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-4 py-1.5 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed border-2 stitch-border-on-surface rounded-full shadow-stitch-sm">
                    <span class="material-symbols-outlined ms-sm">alarm</span>
                    <span class="ts-label-md">
                        {{ $jadwalAktifSekarang
                            ? $jadwalAktifSekarang->nama_sesi . ': Sisa ' . $jadwalAktifSekarang->sisaMenit() . ' Menit'
                            : 'Istirahat 1: Sisa 18 Menit' }}
                    </span>
                </div>
                <div class="hidden xl:flex items-center gap-1.5 px-3 py-1 stitch-bg-surface-container border stitch-border-on-surface rounded">
                    <span class="material-symbols-outlined ms-xs stitch-text-primary ms-filled">mic</span>
                    <span class="ts-label-sm stitch-text-on-surface uppercase">MIC 01 ACTIVE</span>
                </div>
            </div>

            {{-- Right: DJ Pill + Power Off --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 stitch-bg-surface-high border-2 stitch-border-on-surface px-4 py-1.5 rounded-lg shadow-stitch-sm">
                    <div class="flex flex-col text-right">
                        <span class="ts-label-sm stitch-text-on-surface uppercase font-bold">{{ Auth::user()->name }}</span>
                        <span class="ts-label-sm stitch-text-secondary font-bold">DJ OSIS Audio</span>
                    </div>
                    @php $ini = strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', Auth::user()->name ?? 'U'))), 0, 2)); @endphp
                    <div class="w-8 h-8 rounded-full border-2 stitch-border-on-surface bg-[#a3e635] text-[#416400] flex items-center justify-center font-black text-xs select-none">{{ $ini }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                            class="flex items-center justify-center p-2 rounded-lg stitch-bg-surface-container border-2 stitch-border-on-surface stitch-text-on-surface hover:stitch-bg-error-container shadow-stitch-sm transition-all"
                            onmouseover="this.style.backgroundColor='var(--color-error-container)'; this.style.color='var(--color-on-error-container)'"
                            onmouseout="this.style.backgroundColor=''; this.style.color=''">
                        <span class="material-symbols-outlined ms-sm">power_settings_new</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ── SCROLLABLE CONTENT ── --}}
        <main class="flex-1 overflow-y-auto hide-scrollbar stitch-bg-surface">
            <div class="p-6 space-y-6">

                {{-- ━━━ TOP STATION BANNER ━━━ --}}
                <div class="stitch-bg-surface-container rounded-xl shadow-md p-4 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 stitch-bg-tertiary-container px-4 py-1.5 rounded-full shadow-sm">
                            <span class="inline-block w-3 h-3 rounded-full stitch-bg-tertiary animate-live-ping flex-shrink-0"></span>
                            <span class="ts-label-lg stitch-text-on-tertiary-container uppercase tracking-wider">
                                SESI AKTIF: {{ $jadwalAktifSekarang?->nama_sesi ?: 'ISTIRAHAT SIANG' }}
                                ({{ substr($jadwalAktifSekarang?->jam_mulai ?: '12:00:00', 0, 5) }}
                                – {{ substr($jadwalAktifSekarang?->jam_selesai ?: '12:45:00', 0, 5) }} WIB)
                            </span>
                        </div>
                        <div class="flex items-center gap-2 stitch-bg-surface-high px-3 py-1 rounded">
                            <span class="material-symbols-outlined ms-sm stitch-text-secondary">hourglass_bottom</span>
                            <span class="ts-label-md stitch-text-on-surface">
                                Sisa Waktu Siaran:
                                <strong class="stitch-text-tertiary">{{ $jadwalAktifSekarang ? $jadwalAktifSekarang->sisaMenit() : '24' }} Menit</strong>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5 px-3 py-1 stitch-bg-primary-container stitch-text-on-primary-container rounded shadow-sm">
                            <span class="material-symbols-outlined ms-xs">autorenew</span>
                            <span class="ts-label-sm">AUTO-ADVANCE PLAYLIST: ON</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed rounded shadow-sm">
                            <span class="material-symbols-outlined ms-xs">security_update_warning</span>
                            <span class="ts-label-sm">ERROR FALLBACK: 3s AUTO-SKIP</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 stitch-bg-surface-highest stitch-text-on-surface rounded">
                            <span class="material-symbols-outlined ms-xs stitch-text-primary">sensors</span>
                            <span class="ts-label-sm uppercase">SPEAKER: ZONA KANTIN & KORIDOR</span>
                        </div>
                    </div>
                </div>

                {{-- ━━━ DUAL COLUMN STUDIO GRID ━━━ --}}
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

                    {{-- ═══ LEFT COLUMN (7/12) ═══ --}}
                    <div class="xl:col-span-7 space-y-6">

                        {{-- ── YOUTUBE ENGINE BROADCAST PRO V3.1 ── --}}
                        <div class="stitch-bg-surface-lowest rounded-xl shadow-lg overflow-hidden flex flex-col">

                            {{-- Purple Header Bar --}}
                            <div class="stitch-bg-secondary-container stitch-text-on-secondary-container px-4 py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined ms-sm stitch-text-primary-fixed">videocam</span>
                                    <span class="ts-label-md uppercase tracking-wider">YOUTUBE ENGINE BROADCAST PRO V3.1</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="ts-label-sm bg-white/20 stitch-text-on-secondary-container px-2 py-0.5 rounded">1080p HQ STEREO</span>
                                    <span class="inline-block w-2.5 h-2.5 rounded-full stitch-bg-primary-container animate-live-ping"></span>
                                </div>
                            </div>

                            {{-- Player / Video Area --}}
                            <div class="relative stitch-bg-inverse-surface aspect-video w-full flex flex-col justify-end overflow-hidden group" style="background-color: var(--color-inverse-surface);">

                                {{-- YouTube IFrame --}}
                                <div id="youtube-player" class="absolute inset-0 w-full h-full z-0"></div>

                                {{-- Poster Image (idle state) --}}
                                <img id="player-poster"
                                     src="{{ asset('images/stitch/youtube_stream_poster.jpg') }}"
                                     alt="Broadcast Studio"
                                     class="{{ $sedangDiputar ? 'hidden' : '' }} absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500 z-5 pointer-events-none">

                                {{-- Gradient Scrim --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-[#131b2e] via-[#131b2e]/60 to-transparent pointer-events-none z-10"></div>

                                {{-- Top Badges --}}
                                <div class="absolute top-3 left-3 z-20 flex items-center gap-2 pointer-events-none">
                                    <span class="stitch-bg-tertiary stitch-text-on-tertiary ts-label-sm px-3 py-1 rounded shadow-sm flex items-center gap-1 font-bold">
                                        <span class="material-symbols-outlined ms-xs ms-filled text-sm">radio_button_checked</span>
                                        ON-AIR NOW
                                    </span>
                                    <span class="ts-label-sm px-3 py-1 rounded backdrop-blur-sm font-mono border border-white/20" style="background:rgba(40,48,68,0.85); color: var(--color-inverse-on-surface);">
                                        YT ID: <span id="hud-yt-id">{{ $sedangDiputar?->youtube_id ?: 'd9x_Q6G1kPA' }}</span>
                                    </span>
                                </div>

                                {{-- Waveform Animation Overlay & Track Meta --}}
                                <div class="relative z-20 p-4 space-y-2.5 bg-gradient-to-t from-[#131b2e] via-[#131b2e]/90 to-transparent">
                                    <div class="flex items-end justify-between gap-3 text-white">
                                        <div class="min-w-0 flex-1">
                                            <h2 id="hud-title" class="font-display font-extrabold text-xl sm:text-2xl text-white leading-tight truncate drop-shadow-sm">
                                                {{ $sedangDiputar?->judul ?: 'Kunto Aji — Rehat' }}
                                            </h2>
                                            <p id="hud-subtitle" class="font-sans text-xs sm:text-sm text-[#e2e7ff] truncate">
                                                {{ $sedangDiputar?->channel ?: 'Kunto Aji' }} • Diputar via YouTube Music Audio Stream
                                            </p>
                                        </div>

                                        {{-- Dynamic Waveform Equalizer Visualizer --}}
                                        <div id="waveform-visualizer" class="flex items-end gap-1 h-9 px-2.5 py-1.5 bg-[#131b2e]/80 rounded-xl border border-white/10 backdrop-blur-md flex-shrink-0" title="Live Waveform Audio Spectrum">
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#a3e635] transition-all duration-150" style="height: 65%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#b2f746] transition-all duration-150" style="height: 35%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#a3e635] transition-all duration-150" style="height: 85%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#9c48ea] transition-all duration-150" style="height: 50%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#f0dbff] transition-all duration-150" style="height: 95%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#a3e635] transition-all duration-150" style="height: 70%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#9c48ea] transition-all duration-150" style="height: 40%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#b2f746] transition-all duration-150" style="height: 90%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#a3e635] transition-all duration-150" style="height: 60%;"></span>
                                            <span class="waveform-bar w-1.5 rounded-t bg-[#f0dbff] transition-all duration-150" style="height: 30%;"></span>
                                        </div>
                                    </div>

                                    {{-- Active Scrubber Slider Bar --}}
                                    <div class="space-y-1">
                                        <div class="relative w-full group py-1">
                                            <div id="scrubber-track" class="w-full h-2.5 rounded-full overflow-hidden bg-[#283044]/90 border border-white/20 relative shadow-inner cursor-pointer">
                                                <div id="hud-progress-fill" class="h-full bg-gradient-to-r from-[#8127cf] via-[#9c48ea] to-[#a3e635] rounded-full relative transition-all duration-75" style="width: 0%">
                                                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3.5 h-3.5 rounded-full bg-white border-2 border-[#131b2e] shadow-md transform scale-90 group-hover:scale-125 transition-transform"></div>
                                                </div>
                                            </div>
                                            {{-- Range Slider Input (Drag / Click to Seek) --}}
                                            <input type="range" id="scrubber-slider" min="0" max="100" value="0" step="0.1"
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30"
                                                   title="Geser slider untuk seek lagu">
                                        </div>

                                        <div class="flex justify-between items-center text-xs font-mono font-bold text-white/90">
                                            <div class="flex items-center gap-1.5">
                                                <span id="hud-curr-time">00:00</span>
                                                <span class="text-white/40">/</span>
                                                <span id="hud-total-time" class="text-white/70">{{ $sedangDiputar && $sedangDiputar->durasi > 0 ? $sedangDiputar->durasiFormatted() : '04:12' }}</span>
                                            </div>
                                            <span id="hud-remaining-time" class="text-[#ffb4a3] font-bold">
                                                {{ $sedangDiputar && $sedangDiputar->durasi > 0 ? '(-' . $sedangDiputar->durasiFormatted() . ')' : '(-04:12)' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Idle Overlay --}}
                                <div id="player-idle-overlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 text-center {{ $sedangDiputar ? 'hidden' : '' }}"
                                     style="background: rgba(40,48,68,0.92);">
                                    <div class="size-16 rounded-full border border-white/10 flex items-center justify-center mb-3 shadow-lg" style="background:var(--color-surface-container);">
                                        <span class="material-symbols-outlined ms-lg stitch-text-on-surface-variant">radio</span>
                                    </div>
                                    <h4 class="ts-headline-sm stitch-text-inverse-on-surface">Studio Standby / Belum Ada Lagu</h4>
                                    <p class="ts-body-sm stitch-text-on-surface-variant mt-1 max-w-xs">Pilih lagu di antrian untuk mulai mengudara.</p>
                                    @if ($antrian->isNotEmpty())
                                        <button type="button" onclick="playSong({{ $antrian->first()->id }})"
                                                class="mt-4 px-4 py-2 stitch-bg-primary-container stitch-text-on-primary-container ts-label-lg rounded-lg shadow-stitch-sm border-2 stitch-border-on-surface transition-all active:scale-95 flex items-center gap-1.5">
                                            <span class="material-symbols-outlined ms-sm ms-filled">play_arrow</span>
                                            Putar Lagu Pertama (#1)
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- ── DJ MASTER CONTROLS ── --}}
                            <div class="p-4 stitch-bg-surface-low flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3 flex-wrap">
                                    {{-- Jeda / Pause --}}
                                    <button type="button" id="btn-toggle-play" onclick="togglePlayPause()"
                                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg stitch-bg-primary-container stitch-text-on-primary-container shadow-md transition-all active:translate-y-0.5 hover:brightness-110">
                                        <span class="material-symbols-outlined ms-sm ms-filled" id="ctrl-play-icon">pause</span>
                                        <span class="ts-label-lg" id="ctrl-play-text">Jeda (Pause)</span>
                                    </button>

                                    {{-- Track Berikutnya --}}
                                    <button type="button" onclick="skipCurrentSong(false)"
                                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg stitch-bg-surface-highest stitch-text-on-surface shadow-md transition-all active:translate-y-0.5 hover:brightness-95">
                                        <span class="material-symbols-outlined ms-sm">skip_next</span>
                                        <span class="ts-label-lg">Track Berikutnya</span>
                                    </button>

                                    {{-- Skip / Tandai Gagal --}}
                                    <button type="button" onclick="skipCurrentSong(true)"
                                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg stitch-bg-tertiary-container stitch-text-on-tertiary-container shadow-sm transition-all active:translate-y-0.5"
                                            onmouseover="this.style.backgroundColor='var(--color-error-container)'; this.style.color='var(--color-on-error-container)'"
                                            onmouseout="this.style.backgroundColor=''; this.style.color=''">
                                        <span class="material-symbols-outlined ms-sm">report_problem</span>
                                        <span class="ts-label-lg">Skip (Tandai Gagal)</span>
                                    </button>
                                </div>

                                {{-- Master Gain --}}
                                <div class="flex items-center gap-3 stitch-bg-surface-container px-4 py-2 rounded-lg shadow-sm border border-[#131b2e]/20">
                                    <button type="button" onclick="toggleMute()" id="btn-volume-icon" class="text-[#8127cf] hover:text-[#446900] transition-colors" title="Mute / Unmute">
                                        <span class="material-symbols-outlined text-2xl" id="volume-icon">volume_up</span>
                                    </button>
                                    <div class="flex flex-col gap-1">
                                        <span class="ts-label-sm stitch-text-on-surface-variant font-bold text-[10px] uppercase">MASTER GAIN</span>
                                        <div class="flex items-center gap-2.5">
                                            <input type="range" min="0" max="100" value="82" id="volume-slider"
                                                   oninput="changeVolume(this.value)"
                                                   class="w-24 sm:w-28 h-2 bg-[#eaedff] rounded-lg cursor-pointer accent-[#8127cf] border border-[#131b2e]/40"
                                                   style="accent-color: #8127cf;">
                                            <span id="volume-label" class="ts-label-sm stitch-text-on-surface font-mono font-black min-w-[36px]">82%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── BACA DI MIKROFON SEKARANG! ── --}}
                        <div class="relative stitch-bg-surface-bright rounded-xl shadow-xl overflow-hidden p-6 space-y-4">

                            {{-- Header --}}
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="stitch-bg-tertiary stitch-text-on-tertiary px-4 py-1.5 rounded-lg shadow-md flex items-center gap-2 transform -rotate-1">
                                        <span class="material-symbols-outlined ms-sm animate-pulse ms-filled">mic</span>
                                        <span class="ts-headline-sm uppercase tracking-wide">BACA DI MIKROFON SEKARANG!</span>
                                    </div>
                                    <span class="ts-label-md stitch-bg-secondary-fixed stitch-text-on-secondary-fixed px-3 py-1 rounded">PRIORITAS TINGGI</span>
                                </div>
                                <div class="flex items-center gap-1.5 stitch-text-on-surface-variant">
                                    <span class="material-symbols-outlined ms-xs">schedule</span>
                                    <span class="ts-label-sm">
                                        Masuk: {{ $sedangDiputar ? $sedangDiputar->created_at->format('H:i') . ' WIB' : '12:04 WIB' }}
                                        @if ($sedangDiputar)
                                            ({{ $sedangDiputar->created_at->diffForHumans(null, true) }} lalu)
                                        @else
                                            (3 mnt lalu)
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Dedication Paper Card --}}
                            <div class="stitch-bg-surface-high rounded-xl p-4 space-y-3">
                                {{-- Student Info Row --}}
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div id="dedication-avatar" class="w-10 h-10 rounded-full stitch-bg-secondary-container stitch-text-on-secondary-container flex items-center justify-center ts-headline-sm shadow-sm font-bold uppercase">
                                            {{ strtoupper(substr($sedangDiputar?->user?->name ?: 'Rara Amanda', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h3 id="dedication-name" class="ts-headline-sm stitch-text-on-surface">
                                                {{ $sedangDiputar?->user?->name ?: 'Rara Amanda' }}
                                            </h3>
                                            <p class="ts-body-sm stitch-text-on-surface-variant">
                                                Kelas <span id="dedication-kelas">{{ $sedangDiputar?->user?->kelas ?: 'XII MIPA 3' }}</span>
                                                • <span class="ts-label-sm font-bold">NIS: <span id="dedication-nis">{{ $sedangDiputar?->user?->nis ?: '14144' }}</span></span>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="stitch-bg-primary-container stitch-text-on-primary-container px-3 py-0.5 rounded ts-label-sm">VERIFIED SISWA</span>
                                </div>

                                {{-- Dedication Message --}}
                                <div class="stitch-bg-surface-bright p-4 rounded-lg shadow-inner">
                                    <span class="ts-label-sm stitch-text-secondary uppercase tracking-widest block mb-1">PESAN DEDIKASI SISWA:</span>
                                    <p id="dedication-message" class="ts-body-lg stitch-text-on-surface italic leading-relaxed">
                                        "{{ $sedangDiputar && $sedangDiputar->pesan
                                            ? $sedangDiputar->pesan
                                            : 'Semangat tryout-nya ya anak-anak XII! Jangan lupa makan siang bestie, jangan pingsan pas ngerjain soal kimia! ✨🍛' }}"
                                    </p>
                                </div>

                                {{-- Song + Actions --}}
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined ms-sm stitch-text-primary ms-filled">music_note</span>
                                        <span class="ts-label-md stitch-text-on-surface">
                                            Lagu Permintaan: <strong id="dedication-song-title">{{ $sedangDiputar?->judul ?: 'Kunto Aji - Rehat' }}</strong>
                                            (<span id="dedication-song-durasi">{{ $sedangDiputar && $sedangDiputar->durasi > 0 ? $sedangDiputar->durasiFormatted() : '04:12' }}</span>)
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="markDedicationRead(this)"
                                                class="px-4 py-1.5 rounded-lg stitch-bg-secondary stitch-text-on-secondary ts-label-md shadow-sm flex items-center gap-1 active:translate-y-0.5 transition-all"
                                                style="color:white;">
                                            <span class="material-symbols-outlined ms-xs ms-filled">check_circle</span>
                                            Sudah Dibacakan
                                        </button>
                                        <button type="button" onclick="archiveDedication(this)"
                                                class="px-3 py-1.5 rounded-lg stitch-bg-surface-container stitch-text-on-surface ts-label-md shadow-sm hover:brightness-95 transition-all">
                                            Arsipkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /LEFT COLUMN --}}

                    {{-- ═══ RIGHT COLUMN (5/12) ═══ --}}
                    <div class="xl:col-span-5 space-y-6">

                        {{-- ── ANTRIAN PUTAR SIARAN ── --}}
                        <div class="stitch-bg-surface-lowest rounded-xl shadow-lg overflow-hidden flex flex-col">

                            {{-- Queue Header --}}
                            <div class="stitch-bg-surface-high px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined ms-sm stitch-text-primary">queue_music</span>
                                    <div>
                                        <h3 class="ts-headline-sm stitch-text-on-surface leading-tight">Antrian Putar Siaran</h3>
                                        <span class="ts-label-sm stitch-text-on-surface-variant">{{ $antrian->count() }} Lagu Siap Putar Berurutan</span>
                                    </div>
                                </div>
                                <span class="ts-label-sm stitch-bg-primary stitch-text-on-primary px-3 py-0.5 rounded-full" style="background:var(--color-primary); color:white;">LIVE POLLING</span>
                            </div>

                            {{-- Queue List --}}
                            <div id="queue-container" class="p-4 space-y-3 max-h-[520px] overflow-y-auto hide-scrollbar">
                                @forelse ($antrian as $index => $song)
                                    <div id="queue-item-{{ $song->id }}"
                                         class="p-4 {{ $index === 0 ? 'stitch-bg-surface-container' : 'stitch-bg-surface-low' }} rounded-xl shadow-sm space-y-2 transition-all hover:stitch-bg-surface-high">

                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                {{-- Rank Badge --}}
                                                <span class="w-7 h-7 rounded-lg ts-label-lg flex items-center justify-center flex-shrink-0 font-bold {{ $index === 0 ? 'stitch-bg-primary-container stitch-text-on-primary-container' : 'stitch-bg-surface-highest stitch-text-on-surface' }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                                <div class="truncate min-w-0">
                                                    <h4 class="ts-headline-sm stitch-text-on-surface truncate" title="{{ $song->judul }}">{{ $song->judul }}</h4>
                                                    <p class="ts-body-sm stitch-text-on-surface-variant">
                                                        {{ $song->user?->name ?: 'Siswa' }}
                                                        • <span class="stitch-text-secondary font-bold">{{ $song->user?->kelas ?: '—' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dedication Snippet --}}
                                        @if ($song->pesan)
                                            <div class="stitch-bg-surface-bright p-2 rounded ts-body-sm stitch-text-on-surface-variant italic flex items-center gap-1">
                                                <span class="material-symbols-outlined ms-xs stitch-text-secondary">chat_bubble</span>
                                                <span class="truncate">Salam: "{{ $song->pesan }}"</span>
                                            </div>
                                        @endif

                                        {{-- Bottom: Estimasi + Actions --}}
                                        <div class="flex items-center justify-between pt-1">
                                            <span class="ts-label-sm stitch-text-outline">
                                                Estimasi Putar: {{ now()->addMinutes(($index + 1) * 4)->format('H:i') }} WIB
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                @if ($index === 0)
                                                    <button type="button" onclick="playSong({{ $song->id }})"
                                                            class="px-2.5 py-1 rounded ts-label-sm flex items-center gap-1 shadow-sm active:translate-y-0.5 transition-all"
                                                            style="background:var(--color-primary); color:white;">
                                                        <span class="material-symbols-outlined ms-xs ms-filled">play_arrow</span>
                                                        Putar Sekarang
                                                    </button>
                                                @endif
                                                @if ($index > 0)
                                                    <form method="POST" action="{{ route('admin.songs.moveUp', $song) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1 stitch-bg-surface-highest stitch-text-on-surface rounded hover:brightness-95" title="Naikkan">
                                                            <span class="material-symbols-outlined ms-xs">arrow_upward</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($index < $antrian->count() - 1)
                                                    <form method="POST" action="{{ route('admin.songs.moveDown', $song) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1 stitch-bg-surface-highest stitch-text-on-surface rounded hover:brightness-95" title="Turunkan">
                                                            <span class="material-symbols-outlined ms-xs">arrow_downward</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-10 text-center stitch-text-on-surface-variant ts-body-sm">
                                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">queue_music</span>
                                        Tidak ada lagu disetujui di antrian siaran saat ini.
                                    </div>
                                @endforelse
                            </div>

                            {{-- Add Track Footer --}}
                            <div class="p-4 stitch-bg-surface-container flex items-center justify-between">
                                <span class="ts-label-sm stitch-text-on-surface-variant">Tambah Track Manual / Request Khusus Guru?</span>
                                <button type="button"
                                        id="btn-open-manual"
                                        class="px-4 py-1.5 rounded-lg stitch-bg-surface-highest stitch-text-on-surface ts-label-md flex items-center gap-1 shadow-sm transition-all hover:brightness-95 cursor-pointer border-2 stitch-border-on-surface">
                                    <span class="material-symbols-outlined ms-xs">add</span>
                                    Input Manual
                                </button>
                            </div>
                        </div>

                        {{-- ── QUICK STATS GRID ── --}}
                        <div class="grid grid-cols-3 gap-3">
                            {{-- Total Putar --}}
                            <div class="stitch-bg-surface-container rounded-xl p-4 flex flex-col justify-between shadow-sm">
                                <span class="material-symbols-outlined stitch-text-primary mb-1">library_music</span>
                                <div>
                                    <span class="ts-headline-lg stitch-text-on-surface leading-none block" style="font-weight:800;">{{ $totalPutarHariIni }} Lagu</span>
                                    <span class="ts-label-sm stitch-text-on-surface-variant">Total Putar Hari Ini</span>
                                </div>
                            </div>
                            {{-- Durasi --}}
                            <div class="stitch-bg-surface-container rounded-xl p-4 flex flex-col justify-between shadow-sm">
                                <span class="material-symbols-outlined stitch-text-secondary mb-1">timer</span>
                                <div>
                                    <span class="ts-headline-lg stitch-text-on-surface leading-none block" style="font-weight:800;">{{ $totalDurasiMnt }} Mnt</span>
                                    <span class="ts-label-sm stitch-text-on-surface-variant">Total Durasi On-Air</span>
                                </div>
                            </div>
                            {{-- Sisa --}}
                            <div class="stitch-bg-surface-container rounded-xl p-4 flex flex-col justify-between shadow-sm">
                                <span class="material-symbols-outlined stitch-text-tertiary mb-1">pending_actions</span>
                                <div>
                                    <span class="ts-headline-lg stitch-text-on-surface leading-none block" style="font-weight:800;">{{ $sisaAntrianSesi }} Lagu</span>
                                    <span class="ts-label-sm stitch-text-on-surface-variant">Sisa Antrian Sesi Ini</span>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /RIGHT COLUMN --}}

                </div>{{-- /DUAL COLUMN --}}

            </div>{{-- /p-6 --}}
        </main>

    </div>{{-- /MAIN AREA --}}

</div>{{-- /full screen layout --}}

{{-- ═══════════════════════════════════════
     SCRIPTS — YouTube IFrame API + Studio Engine
═══════════════════════════════════════ --}}
<script>
    let player = null;
    let isPlayerReady = false;
    let currentSongId = {{ $sedangDiputar?->id ?: 'null' }};
    let currentYoutubeId = '{{ $sedangDiputar?->youtube_id ?: '' }}';
    const csrfToken = '{{ csrf_token() }}';

    // ── Waveform Spectrum Visualizer Animation ──
    let waveformInterval = null;
    function startWaveform() {
        if (waveformInterval) clearInterval(waveformInterval);
        const bars = document.querySelectorAll('#waveform-visualizer .waveform-bar');
        if (!bars.length) return;
        const baseHeights = [65, 40, 85, 55, 95, 75, 45, 90, 60, 35];
        waveformInterval = setInterval(() => {
            bars.forEach((bar, idx) => {
                const variance = Math.floor(Math.random() * 40) - 20;
                const h = Math.max(15, Math.min(100, baseHeights[idx % baseHeights.length] + variance));
                bar.style.height = h + '%';
            });
        }, 130);
    }

    function pauseWaveform() {
        if (waveformInterval) clearInterval(waveformInterval);
        const bars = document.querySelectorAll('#waveform-visualizer .waveform-bar');
        bars.forEach(bar => {
            bar.style.height = '15%';
        });
    }

    // Load YouTube IFrame API
    const ytScript = document.createElement('script');
    ytScript.src = 'https://www.youtube.com/iframe_api';
    document.head.appendChild(ytScript);

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            height: '100%', width: '100%',
            videoId: currentYoutubeId,
            playerVars: { autoplay: currentYoutubeId ? 1 : 0, controls: 1, rel: 0, modestbranding: 1 },
            events: {
                onReady:       onPlayerReady,
                onStateChange: onPlayerStateChange,
                onError:       onPlayerError
            }
        });
    }

    function onPlayerReady(event) {
        isPlayerReady = true;
        
        if (currentYoutubeId) {
            event.target.playVideo();
            updatePlayButton(true);
            hidePoster();
            startWaveform();
        }
        
        // Update progress lebih sering
        setInterval(() => {
            updateProgress();
        }, 500); // 2x per second untuk smooth progress
    }

    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            updatePlayButton(true);
            hidePoster();
            startWaveform();
        } else if (event.data === YT.PlayerState.PAUSED) {
            updatePlayButton(false);
            pauseWaveform();
        } else if (event.data === YT.PlayerState.ENDED) {
            pauseWaveform();
            finishCurrentSong();
        }
    }

    function onPlayerError() {
        setTimeout(() => skipCurrentSong(true, 'Video YouTube dibatasi / tidak bisa di-embed'), 3000);
    }

    function hidePoster() {
        document.getElementById('player-poster')?.classList.add('hidden');
    }

    function togglePlayPause() {
        if (!player || !isPlayerReady) return;
        if (player.getPlayerState() === YT.PlayerState.PLAYING) {
            player.pauseVideo();
            updatePlayButton(false);
            pauseWaveform();
        } else {
            player.playVideo();
            updatePlayButton(true);
            startWaveform();
        }
    }

    function updatePlayButton(isPlaying) {
        const icon = document.getElementById('ctrl-play-icon');
        const text = document.getElementById('ctrl-play-text');
        if (isPlaying) {
            if (icon) icon.textContent = 'pause';
            if (text) text.textContent = 'Jeda (Pause)';
        } else {
            if (icon) icon.textContent = 'play_arrow';
            if (text) text.textContent = 'Lanjut Putar';
        }
    }

    // ── Master Gain / Volume Slider Control ──
    let lastVolume = 82;
    function changeVolume(val) {
        val = parseInt(val, 10);
        if (player && isPlayerReady && typeof player.setVolume === 'function') {
            player.setVolume(val);
        }
        const label = document.getElementById('volume-label');
        const icon  = document.getElementById('volume-icon');
        if (label) label.textContent = val + '%';
        if (icon) {
            if (val === 0) icon.textContent = 'volume_off';
            else if (val < 50) icon.textContent = 'volume_down';
            else icon.textContent = 'volume_up';
        }
    }

    function toggleMute() {
        const slider = document.getElementById('volume-slider');
        if (!slider) return;
        if (slider.value > 0) {
            lastVolume = slider.value;
            slider.value = 0;
            changeVolume(0);
        } else {
            slider.value = lastVolume || 82;
            changeVolume(slider.value);
        }
    }

    // ── Active Scrubber Slider & Progress Engine ──
    let isSeeking = false;
    const scrubberSlider = document.getElementById('scrubber-slider');
    const scrubberTrack  = document.getElementById('scrubber-track');

    if (scrubberSlider) {
        scrubberSlider.addEventListener('input', function() {
            isSeeking = true;
            const pct = parseFloat(this.value);
            const fill = document.getElementById('hud-progress-fill');
            if (fill) fill.style.width = pct + '%';
            if (player && isPlayerReady && typeof player.getDuration === 'function') {
                const dur = player.getDuration() || 0;
                const targetTime = Math.floor((pct / 100) * dur);
                const curEl = document.getElementById('hud-curr-time');
                const remEl = document.getElementById('hud-remaining-time');
                if (curEl) curEl.textContent = formatTime(targetTime);
                if (remEl) remEl.textContent = '(-' + formatTime(Math.max(0, dur - targetTime)) + ')';
            }
        });

        scrubberSlider.addEventListener('change', function() {
            if (player && isPlayerReady && typeof player.getDuration === 'function') {
                const dur = player.getDuration() || 0;
                const targetTime = (parseFloat(this.value) / 100) * dur;
                player.seekTo(targetTime, true);
            }
            setTimeout(() => { isSeeking = false; }, 300);
        });
    }

    if (scrubberTrack) {
        scrubberTrack.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const pct = Math.max(0, Math.min(100, (clickX / rect.width) * 100));
            if (scrubberSlider) scrubberSlider.value = pct;
            const fill = document.getElementById('hud-progress-fill');
            if (fill) fill.style.width = pct + '%';
            if (player && isPlayerReady && typeof player.getDuration === 'function') {
                const dur = player.getDuration() || 0;
                const targetTime = (pct / 100) * dur;
                player.seekTo(targetTime, true);
            }
        });
    }

    function updateProgress() {
        if (isSeeking) return;
        if (!player || !isPlayerReady || typeof player.getCurrentTime !== 'function') return;
        const cur = Math.floor(player.getCurrentTime());
        const dur = Math.floor(player.getDuration()) || 1;
        const pct = Math.min(100, (cur / dur) * 100);

        const fill   = document.getElementById('hud-progress-fill');
        const slider = document.getElementById('scrubber-slider');
        const curEl  = document.getElementById('hud-curr-time');
        const totEl  = document.getElementById('hud-total-time');
        const remEl  = document.getElementById('hud-remaining-time');

        if (fill)   fill.style.width = pct + '%';
        if (slider) slider.value = pct;
        if (curEl)  curEl.textContent = formatTime(cur);
        if (totEl && dur > 1) totEl.textContent = formatTime(dur);
        if (remEl && dur > 1) {
            const rem = Math.max(0, dur - cur);
            remEl.textContent = '(-' + formatTime(rem) + ')';
        }
    }

    function formatTime(sec) {
        sec = Math.max(0, Math.floor(sec || 0));
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }

    // ── Playlist Actions (Play, Finish, Skip) ──
    function playSong(songId) {
        fetch(`/admin/player/${songId}/play`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.song) {
                currentSongId = data.song.id;
                currentYoutubeId = data.song.youtube_id;
                updateUI(data.song);
                if (player && isPlayerReady) player.loadVideoById(currentYoutubeId);
                document.getElementById(`queue-item-${songId}`)?.remove();
            }
        })
        .catch(err => console.error('Gagal play:', err));
    }

    function finishCurrentSong() {
        if (!currentSongId) return;
        fetch(`/admin/player/${currentSongId}/finish`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.has_next && data.song) {
                currentSongId = data.song.id;
                currentYoutubeId = data.song.youtube_id;
                updateUI(data.song);
                if (player && isPlayerReady) player.loadVideoById(currentYoutubeId);
                document.getElementById(`queue-item-${data.song.id}`)?.remove();
            } else {
                currentSongId = null;
                currentYoutubeId = '';
                showIdle();
                if (player && isPlayerReady) player.stopVideo();
            }
        })
        .catch(err => console.error('Gagal finish:', err));
    }

    function skipCurrentSong(isError = false, alasan = '') {
        if (!currentSongId) {
            document.querySelector('#queue-container button')?.click();
            return;
        }
        fetch(`/admin/player/${currentSongId}/skip`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ error: isError, alasan: alasan || (isError ? 'Video error embed' : 'Di-skip DJ') })
        })
        .then(r => r.json())
        .then(data => {
            if (data.has_next && data.song) {
                currentSongId = data.song.id;
                currentYoutubeId = data.song.youtube_id;
                updateUI(data.song);
                if (player && isPlayerReady) player.loadVideoById(currentYoutubeId);
                document.getElementById(`queue-item-${data.song.id}`)?.remove();
            } else {
                currentSongId = null;
                currentYoutubeId = '';
                showIdle();
                if (player && isPlayerReady) player.stopVideo();
            }
        })
        .catch(err => console.error('Gagal skip:', err));
    }

    function updateUI(song) {
        document.getElementById('player-idle-overlay')?.classList.add('hidden');
        hidePoster();

        // 1. Track Meta in Overlay
        const title = document.getElementById('hud-title');
        const sub   = document.getElementById('hud-subtitle');
        const ytId  = document.getElementById('hud-yt-id');
        const totEl = document.getElementById('hud-total-time');
        const remEl = document.getElementById('hud-remaining-time');

        if (title) title.textContent = song.judul || 'Lagu Tanpa Judul';
        if (sub)   sub.textContent   = `${song.channel || '—'} • Request oleh ${song.user || 'Siswa'}`;
        if (ytId)  ytId.textContent  = song.youtube_id || '—';
        if (totEl && song.durasi_formatted) totEl.textContent = song.durasi_formatted;
        if (remEl && song.durasi_formatted) remEl.textContent = '(-' + song.durasi_formatted + ')';

        // 2. Dedication Card (BACA DI MIKROFON SEKARANG!)
        const dedAvatar = document.getElementById('dedication-avatar');
        const dedName   = document.getElementById('dedication-name');
        const dedKelas  = document.getElementById('dedication-kelas');
        const dedNis    = document.getElementById('dedication-nis');
        const dedMsg    = document.getElementById('dedication-message');
        const dedSong   = document.getElementById('dedication-song-title');
        const dedDurasi = document.getElementById('dedication-song-durasi');

        if (dedAvatar && song.user) dedAvatar.textContent = song.user.substring(0, 2).toUpperCase();
        if (dedName)   dedName.textContent = song.user || 'Siswa';
        if (dedKelas)  dedKelas.textContent = song.kelas || 'Siswa';
        if (dedNis)    dedNis.textContent = song.nis || '—';
        if (dedMsg)    dedMsg.textContent = `"${song.pesan || 'Tidak ada pesan khusus.'}"`;
        if (dedSong)   dedSong.textContent = song.judul || '—';
        if (dedDurasi) dedDurasi.textContent = song.durasi_formatted || (song.durasi ? formatTime(song.durasi) : '—');

        // Start waveform animation
        startWaveform();
    }

    function showIdle() {
        document.getElementById('player-idle-overlay')?.classList.remove('hidden');
        document.getElementById('player-poster')?.classList.remove('hidden');
        pauseWaveform();
        const fill = document.getElementById('hud-progress-fill');
        const slider = document.getElementById('scrubber-slider');
        const curEl = document.getElementById('hud-curr-time');
        if (fill) fill.style.width = '0%';
        if (slider) slider.value = 0;
        if (curEl) curEl.textContent = '00:00';
    }

    function markDedicationRead(btn) {
        btn.style.background = 'var(--color-primary)';
        btn.innerHTML = '<span class="material-symbols-outlined ms-xs ms-filled">check_circle</span> Telah Dibacakan!';
        btn.disabled = true;
    }

    function archiveDedication(btn) {
        btn.closest('.stitch-bg-surface-high, .space-y-3')?.style && (btn.closest('.space-y-3').style.opacity = '0.45');
        btn.textContent = 'Diarsipkan';
        btn.disabled = true;
    }

    // ── MODAL INPUT MANUAL LAGU ──
    const manualModal = document.createElement('div');
    manualModal.id = 'modal-manual-input';
    manualModal.className = 'fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden';
    manualModal.innerHTML = `
    <div class="stitch-bg-surface-lowest rounded-2xl w-full max-w-lg p-6 shadow-2xl flex flex-col gap-4 transform scale-95 transition-transform border-2 stitch-border-on-surface shadow-stitch-lg" id="manual-modal-content">
        {{-- Header --}}
        <div class="flex items-start justify-between pb-2 border-b border-on-surface/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg stitch-bg-secondary stitch-text-on-secondary flex items-center justify-center shadow-sm" style="background:var(--color-secondary); color:white;">
                    <span class="material-symbols-outlined ms-sm">edit_note</span>
                </div>
                <div class="flex flex-col">
                    <span class="ts-headline-sm stitch-text-on-surface font-bold">Input Manual Lagu</span>
                    <span class="ts-body-sm stitch-text-on-surface-variant">Tambahkan lagu langsung ke antrian siaran (bypass moderasi)</span>
                </div>
            </div>
            <button class="p-1 rounded-lg stitch-text-on-surface-variant hover:stitch-text-on-surface hover:stitch-bg-surface-container cursor-pointer" id="btn-close-manual" type="button">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Form --}}
        <form id="form-manual-input" class="flex flex-col gap-4">
            {{-- YouTube URL --}}
            <div class="flex flex-col gap-1.5">
                <label class="ts-label-md stitch-text-on-surface font-bold">URL atau ID YouTube <span class="stitch-text-tertiary">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 stitch-text-on-surface-variant text-xl">link</span>
                    <input type="text"
                           name="youtube_url"
                           id="manual-youtube-url"
                           required
                           placeholder="Contoh: https://youtube.com/watch?v=... atau d9x_Q6G1kPA"
                           class="w-full stitch-bg-surface-low stitch-text-on-surface ts-body-md pl-11 pr-12 py-3 rounded-lg focus:stitch-bg-surface-lowest focus:outline-none transition-all border-2 stitch-border-on-surface">
                    <span id="manual-url-spinner" class="material-symbols-outlined absolute right-3 text-xl stitch-text-on-surface-variant animate-spin hidden">sync</span>
                </div>
            </div>

            {{-- Judul Lagu --}}
            <div class="flex flex-col gap-1.5">
                <label class="ts-label-md stitch-text-on-surface font-bold">Judul Lagu <span class="stitch-text-outline font-normal">(opsional — auto-detect jika kosong)</span></label>
                <input type="text"
                       name="judul"
                       id="manual-judul"
                       placeholder="Otomatis terdeteksi dari YouTube..."
                       class="w-full stitch-bg-surface-low stitch-text-on-surface ts-body-md px-4 py-3 rounded-lg focus:stitch-bg-surface-lowest focus:outline-none transition-all border-2 stitch-border-on-surface">
                <span id="manual-autodetect-badge" class="hidden ts-label-sm stitch-text-secondary font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined ms-xs ms-filled text-sm">auto_awesome</span>
                    Judul terdeteksi otomatis
                </span>
            </div>

            {{-- Pesan Dedikasi --}}
            <div class="flex flex-col gap-1.5">
                <label class="ts-label-md stitch-text-on-surface font-bold">Pesan / Salam Dedikasi <span class="stitch-text-outline font-normal">(opsional)</span></label>
                <textarea name="pesan"
                          id="manual-pesan"
                          rows="2"
                          placeholder="Contoh: Lagu request khusus dari Pak Guru untuk semangat ujian!"
                          class="w-full stitch-bg-surface-low stitch-text-on-surface ts-body-md px-4 py-3 rounded-lg focus:stitch-bg-surface-lowest focus:outline-none transition-all border-2 stitch-border-on-surface resize-none"></textarea>
            </div>

            {{-- Info Note --}}
            <div class="stitch-bg-surface-container p-3 rounded-lg flex items-start gap-2 border border-on-surface/10">
                <span class="material-symbols-outlined stitch-text-secondary text-lg shrink-0 mt-0.5">info</span>
                <p class="ts-body-sm stitch-text-on-surface-variant">Lagu yang diinput manual akan langsung berstatus <strong class="stitch-text-on-surface">Disetujui</strong> dan masuk ke antrian siaran tanpa moderasi. Lagu yang sedang diputar tidak akan terganggu.</p>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-on-surface/10">
                <button type="button"
                        class="px-4 py-2.5 stitch-bg-surface-high stitch-text-on-surface ts-label-md rounded-lg hover:brightness-95 transition-colors cursor-pointer border border-on-surface/10"
                        id="btn-cancel-manual">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 stitch-bg-primary-container stitch-text-on-primary-container ts-label-md rounded-lg shadow-stitch-sm hover:brightness-95 transition-all flex items-center gap-1.5 cursor-pointer font-bold border-2 stitch-border-on-surface"
                        id="btn-confirm-manual">
                    <span class="material-symbols-outlined text-lg">add_circle</span>
                    <span>Tambah ke Antrian</span>
                </button>
            </div>
        </form>

        {{-- Alert Container --}}
        <div id="manual-alert" class="hidden p-3 rounded-lg ts-body-sm font-bold items-center gap-2"></div>
    </div>`;
    document.body.appendChild(manualModal);

    // Modal elements
    const manualModalContent = document.getElementById('manual-modal-content');
    const manualForm = document.getElementById('form-manual-input');
    const manualAlert = document.getElementById('manual-alert');
    const manualUrlInput = document.getElementById('manual-youtube-url');
    const manualJudulInput = document.getElementById('manual-judul');
    const manualUrlSpinner = document.getElementById('manual-url-spinner');
    const manualAutoDetectBadge = document.getElementById('manual-autodetect-badge');

    // ── Auto-detect judul dari YouTube oEmbed saat user ketik URL ──
    let autoDetectTimer = null;
    manualUrlInput.addEventListener('input', function() {
        clearTimeout(autoDetectTimer);
        const url = this.value.trim();
        if (!url) {
            manualUrlSpinner.classList.add('hidden');
            manualAutoDetectBadge.classList.add('hidden');
            return;
        }

        // Extract video ID untuk validasi
        let videoId = null;
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/,
            /^([a-zA-Z0-9_-]{11})$/
        ];
        for (const p of patterns) {
            const m = url.match(p);
            if (m) { videoId = m[1]; break; }
        }

        if (!videoId) {
            manualUrlSpinner.classList.add('hidden');
            manualAutoDetectBadge.classList.add('hidden');
            return;
        }

        // Hanya auto-detect jika judul masih kosong (user belum isi manual)
        if (manualJudulInput.value.trim() && manualJudulInput.value.trim() !== manualJudulInput.dataset.autoDetected) {
            manualAutoDetectBadge.classList.add('hidden');
            return;
        }

        autoDetectTimer = setTimeout(() => {
            manualUrlSpinner.classList.remove('hidden');
            manualAutoDetectBadge.classList.add('hidden');

            // Pakai YouTube oEmbed API (gratis, tanpa API key)
            fetch(`https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=${videoId}&format=json`)
                .then(r => {
                    if (!r.ok) throw new Error('oEmbed failed');
                    return r.json();
                })
                .then(data => {
                    manualUrlSpinner.classList.add('hidden');
                    if (data.title) {
                        // Hanya set jika user belum mengetik manual
                        if (!manualJudulInput.value.trim() || manualJudulInput.value.trim() === manualJudulInput.dataset.autoDetected) {
                            manualJudulInput.value = data.title;
                            manualJudulInput.dataset.autoDetected = data.title;
                            manualAutoDetectBadge.classList.remove('hidden');
                            manualAutoDetectBadge.classList.add('flex');
                        }
                    }
                })
                .catch(() => {
                    manualUrlSpinner.classList.add('hidden');
                    manualAutoDetectBadge.classList.add('hidden');
                });
        }, 600);
    });

    // Jika user ketik judul manual, hapus auto-detect badge
    manualJudulInput.addEventListener('input', function() {
        if (this.value.trim() !== this.dataset.autoDetected) {
            manualAutoDetectBadge.classList.add('hidden');
            manualAutoDetectBadge.classList.remove('flex');
        }
    });

    // Open modal
    document.getElementById('btn-open-manual')?.addEventListener('click', function() {
        manualModal.classList.remove('hidden');
        setTimeout(() => {
            manualModalContent.classList.remove('scale-95');
            manualModalContent.classList.add('scale-100');
        }, 10);
    });

    // Close modal
    function closeManualModal() {
        manualModalContent.classList.add('scale-95');
        manualModalContent.classList.remove('scale-100');
        setTimeout(() => {
            manualModal.classList.add('hidden');
            manualForm.reset();
            manualAlert.classList.add('hidden');
            manualAlert.className = 'hidden p-3 rounded-lg ts-body-sm font-bold items-center gap-2';
            manualUrlSpinner.classList.add('hidden');
            manualAutoDetectBadge.classList.add('hidden');
            manualAutoDetectBadge.classList.remove('flex');
            delete manualJudulInput.dataset.autoDetected;
        }, 150);
    }

    document.getElementById('btn-close-manual')?.addEventListener('click', closeManualModal);
    document.getElementById('btn-cancel-manual')?.addEventListener('click', closeManualModal);
    manualModal.addEventListener('click', function(e) {
        if (e.target === manualModal) closeManualModal();
    });

    // Show alert in modal
    function showManualAlert(message, type) {
        manualAlert.classList.remove('hidden');
        manualAlert.style.display = 'flex';
        if (type === 'error') {
            manualAlert.className = 'p-3 rounded-lg ts-body-sm font-bold flex items-center gap-2 stitch-bg-error-container stitch-text-on-error-container border border-on-surface/10';
            manualAlert.innerHTML = '<span class="material-symbols-outlined text-lg">error</span>' + message;
        } else {
            manualAlert.className = 'p-3 rounded-lg ts-body-sm font-bold flex items-center gap-2 bg-primary-container text-on-primary-container border border-on-surface/10';
            manualAlert.innerHTML = '<span class="material-symbols-outlined text-lg ms-filled">check_circle</span>' + message;
        }
    }

    // ── Submit form: Tambah lagu ke antrian TANPA refresh page ──
    manualForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btnConfirm = document.getElementById('btn-confirm-manual');
        const originalBtnHTML = btnConfirm.innerHTML;
        btnConfirm.disabled = true;
        btnConfirm.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">sync</span><span>Menambahkan...</span>';

        const formData = new FormData(manualForm);
        const data = {};
        formData.forEach((val, key) => { data[key] = val; });

        fetch('{{ route("admin.player.manual") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = originalBtnHTML;

            if (data.success) {
                showManualAlert(data.message, 'success');

                // ── Tambahkan lagu baru ke queue container TANPA refresh ──
                const queueContainer = document.getElementById('queue-container');
                if (queueContainer && data.song) {
                    const song = data.song;
                    const queueIndex = queueContainer.querySelectorAll('[id^="queue-item-"]').length;

                    // Buat card HTML untuk lagu baru
                    const newCard = document.createElement('div');
                    newCard.id = `queue-item-${song.id}`;
                    newCard.className = 'p-4 stitch-bg-surface-low rounded-xl shadow-sm space-y-2 transition-all hover:stitch-bg-surface-high';

                    const pesanHtml = song.pesan
                        ? `<div class="stitch-bg-surface-bright p-2 rounded ts-body-sm stitch-text-on-surface-variant italic flex items-center gap-1">
                             <span class="material-symbols-outlined ms-xs stitch-text-secondary">chat_bubble</span>
                             <span class="truncate">Salam: "${song.pesan}"</span>
                           </div>`
                        : '';

                    const durasiFmt = song.durasi_formatted || (song.durasi > 0 ? formatTime(song.durasi) : '—');

                    newCard.innerHTML = `
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-7 h-7 rounded-lg ts-label-lg flex items-center justify-center flex-shrink-0 font-bold stitch-bg-surface-highest stitch-text-on-surface">
                                    #${queueIndex + 1}
                                </span>
                                <div class="truncate min-w-0">
                                    <h4 class="ts-headline-sm stitch-text-on-surface truncate" title="${song.judul}">${song.judul}</h4>
                                    <p class="ts-body-sm stitch-text-on-surface-variant">
                                        ${song.user || 'DJ'} • <span class="stitch-text-secondary font-bold">${song.kelas || 'Manual'}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed px-2 py-0.5 rounded-full flex-shrink-0">
                                <span class="material-symbols-outlined ms-xs ms-filled">thumb_up</span>
                                <span class="ts-label-sm font-bold">0 Votes</span>
                            </div>
                        </div>
                        ${pesanHtml}
                        <div class="flex items-center justify-between pt-1">
                            <span class="ts-label-sm stitch-text-outline">
                                Estimasi Putar: ${new Date(Date.now() + (queueIndex + 1) * 4 * 60000).toTimeString().substring(0, 5)} WIB
                            </span>
                            <div class="flex items-center gap-1.5">
                                <form method="POST" action="/admin/songs/${song.id}/move-up" class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="p-1 stitch-bg-surface-highest stitch-text-on-surface rounded hover:brightness-95" title="Naikkan">
                                        <span class="material-symbols-outlined ms-xs">arrow_upward</span>
                                    </button>
                                </form>
                                <form method="POST" action="/admin/songs/${song.id}/move-down" class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="p-1 stitch-bg-surface-highest stitch-text-on-surface rounded hover:brightness-95" title="Turunkan">
                                        <span class="material-symbols-outlined ms-xs">arrow_downward</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;

                    // Jika queue container ada pesan "Tidak ada lagu", hapus
                    const emptyMsg = queueContainer.querySelector('.text-center');
                    if (emptyMsg && emptyMsg.textContent.includes('Tidak ada lagu')) {
                        emptyMsg.remove();
                    }

                    queueContainer.appendChild(newCard);

                    // Animasi fade-in untuk card baru
                    newCard.style.opacity = '0';
                    newCard.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        newCard.style.transition = 'opacity 0.4s, transform 0.4s';
                        newCard.style.opacity = '1';
                        newCard.style.transform = 'translateY(0)';
                    }, 50);
                }

                // Tutup modal setelah 1.2 detik (TANPA reload page)
                setTimeout(() => {
                    closeManualModal();
                }, 1200);
            } else {
                showManualAlert(data.message || 'Gagal menambahkan lagu.', 'error');
            }
        })
        .catch(err => {
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = originalBtnHTML;
            showManualAlert('Terjadi kesalahan. Coba lagi.', 'error');
            console.error('Manual input error:', err);
        });
    });
</script>
</x-admin-layout>
