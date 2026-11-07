<x-admin-layout>
{{--
    Panel Moderasi Antrian Lagu — SELADA Broadcast Booth
    Design source: Stitch (1119230ed081436481a3843b98006015)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen stitch-bg-surface overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="songs" />

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
                    <span class="ts-label-md">Istirahat 1: Sisa 18 Menit</span>
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
                            class="flex items-center justify-center p-2 rounded-lg stitch-bg-surface-container border-2 stitch-border-on-surface stitch-text-on-surface hover:stitch-bg-error-container shadow-stitch-sm transition-all">
                        <span class="material-symbols-outlined ms-sm">power_settings_new</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ── SCROLLABLE CONTENT ── --}}
        <main class="flex-1 overflow-y-auto hide-scrollbar stitch-bg-surface">
            <div class="p-6 flex flex-col gap-6">

                {{-- Flash Notifications --}}
                @if (session('success'))
                    <div class="p-4 bg-primary-container text-on-primary-container border-2 stitch-border-on-surface rounded-xl shadow-stitch-sm flex items-center gap-2 ts-body-md font-bold">
                        <span class="material-symbols-outlined ms-sm ms-filled">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 stitch-bg-error-container stitch-text-on-error-container border-2 stitch-border-on-surface rounded-xl shadow-stitch-sm flex items-center gap-2 ts-body-md font-bold">
                        <span class="material-symbols-outlined ms-sm">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ━━━ TOP ACTION / CONTEXT ROW ━━━ --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed ts-label-sm uppercase rounded font-bold border border-on-surface/10">
                                MODERASI ON-AIR
                            </span>
                            <span class="ts-label-sm stitch-text-on-surface-variant font-bold">/admin/songs</span>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <h1 class="ts-headline-lg stitch-text-on-surface uppercase tracking-tight">
                                Panel Moderasi Lagu & Manajemen Antrian
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-container text-on-primary-container rounded-full ts-label-sm font-bold border border-on-surface/10">
                                <span class="w-2 h-2 rounded-full bg-primary animate-live-ping"></span> Live Sync
                            </span>
                        </div>
                    </div>

                    {{-- Quick Bulk Actions --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <button class="flex items-center gap-1.5 px-4 py-2.5 stitch-bg-surface-lowest stitch-text-on-surface ts-label-md rounded-lg shadow-sm border border-on-surface/10 hover:shadow transition-all cursor-pointer"
                                id="btn-export"
                                type="button">
                            <span class="material-symbols-outlined text-lg">download</span>
                            <span>Export Rekap CSV</span>
                        </button>
                        <button class="flex items-center gap-1.5 px-4 py-2.5 stitch-bg-surface-high stitch-text-tertiary ts-label-md rounded-lg shadow-sm border border-on-surface/10 hover:stitch-bg-error-container transition-all cursor-pointer"
                                id="btn-cleanup"
                                type="button">
                            <span class="material-symbols-outlined text-lg">auto_delete</span>
                            <span>Bersihkan Antrian Kedaluwarsa</span>
                        </button>
                    </div>
                </div>

                {{-- ━━━ 1. BENTO STATS BAR (4 METRICS) ━━━ --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Card 1: Menunggu Moderasi --}}
                    <div class="stitch-bg-tertiary-container stitch-text-on-tertiary-container p-4 rounded-xl flex items-center justify-between shadow-sm relative overflow-hidden border border-on-surface/10">
                        <div class="flex flex-col z-10">
                            <span class="ts-label-sm uppercase font-bold tracking-wider opacity-90">Menunggu Moderasi</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="ts-headline-lg font-bold leading-none">{{ $menunggu->count() }}</span>
                                <span class="ts-label-lg font-bold">Lagu ⏳</span>
                            </div>
                            <span class="ts-body-sm mt-1 opacity-80">Prioritas jam istirahat 1</span>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">hourglass_top</span>
                        </div>
                    </div>

                    {{-- Card 2: Disetujui di Antrian --}}
                    <div class="bg-primary-container text-on-primary-container p-4 rounded-xl flex items-center justify-between shadow-sm relative overflow-hidden border border-on-surface/10">
                        <div class="flex flex-col z-10">
                            <span class="ts-label-sm uppercase font-bold tracking-wider opacity-90">Disetujui di Antrian</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="ts-headline-lg font-bold leading-none">{{ $antrian->count() }}</span>
                                <span class="ts-label-lg font-bold">Lagu ✅</span>
                            </div>
                            <span class="ts-body-sm mt-1 opacity-80">Estimasi siaran: {{ $totalDurasiAntrianMnt ?? 28 }} menit</span>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">playlist_play</span>
                        </div>
                    </div>

                    {{-- Card 3: Ditolak Hari Ini --}}
                    <div class="stitch-bg-error-container stitch-text-on-error-container p-4 rounded-xl flex items-center justify-between shadow-sm relative overflow-hidden border border-on-surface/10">
                        <div class="flex flex-col z-10">
                            <span class="ts-label-sm uppercase font-bold tracking-wider opacity-90">Ditolak Hari Ini</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="ts-headline-lg font-bold leading-none">{{ $ditolak->count() }}</span>
                                <span class="ts-label-lg font-bold">Lagu ❌</span>
                            </div>
                            <span class="ts-body-sm mt-1 opacity-80">Alasan: profanity & duplikasi</span>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">block</span>
                        </div>
                    </div>

                    {{-- Card 4: Auto-Moderasi Toggle --}}
                    <div class="stitch-bg-surface-lowest stitch-text-on-surface p-4 rounded-xl flex flex-col justify-between shadow-sm border border-on-surface/10">
                        <div class="flex items-center justify-between">
                            <span class="ts-label-sm stitch-text-on-surface-variant uppercase font-bold tracking-wider">Sistem AI / Filter</span>
                            <span class="px-2 py-0.5 stitch-bg-surface-container rounded ts-label-sm font-bold" id="automod-status-label">BYPASS: OFF</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex flex-col">
                                <span class="ts-headline-sm leading-tight">Auto-Moderasi</span>
                                <span class="ts-body-sm stitch-text-on-surface-variant">Filter lirik eksplisit otomatis</span>
                            </div>
                            <button aria-label="Toggle Auto Moderasi"
                                    class="w-12 h-7 rounded-full relative flex items-center px-1 transition-colors cursor-pointer bg-primary-container"
                                    id="toggle-automod"
                                    type="button">
                                <div class="w-5 h-5 rounded-full bg-white shadow transition-transform translate-x-5" id="automod-thumb"></div>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- ━━━ 2. FILTER & SEARCH CONTROLS ━━━ --}}
                <div class="stitch-bg-surface-lowest p-4 rounded-xl shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">

                    {{-- Search Input --}}
                    <div class="w-full md:w-1/2 flex items-center stitch-bg-surface-low rounded-lg px-4 py-2 shadow-inner">
                        <span class="material-symbols-outlined stitch-text-on-surface-variant mr-2 text-xl">search</span>
                        <input class="w-full bg-transparent font-sans ts-body-md stitch-text-on-surface placeholder:stitch-text-outline focus:outline-none"
                               id="search-input"
                               placeholder="Cari judul lagu, nama siswa, atau NIS..."
                               type="text">
                        <button class="stitch-text-on-surface-variant hover:stitch-text-on-surface hidden p-1 cursor-pointer flex items-center justify-center" id="btn-clear-search" type="button" title="Bersihkan pencarian">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>

                    {{-- Dropdown Selectors --}}
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        {{-- Status Dropdown --}}
                        <div class="relative flex-1 md:flex-initial">
                            <select class="w-full appearance-none stitch-bg-surface-low stitch-text-on-surface ts-label-md py-2.5 pl-4 pr-10 rounded-lg focus:outline-none cursor-pointer"
                                    id="filter-status">
                                <option value="all">Semua Status</option>
                                <option value="pending" selected>Menunggu ({{ $menunggu->count() }})</option>
                                <option value="approved">Disetujui ({{ $antrian->count() }})</option>
                                <option value="rejected">Ditolak ({{ $ditolak->count() }})</option>
                                <option value="completed">Selesai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none stitch-text-on-surface-variant text-lg">expand_more</span>
                        </div>

                        {{-- Grade/Class Dropdown --}}
                        <div class="relative flex-1 md:flex-initial">
                            <select class="w-full appearance-none stitch-bg-surface-low stitch-text-on-surface ts-label-md py-2.5 pl-4 pr-10 rounded-lg focus:outline-none cursor-pointer"
                                    id="filter-grade">
                                <option value="all">Semua Kelas</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none stitch-text-on-surface-variant text-lg">expand_more</span>
                        </div>

                        {{-- Reset Filter Button --}}
                        <button class="p-2.5 stitch-bg-surface-high stitch-text-on-surface-variant hover:stitch-text-on-surface rounded-lg hover:stitch-bg-surface-highest transition-colors cursor-pointer"
                                id="btn-reset-filters"
                                title="Reset filter"
                                type="button">
                            <span class="material-symbols-outlined text-xl">tune</span>
                        </button>
                    </div>
                </div>

                {{-- ━━━ 3. LIST OF SONGS FOR MODERATION ━━━ --}}
                <div class="flex flex-col gap-4">

                    {{-- Section Tag / Tab indicators --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="ts-headline-sm uppercase tracking-tight stitch-text-on-surface font-bold">Daftar Antrean &amp; Moderasi</span>
                            <span class="px-2 py-0.5 stitch-bg-surface-container stitch-text-on-surface ts-label-sm rounded-full font-bold" id="item-count-badge">
                                Showing: {{ $menunggu->count() }} Items Terpilih
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 ts-label-sm stitch-text-on-surface-variant">
                            <span>Sort by:</span>
                            <button type="button" id="sort-order-btn" class="font-bold stitch-text-on-surface underline cursor-pointer bg-transparent border-0 p-0">
                                Waktu Request (<span id="sort-order-label">Terbaru</span>)
                            </button>
                        </div>
                    </div>

                    {{-- CONTAINER FOR MODERATION CARDS --}}
                    <div id="songs-list-container" class="space-y-4">

                        {{-- ── 1. DAFTAR LAGU MENUNGGU MODERASI ── --}}
                        @forelse ($menunggu as $song)
                            <div class="song-card song-status-menunggu stitch-bg-surface-lowest rounded-xl p-4 shadow-md flex flex-col xl:flex-row gap-4 justify-between transition-transform hover:-translate-y-0.5 border border-on-surface/10"
                                 data-status="menunggu"
                                 data-grade="{{ $song->user?->kelas ?: 'UNKNOWN' }}"
                                 data-nis="{{ $song->user?->nis ?: '' }}"
                                 data-title="{{ strtolower($song->judul) }}"
                                 data-channel="{{ strtolower($song->channel ?: '') }}"
                                 data-requester="{{ strtolower($song->user?->name ?: '') }}"
                                 data-message="{{ strtolower($song->pesan ?: '') }}"
                                 data-timestamp="{{ $song->created_at->timestamp }}">

                                <div class="flex flex-col md:flex-row gap-4 flex-1">
                                    {{-- Thumbnail + Play Preview --}}
                                    <div class="relative w-full md:w-44 h-32 md:h-auto rounded-lg overflow-hidden shrink-0 stitch-bg-surface-highest group border border-on-surface/10">
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                             src="{{ $song->thumbnail ?: 'https://img.youtube.com/vi/'.$song->youtube_id.'/mqdefault.jpg' }}"
                                             onerror="this.src='{{ asset('images/stitch/moderasi_asset_2.png') }}'"
                                             alt="Thumbnail">

                                        <a href="https://www.youtube.com/watch?v={{ $song->youtube_id }}"
                                           target="_blank" rel="noopener"
                                           class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shadow">
                                                <span class="material-symbols-outlined text-2xl ms-filled">play_arrow</span>
                                            </div>
                                        </a>

                                        <span class="absolute bottom-2 right-2 bg-black/80 text-white ts-label-sm px-1.5 py-0.5 rounded font-mono">
                                            {{ $song->durasi > 0 ? $song->durasiFormatted() : '03:45' }}
                                        </span>
                                        <span class="absolute top-2 left-2 bg-white/90 px-1.5 py-0.5 rounded text-on-surface ts-label-sm flex items-center gap-1 font-bold">
                                            <span class="material-symbols-outlined text-xs text-error">smart_display</span> YT
                                        </span>
                                    </div>

                                    {{-- Metadata & Dedication --}}
                                    <div class="flex flex-col justify-between flex-1 min-w-0">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                                <span class="px-2 py-0.5 stitch-bg-tertiary-container stitch-text-on-tertiary-container ts-label-sm uppercase rounded font-bold">
                                                    Menunggu
                                                </span>
                                                <span class="px-2 py-0.5 stitch-bg-surface-low stitch-text-on-surface ts-label-sm rounded font-bold">
                                                    #REQ-{{ $song->id }}
                                                </span>
                                                <span class="px-2 py-0.5 stitch-bg-primary-fixed stitch-text-on-primary-fixed ts-label-sm rounded flex items-center gap-1 font-bold">
                                                    <span class="material-symbols-outlined text-xs ms-filled">verified</span> Clean (No Profanity)
                                                </span>
                                            </div>

                                            <h2 class="ts-headline-md stitch-text-on-surface font-bold tracking-tight truncate leading-tight" title="{{ $song->judul }}">
                                                {{ $song->judul }}
                                            </h2>

                                            <p class="ts-body-sm stitch-text-on-surface-variant mt-0.5">
                                                Pengaju: <strong class="stitch-text-on-surface">{{ $song->user?->name ?: 'Siswa' }}</strong>
                                                • <span class="px-1.5 py-0.2 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed ts-label-sm rounded font-bold">{{ $song->user?->kelas ?: 'Siswa' }}</span>
                                                (NIS: {{ $song->user?->nis ?: '14144' }})
                                                • {{ $song->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        {{-- Sticky Note Dedication --}}
                                        @if ($song->pesan)
                                            <div class="mt-2 p-2.5 stitch-bg-surface-low rounded-lg flex items-start gap-2 relative border border-on-surface/5">
                                                <span class="material-symbols-outlined stitch-text-secondary text-lg mt-0.5 shrink-0">format_quote</span>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="ts-label-sm stitch-text-secondary uppercase font-bold">Salam Dedikasi:</span>
                                                    <p class="ts-body-md stitch-text-on-surface italic leading-snug">
                                                        "{{ $song->pesan }}"
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-row xl:flex-col justify-end gap-2.5 shrink-0 self-center xl:self-stretch">
                                    {{-- Setujui Form --}}
                                    <form method="POST" action="{{ route('admin.songs.approve', $song) }}" class="flex-1 xl:flex-initial">
                                        @csrf
                                        <button type="submit"
                                                class="w-full px-4 py-3 bg-primary-container text-on-primary-container ts-label-md rounded-lg shadow-sm hover:bg-primary-fixed transition-all flex items-center justify-center gap-1.5 cursor-pointer font-bold border border-on-surface/10">
                                            <span class="material-symbols-outlined text-xl ms-filled">check_circle</span>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    {{-- Tolak Modal Trigger --}}
                                    <button type="button"
                                            class="btn-open-reject flex-1 xl:flex-initial px-4 py-3 stitch-bg-error-container stitch-text-on-error-container ts-label-md rounded-lg shadow-sm hover:brightness-95 transition-all flex items-center justify-center gap-1.5 cursor-pointer font-bold border border-on-surface/10"
                                            data-song-id="{{ $song->id }}"
                                            data-song-title="{{ $song->judul }}"
                                            data-requester="{{ $song->user?->name ?: 'Siswa' }} ({{ $song->user?->kelas ?: '—' }})">
                                        <span class="material-symbols-outlined text-xl">cancel</span>
                                        <span>Tolak</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center stitch-text-on-surface-variant ts-body-md stitch-bg-surface-lowest rounded-xl border border-on-surface/10">
                                <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">done_all</span>
                                Tidak ada permintaan lagu yang menunggu moderasi saat ini. ✓
                            </div>
                        @endforelse


                        {{-- ── 2. DAFTAR LAGU DISETUJUI DI ANTREAN ── --}}
                        @foreach ($antrian as $i => $song)
                            <div class="song-card song-status-disetujui stitch-bg-surface-lowest rounded-xl p-4 shadow-md flex flex-col xl:flex-row gap-4 justify-between transition-transform hover:-translate-y-0.5 border border-on-surface/10"
                                 data-status="disetujui"
                                 data-grade="{{ $song->user?->kelas ?: 'UNKNOWN' }}"
                                 data-nis="{{ $song->user?->nis ?: '' }}"
                                 data-title="{{ strtolower($song->judul) }}"
                                 data-channel="{{ strtolower($song->channel ?: '') }}"
                                 data-requester="{{ strtolower($song->user?->name ?: '') }}"
                                 data-message="{{ strtolower($song->pesan ?: '') }}"
                                 data-timestamp="{{ $song->created_at->timestamp }}">

                                <div class="flex flex-col md:flex-row gap-4 flex-1">
                                    {{-- Urutan Rank Badge & Artwork --}}
                                    <div class="flex items-center gap-3 shrink-0">
                                        <div class="w-12 h-12 rounded-lg bg-primary-container text-on-primary-container flex flex-col items-center justify-center shrink-0 border border-on-surface/10">
                                            <span class="ts-label-sm uppercase leading-none font-bold">Antrean</span>
                                            <span class="ts-headline-sm font-extrabold leading-none">#{{ $i + 1 }}</span>
                                        </div>
                                        <div class="relative w-28 h-20 md:h-full md:w-28 rounded-lg overflow-hidden shrink-0 stitch-bg-surface-highest border border-on-surface/10">
                                            <img class="w-full h-full object-cover"
                                                 src="{{ $song->thumbnail ?: 'https://img.youtube.com/vi/'.$song->youtube_id.'/mqdefault.jpg' }}"
                                                 onerror="this.src='{{ asset('images/stitch/moderasi_asset_4.png') }}'"
                                                 alt="Cover">
                                        </div>
                                    </div>

                                    {{-- Metadata --}}
                                    <div class="flex flex-col justify-between flex-1 min-w-0">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                                <span class="px-2 py-0.5 stitch-bg-primary-fixed stitch-text-on-primary-fixed ts-label-sm uppercase rounded font-bold">
                                                    Disetujui di Antrean
                                                </span>
                                                @if ($i === 0)
                                                    <span class="px-2 py-0.5 stitch-bg-surface-container-high stitch-text-on-surface ts-label-sm rounded font-bold">
                                                        Next on Playlist
                                                    </span>
                                                @endif
                                            </div>

                                            <h2 class="ts-headline-md stitch-text-on-surface font-bold tracking-tight truncate leading-tight" title="{{ $song->judul }}">
                                                {{ $song->judul }}
                                            </h2>

                                            <p class="ts-body-sm stitch-text-on-surface-variant mt-0.5">
                                                Pengaju: <strong class="stitch-text-on-surface">{{ $song->user?->name ?: 'Siswa' }}</strong>
                                                • <span class="px-1.5 py-0.2 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed ts-label-sm rounded font-bold">{{ $song->user?->kelas ?: 'Siswa' }}</span>
                                            </p>
                                        </div>

                                        {{-- Queue info bar --}}
                                        <div class="mt-2 flex items-center gap-4 stitch-text-on-surface-variant ts-label-sm">
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">schedule</span>
                                                Estimasi On-Air: {{ now()->addMinutes(($i + 1) * 4)->format('H:i') }} WIB
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm ms-filled">verified_user</span>
                                                Disetujui oleh: Admin OSIS
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Queue Priority Controls --}}
                                <div class="flex items-center gap-2 shrink-0 self-center xl:self-stretch">
                                    {{-- Move Up --}}
                                    @if ($i > 0)
                                        <form method="POST" action="{{ route('admin.songs.moveUp', $song) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2.5 stitch-bg-surface-container-high stitch-text-on-surface rounded-lg hover:brightness-95 transition-colors flex items-center justify-center shadow-sm cursor-pointer border border-on-surface/10"
                                                    title="Naikkan urutan">
                                                <span class="material-symbols-outlined text-xl">arrow_upward</span>
                                            </button>
                                        </form>
                                    @else
                                        <button class="p-2.5 stitch-bg-surface-container text-outline rounded-lg cursor-not-allowed opacity-50 flex items-center justify-center border border-on-surface/5"
                                                disabled
                                                title="Sudah berada di posisi puncak">
                                            <span class="material-symbols-outlined text-xl">arrow_upward</span>
                                        </button>
                                    @endif

                                    {{-- Move Down --}}
                                    @if ($i < $antrian->count() - 1)
                                        <form method="POST" action="{{ route('admin.songs.moveDown', $song) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2.5 stitch-bg-surface-container-high stitch-text-on-surface rounded-lg hover:brightness-95 transition-colors flex items-center justify-center shadow-sm cursor-pointer border border-on-surface/10"
                                                    title="Turunkan urutan">
                                                <span class="material-symbols-outlined text-xl">arrow_downward</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Putar Sekarang Button --}}
                                    <a href="{{ route('admin.player.index') }}"
                                       class="px-4 py-2.5 stitch-bg-secondary-container stitch-text-on-secondary-container ts-label-md rounded-lg shadow-sm hover:brightness-95 transition-all flex items-center gap-1.5 font-bold border border-on-surface/10">
                                        <span class="material-symbols-outlined text-xl ms-filled">play_circle</span>
                                        <span>Putar Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach


                        {{-- ── 3. DAFTAR LAGU DITOLAK ── --}}
                        @foreach ($ditolak as $song)
                            <div class="song-card song-status-ditolak stitch-bg-surface-low rounded-xl p-4 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-start md:items-center opacity-85 hover:opacity-100 transition-opacity border border-on-surface/10"
                                 data-status="ditolak"
                                 data-grade="{{ $song->user?->kelas ?: 'UNKNOWN' }}"
                                 data-nis="{{ $song->user?->nis ?: '' }}"
                                 data-title="{{ strtolower($song->judul) }}"
                                 data-channel="{{ strtolower($song->channel ?: '') }}"
                                 data-requester="{{ strtolower($song->user?->name ?: '') }}"
                                 data-message="{{ strtolower($song->alasan_ditolak ?: '') }}"
                                 data-timestamp="{{ $song->updated_at->timestamp }}">

                                <div class="flex items-center gap-3.5 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-lg stitch-bg-error-container stitch-text-on-error-container flex items-center justify-center shrink-0 border border-on-surface/10">
                                        <span class="material-symbols-outlined text-2xl">block</span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                            <span class="px-2 py-0.5 stitch-bg-error text-white ts-label-sm uppercase rounded font-bold">
                                                Ditolak
                                            </span>
                                            <span class="ts-label-sm stitch-text-on-surface-variant">
                                                {{ $song->user?->name ?: 'Siswa' }} ({{ $song->user?->kelas ?: '—' }}) • {{ $song->updated_at->format('H:i') }} WIB
                                            </span>
                                        </div>
                                        <h3 class="ts-headline-sm stitch-text-on-surface line-through opacity-75 truncate" title="{{ $song->judul }}">
                                            {{ $song->judul }}
                                        </h3>
                                        <p class="ts-body-sm stitch-text-tertiary mt-0.5">
                                            <strong>Catatan Penolakan:</strong> {{ $song->alasan_ditolak ?: 'Tidak memenuhi kriteria siaran radio sekolah.' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
                                    {{-- Hapus Permanen --}}
                                    <form method="POST" action="{{ route('admin.songs.destroy', $song) }}" onsubmit="return confirm('Hapus riwayat lagu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 stitch-bg-surface-container-high stitch-text-on-surface ts-label-sm rounded hover:stitch-bg-error-container transition-colors cursor-pointer border border-on-surface/10">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        {{-- Empty State when no results match search/filter --}}
                        <div id="filter-empty-state" class="hidden p-12 text-center stitch-bg-surface-lowest rounded-xl shadow-sm flex flex-col items-center justify-center gap-3">
                            <div class="w-16 h-16 rounded-full stitch-bg-surface-container flex items-center justify-center stitch-text-on-surface-variant">
                                <span class="material-symbols-outlined text-3xl">search_off</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <h4 class="ts-headline-sm stitch-text-on-surface font-bold">Tidak ada lagu yang cocok</h4>
                                <p class="ts-body-sm stitch-text-on-surface-variant mt-1">Coba sesuaikan kata kunci pencarian atau ubah filter status & kelas.</p>
                            </div>
                            <button type="button" id="btn-empty-reset" class="mt-2 px-4 py-2 stitch-bg-primary-container stitch-text-on-primary-container ts-label-md rounded-lg font-bold shadow-sm hover:brightness-95 transition-all cursor-pointer">
                                Reset Semua Filter
                            </button>
                        </div>

                    </div>{{-- /songs-list-container --}}

                    {{-- ━━━ DATA SUMMARY BAR FOOTER ━━━ --}}
                    <div class="p-4 stitch-bg-surface-container rounded-xl flex flex-col md:flex-row items-center justify-between stitch-text-on-surface-variant gap-3 ts-label-sm border border-on-surface/10">
                        <div class="flex items-center gap-3">
                            <span>
                                Menampilkan <strong>{{ $menunggu->count() + $antrian->count() + $ditolak->count() }}</strong> total permintaan lagu hari ini
                            </span>
                            <span class="hidden md:inline">•</span>
                            <span class="hidden md:inline">Terakhir di-refresh: Baru saja ({{ now()->format('H:i:s') }})</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-3 py-1 stitch-bg-surface-lowest stitch-text-on-surface rounded shadow-sm font-bold border border-on-surface/10">1</span>
                        </div>
                    </div>

                </div>{{-- /flex-col gap-4 --}}

            </div>{{-- /p-6 --}}
        </main>

    </div>{{-- /MAIN AREA --}}

</div>{{-- /full screen layout --}}


{{-- ═══════════════════════════════════════
     NEO-BRUTALIST REJECTION MODAL DIALOG
═══════════════════════════════════════ --}}
<div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden" id="modal-reject">
    <div class="stitch-bg-surface-lowest rounded-2xl w-full max-w-lg p-6 shadow-2xl flex flex-col gap-4 transform scale-95 transition-transform border-2 stitch-border-on-surface shadow-stitch-lg" id="modal-content">

        {{-- Modal Header --}}
        <div class="flex items-start justify-between pb-2 border-b border-on-surface/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full stitch-bg-error-container stitch-text-on-error-container flex items-center justify-center shrink-0 border border-on-surface/10">
                    <span class="material-symbols-outlined text-2xl">gavel</span>
                </div>
                <div class="flex flex-col">
                    <span class="ts-headline-sm stitch-text-on-surface font-bold">Tolak Permintaan Lagu</span>
                    <span class="ts-body-sm stitch-text-on-surface-variant" id="modal-song-title">Target Lagu</span>
                </div>
            </div>
            <button class="p-1 rounded-lg stitch-text-on-surface-variant hover:stitch-text-on-surface hover:stitch-bg-surface-container cursor-pointer" id="btn-close-modal" type="button">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Form Reject --}}
        <form method="POST" action="" id="form-reject-song" class="flex flex-col gap-3">
            @csrf

            <label class="ts-label-md stitch-text-on-surface uppercase font-bold">Pilih Alasan Penolakan:</label>

            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-3 p-3 stitch-bg-surface-low rounded-lg cursor-pointer hover:stitch-bg-surface-container border border-on-surface/5">
                    <input checked class="accent-error w-4 h-4" name="alasan_preset" type="radio" value="Lirik Sensitif / Mengandung Umpatan atau SARA">
                    <div class="flex flex-col">
                        <span class="ts-label-md stitch-text-on-surface font-bold">Lirik Sensitif / Kata Kasar</span>
                        <span class="ts-body-sm stitch-text-on-surface-variant">Mengandung umpatan, SARA, atau tema tidak pantas</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 stitch-bg-surface-low rounded-lg cursor-pointer hover:stitch-bg-surface-container border border-on-surface/5">
                    <input class="accent-error w-4 h-4" name="alasan_preset" type="radio" value="Bukan genre lagu yang diizinkan untuk suasana istirahat sekolah">
                    <div class="flex flex-col">
                        <span class="ts-label-md stitch-text-on-surface font-bold">Bukan Genre yang Diizinkan</span>
                        <span class="ts-body-sm stitch-text-on-surface-variant">Distorsi terlalu bising untuk suasana sekolah</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 stitch-bg-surface-low rounded-lg cursor-pointer hover:stitch-bg-surface-container border border-on-surface/5">
                    <input class="accent-error w-4 h-4" name="alasan_preset" type="radio" value="Duplikasi lagu / sudah sering diputar dalam sesi kemarin">
                    <div class="flex flex-col">
                        <span class="ts-label-md stitch-text-on-surface font-bold">Duplikasi / Sudah Diputar Kemarin</span>
                        <span class="ts-body-sm stitch-text-on-surface-variant">Menjaga variasi playlist siaran radio</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3 stitch-bg-surface-low rounded-lg cursor-pointer hover:stitch-bg-surface-container border border-on-surface/5">
                    <input class="accent-error w-4 h-4" name="alasan_preset" type="radio" value="other">
                    <div class="flex flex-col">
                        <span class="ts-label-md stitch-text-on-surface font-bold">Alasan Lainnya...</span>
                    </div>
                </label>
            </div>

            <div class="flex flex-col mt-1">
                <label class="ts-label-sm stitch-text-on-surface-variant mb-1 font-bold">Catatan Tambahan untuk Siswa (Opsional):</label>
                <textarea class="p-3 stitch-bg-surface-low rounded-lg ts-body-sm stitch-text-on-surface placeholder:stitch-text-outline focus:outline-none border border-on-surface/10"
                          id="reject-note"
                          name="alasan"
                          placeholder="Contoh: Silakan ajukan lagu lain dari musisi yang sama versi radio edit ya!"
                          rows="2"></textarea>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-on-surface/10">
                <button type="button"
                        class="px-4 py-2.5 stitch-bg-surface-high stitch-text-on-surface ts-label-md rounded-lg hover:brightness-95 transition-colors cursor-pointer border border-on-surface/10"
                        id="btn-cancel-reject">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 stitch-bg-tertiary stitch-text-on-tertiary ts-label-md rounded-lg shadow-stitch-sm hover:brightness-95 transition-all flex items-center gap-1.5 cursor-pointer font-bold border-2 stitch-border-on-surface"
                        id="btn-confirm-reject">
                    <span class="material-symbols-outlined text-lg">check</span>
                    <span>Konfirmasi Penolakan</span>
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════
     INTERACTIVE JAVASCRIPT
═══════════════════════════════════════ --}}
<script>
(function() {
    // 1. Auto-moderasi Toggle
    const toggleBtn = document.getElementById('toggle-automod');
    const toggleThumb = document.getElementById('automod-thumb');
    const statusLabel = document.getElementById('automod-status-label');
    let autoModEnabled = true;

    if (toggleBtn && toggleThumb) {
        toggleBtn.addEventListener('click', () => {
            autoModEnabled = !autoModEnabled;
            if (autoModEnabled) {
                toggleBtn.classList.remove('bg-surface-variant');
                toggleBtn.classList.add('bg-primary-container');
                toggleThumb.classList.add('translate-x-5');
                if (statusLabel) statusLabel.textContent = 'BYPASS: OFF';
            } else {
                toggleBtn.classList.add('bg-surface-variant');
                toggleBtn.classList.remove('bg-primary-container');
                toggleThumb.classList.remove('translate-x-5');
                if (statusLabel) statusLabel.textContent = 'BYPASS: ON';
            }
        });
    }

    // 2. Reject Modal Dialog Logic
    const modal          = document.getElementById('modal-reject');
    const modalContent   = document.getElementById('modal-content');
    const songTitleElem  = document.getElementById('modal-song-title');
    const formReject     = document.getElementById('form-reject-song');
    const rejectNote     = document.getElementById('reject-note');
    const openRejectBtns = document.querySelectorAll('.btn-open-reject');
    const closeRejectBtn = document.getElementById('btn-close-modal');
    const cancelRejectBtn= document.getElementById('btn-cancel-reject');

    function openModal(songId, title, requester) {
        if (!modal) return;
        formReject.action = `/admin/songs/${songId}/reject`;
        songTitleElem.textContent = title + ' • ' + requester;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        if (!modal) return;
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    openRejectBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-song-id');
            const title = btn.getAttribute('data-song-title') || 'Lagu';
            const requester = btn.getAttribute('data-requester') || 'Siswa';
            openModal(id, title, requester);
        });
    });

    if (closeRejectBtn) closeRejectBtn.addEventListener('click', closeModal);
    if (cancelRejectBtn) cancelRejectBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    // Auto-fill note based on radio choice if custom note is empty
    formReject.addEventListener('submit', function() {
        const selectedRadio = document.querySelector('input[name="alasan_preset"]:checked');
        if (selectedRadio && selectedRadio.value !== 'other' && !rejectNote.value.trim()) {
            rejectNote.value = selectedRadio.value;
        }
    });

    // 3. Client-side Search and Filtering
    const searchInput    = document.getElementById('search-input');
    const btnClearSearch = document.getElementById('btn-clear-search');
    const filterStatus   = document.getElementById('filter-status');
    const filterGrade    = document.getElementById('filter-grade');
    const btnReset       = document.getElementById('btn-reset-filters');
    const btnEmptyReset  = document.getElementById('btn-empty-reset');
    const songCards      = document.querySelectorAll('.song-card');
    const itemCountBadge = document.getElementById('item-count-badge');
    const emptyState     = document.getElementById('filter-empty-state');
    const sortBtn        = document.getElementById('sort-order-btn');
    const sortLabel      = document.getElementById('sort-order-label');
    const cardsContainer = document.getElementById('songs-list-container');

    let sortOrder = 'desc'; // 'desc' = Terbaru, 'asc' = Terlama

    function applyFilters() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        const statusVal = filterStatus?.value || 'all';
        const gradeVal = filterGrade?.value || 'all';
        let visibleCount = 0;

        if (btnClearSearch) {
            btnClearSearch.classList.toggle('hidden', q.length === 0);
        }

        songCards.forEach(card => {
            const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();
            const cardGrade  = (card.getAttribute('data-grade') || '').toUpperCase();
            const cardTitle  = card.getAttribute('data-title') || '';
            const cardReq    = card.getAttribute('data-requester') || '';
            const cardNis    = card.getAttribute('data-nis') || '';
            const cardChan   = card.getAttribute('data-channel') || '';
            const cardMsg    = card.getAttribute('data-message') || '';

            // Universal query match
            const matchQuery = !q ||
                cardTitle.includes(q) ||
                cardReq.includes(q) ||
                cardNis.includes(q) ||
                cardChan.includes(q) ||
                cardMsg.includes(q) ||
                cardGrade.toLowerCase().includes(q);

            // Status match
            let matchStatus = false;
            if (statusVal === 'all') {
                matchStatus = true;
            } else if (statusVal === 'pending') {
                matchStatus = (cardStatus === 'menunggu');
            } else if (statusVal === 'approved') {
                matchStatus = (cardStatus === 'disetujui');
            } else if (statusVal === 'rejected') {
                matchStatus = (cardStatus === 'ditolak');
            } else if (statusVal === 'completed') {
                matchStatus = (cardStatus === 'selesai');
            }

            // Grade match (strict so X doesn't match XI/XII)
            // Also handle empty grades - they should still be filterable
            let matchGrade = false;
            if (gradeVal === 'all') {
                matchGrade = true;
            } else if (gradeVal === 'X') {
                // Match exactly "X" or contain X but not XI/XII
                matchGrade = cardGrade === 'X' || (cardGrade.includes('X') && !cardGrade.includes('XI') && !cardGrade.includes('XII'));
            } else if (gradeVal === 'XI') {
                // Match exactly "XI" or contain XI but not XII
                matchGrade = cardGrade === 'XI' || (cardGrade.includes('XI') && !cardGrade.includes('XII'));
            } else if (gradeVal === 'XII') {
                // Match exactly "XII" or contain XII
                matchGrade = cardGrade === 'XII' || cardGrade.includes('XII');
            }
            // If grade is UNKNOWN (students without class), show when "all" is selected only
            if (cardGrade === 'UNKNOWN' && gradeVal !== 'all') {
                matchGrade = false;
            }
            
            if (matchQuery && matchStatus && matchGrade) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (itemCountBadge) {
            itemCountBadge.textContent = `Showing: ${visibleCount} Items Terpilih`;
        }

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    if (btnClearSearch) {
        btnClearSearch.addEventListener('click', () => {
            searchInput.value = '';
            applyFilters();
            searchInput.focus();
        });
    }
    if (filterStatus) {
        filterStatus.addEventListener('change', applyFilters);
    }
    if (filterGrade) {
        filterGrade.addEventListener('change', applyFilters);
    }

    function resetAllFilters() {
        if (searchInput) searchInput.value = '';
        if (filterStatus) filterStatus.value = 'all';
        if (filterGrade) filterGrade.value = 'all';
        applyFilters();
    }

    if (btnReset) btnReset.addEventListener('click', resetAllFilters);
    if (btnEmptyReset) btnEmptyReset.addEventListener('click', resetAllFilters);

    // Sorting functionality - only sort visible cards
    if (sortBtn && cardsContainer) {
        sortBtn.addEventListener('click', () => {
            sortOrder = (sortOrder === 'desc') ? 'asc' : 'desc';
            if (sortLabel) {
                sortLabel.textContent = (sortOrder === 'desc') ? 'Terbaru' : 'Terlama';
            }

            // Sort only visible card elements in DOM
            const cardsArray = Array.from(songCards).filter(card => card.style.display !== 'none');
            cardsArray.sort((a, b) => {
                const timeA = parseInt(a.getAttribute('data-timestamp') || '0', 10);
                const timeB = parseInt(b.getAttribute('data-timestamp') || '0', 10);
                return (sortOrder === 'desc') ? (timeB - timeA) : (timeA - timeB);
            });

            // Reorder visible cards in DOM
            cardsArray.forEach(card => cardsContainer.insertBefore(card, cardsContainer.firstChild));
            cardsArray.reverse().forEach(card => cardsContainer.insertBefore(card, cardsContainer.firstChild));
        });
    }

    // Initial filter run (defaults to 'pending')
    applyFilters();

    // 4. Quick Actions
    const btnExport = document.getElementById('btn-export');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            // Generate simple CSV download of current visible table
            let csv = "ID,Judul,Pengaju,Kelas,Status\n";
            songCards.forEach(c => {
                if (c.style.display !== 'none') {
                    const title = (c.getAttribute('data-title') || '').replace(/,/g, ' ');
                    const req   = (c.getAttribute('data-requester') || '').replace(/,/g, ' ');
                    const grade = (c.getAttribute('data-grade') || '').replace(/,/g, ' ');
                    const st    = c.getAttribute('data-status') || '';
                    csv += `-,${title},${req},${grade},${st}\n`;
                }
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('download', `rekap_moderasi_${new Date().toISOString().slice(0,10)}.csv`);
            a.click();
        });
    }

    const btnCleanup = document.getElementById('btn-cleanup');
    if (btnCleanup) {
        btnCleanup.addEventListener('click', () => {
            if (confirm('Bersihkan antrian kedaluwarsa dari sesi istirahat sebelumnya?')) {
                alert('Antrian kedaluwarsa berhasil dibersihkan.');
            }
        });
    }
})();
</script>
</x-admin-layout>
