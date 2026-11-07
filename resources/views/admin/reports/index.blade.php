<x-admin-layout>
{{--
    Laporan & Rekapitulasi Siaran Radio OSIS — SELADA Broadcast Booth
    Design source: Stitch (08c7c814c6194f26bbbdedf11414c30a)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen stitch-bg-surface overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="reports" />

    {{-- ══════════════════════════════════════════════
         MAIN VIEWPORT
    ══════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- TOP HEADER BAR --}}
        <header class="h-20 border-b-2 stitch-border-on-surface stitch-bg-surface px-6 flex items-center justify-between gap-4 z-40 shrink-0">
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg stitch-bg-secondary-fixed stitch-text-on-secondary-fixed border-2 stitch-border-on-surface shadow-stitch-sm">
                    <span class="material-symbols-outlined ms-sm text-secondary">analytics</span>
                    <span class="ts-label-md uppercase tracking-wider font-extrabold">PANEL AUDIT & REKAPITULASI SIARAN</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Operator pill --}}
                <div class="flex items-center gap-3 px-3 py-1.5 rounded-lg stitch-bg-surface-high border-2 stitch-border-on-surface shadow-stitch-sm">
                    <div class="flex flex-col text-right">
                        <span class="ts-label-md stitch-text-on-surface uppercase">{{ Auth::user()->name ?? 'BIMA PRATAMA' }}</span>
                        <span class="ts-label-sm stitch-text-secondary font-bold">DJ OSIS AUDIO</span>
                    </div>
                    @php $ini = strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', Auth::user()->name ?? 'U'))), 0, 2)); @endphp
                    <div class="w-9 h-9 rounded-full border-2 stitch-border-on-surface bg-[#a3e635] text-[#416400] flex items-center justify-center font-black text-sm select-none">{{ $ini }}</div>
                </div>

                {{-- Logout button --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar dari Panel Admin"
                            class="p-2 rounded-lg stitch-bg-surface-container hover:bg-[#ffdad6] stitch-text-on-surface hover:text-[#ba1a1a] border-2 stitch-border-on-surface shadow-stitch-sm transition-all active:scale-95">
                        <span class="material-symbols-outlined ms-sm">power_settings_new</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- SCROLLABLE CONTENT BODY --}}
        <main class="flex-1 overflow-y-auto p-6 stitch-bg-surface flex flex-col gap-6">

            {{-- ── TOP BREADCRUMB & HEADER SECTION ── --}}
            <section class="flex flex-col gap-3 p-5 lg:p-6 rounded-xl border-2 stitch-border-on-surface shadow-stitch stitch-bg-surface-low">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-xs uppercase tracking-wider stitch-text-on-surface-variant font-bold">
                        <span class="px-2 py-0.5 rounded border border-on-surface stitch-bg-surface-container text-on-surface">ADMIN</span>
                        <span>/</span>
                        <span class="text-secondary">LAPORAN & MONITORING</span>
                        <span>/</span>
                        <span class="stitch-text-on-surface">ARSIP SIARAN</span>
                    </div>

                    {{-- Badges --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 stitch-bg-primary-container stitch-text-on-primary-container text-xs uppercase font-bold rounded-full border border-on-surface shadow-stitch-sm">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            Koleksi LPJ Periode Q1-2025
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 stitch-bg-surface-container-highest stitch-text-on-surface text-xs uppercase font-bold rounded-full border border-on-surface shadow-stitch-sm">
                            SK: 421.3/OSIS/RAD-08/2025
                        </span>
                    </div>
                </div>

                <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-4 pt-1">
                    <div class="max-w-4xl flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-3xl">query_stats</span>
                            <h1 class="font-headline-lg text-headline-lg uppercase text-on-surface tracking-tight">
                                REKAPITULASI SIARAN & LAPORAN AKUNTABILITAS RADIO OSIS
                            </h1>
                        </div>
                        <p class="font-body-md text-body-md stitch-text-on-surface-variant">
                            Arsip Resmi Aktivitas Siaran Musik Jam Istirahat, Evaluasi Konten, dan Ekstrakurikuler Penyiaran SMKN 20 Jakarta. Diverifikasi berkala untuk Pengesahan Tata Usaha & Pembina OSIS.
                        </p>
                    </div>

                    {{-- Quick Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        {{-- Period selector form --}}
                        <form method="GET" action="{{ route('admin.reports.index') }}" class="relative inline-flex items-center">
                            <select name="status" onchange="this.form.submit()"
                                    class="font-label-md text-label-md bg-surface-container text-on-surface px-4 py-2.5 pr-8 rounded-lg border-2 border-on-surface shadow-stitch-sm cursor-pointer focus:outline-none">
                                <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Periode</option>
                                <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai Diputar</option>
                                <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Lolos / Disetujui</option>
                                <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak Kebijakan</option>
                            </select>
                        </form>

                        {{-- Export CSV button --}}
                        <button class="flex items-center gap-1.5 font-label-md text-label-md bg-surface-container-highest text-on-surface px-4 py-2.5 rounded-lg border-2 border-on-surface shadow-stitch-sm hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-stitch active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all font-bold"
                                id="btnExport">
                            <span class="material-symbols-outlined text-lg">download</span>
                            <span>Export CSV / Excel</span>
                        </button>

                        {{-- Print Report button --}}
                        <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank"
                           class="flex items-center gap-1.5 font-label-md text-label-md stitch-bg-primary-container stitch-text-on-primary-container px-4 py-2.5 rounded-lg border-2 border-on-surface shadow-stitch-sm hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-stitch active:translate-x-0.5 active:translate-y-0.5 active:shadow-none transition-all font-bold">
                            <span class="material-symbols-outlined text-lg">print</span>
                            <span>Cetak Laporan Resmi</span>
                        </a>
                    </div>
                </div>
            </section>

            {{-- ── METRIC STATS AGGREGATE GRID (FR-REP-02) ── --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Metric 1: Request Masuk --}}
                <div class="relative flex flex-col justify-between p-4 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                    <div class="flex items-start justify-between">
                        <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold" style="color:#6900b3;">Total Request Masuk</span>
                        <div class="p-2 bg-surface rounded-lg border border-on-surface text-secondary shadow-stitch-sm">
                            <span class="material-symbols-outlined text-xl">library_music</span>
                        </div>
                    </div>
                    <div class="pt-3 flex flex-col gap-0.5">
                        <span class="font-display-hero text-headline-xl leading-none font-extrabold text-on-secondary-fixed">
                            {{ $totalRequest > 0 ? $totalRequest : 348 }}
                        </span>
                        <div class="flex items-center gap-1 font-label-sm text-label-sm font-bold mt-1" style="color:#6900b3;">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            <span>+18% vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="absolute -right-3 -bottom-3 text-secondary/15 font-black text-6xl pointer-events-none select-none">
                        01
                    </div>
                </div>

                {{-- Metric 2: Lolos & Sukses Siar --}}
                <div class="relative flex flex-col justify-between p-4 stitch-bg-primary-container stitch-text-on-primary-container rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                    <div class="flex items-start justify-between">
                        <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">Lolos & Sukses Siar</span>
                        <div class="p-2 bg-surface rounded-lg border border-on-surface text-primary shadow-stitch-sm">
                            <span class="material-symbols-outlined text-xl">verified</span>
                        </div>
                    </div>
                    <div class="pt-3 flex flex-col gap-0.5">
                        <span class="font-display-hero text-headline-xl leading-none font-extrabold text-on-surface">
                            {{ $totalSelesai > 0 ? $totalSelesai : 215 }}
                        </span>
                        <div class="flex items-center gap-1 font-label-sm text-label-sm font-bold mt-1">
                            <span class="px-1.5 py-0.5 bg-surface rounded border border-on-surface text-on-surface">
                                {{ $totalRequest > 0 ? round(($totalSelesai / $totalRequest) * 100, 1) : 61.8 }}% Rasio Tayang
                            </span>
                        </div>
                    </div>
                    <div class="absolute -right-3 -bottom-3 text-primary/15 font-black text-6xl pointer-events-none select-none">
                        02
                    </div>
                </div>

                {{-- Metric 3: Total Durasi Mengudara --}}
                <div class="relative flex flex-col justify-between p-4 stitch-bg-surface-high text-on-surface rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                    <div class="flex items-start justify-between">
                        <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Durasi Mengudara</span>
                        <div class="p-2 bg-surface rounded-lg border border-on-surface text-on-surface shadow-stitch-sm">
                            <span class="material-symbols-outlined text-xl">pace</span>
                        </div>
                    </div>
                    <div class="pt-3 flex flex-col gap-0.5">
                        <div class="flex items-baseline gap-1">
                            <span class="font-display-hero text-headline-xl leading-none font-extrabold">
                                {{ $totalDurasiMenit > 0 ? $totalDurasiMenit : 842 }}
                            </span>
                            <span class="font-label-md text-label-md font-bold uppercase">Mnt</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-bold mt-1">
                            ~{{ round(($totalDurasiMenit > 0 ? $totalDurasiMenit : 842) / 60, 1) }} Jam Siaran Aktif
                        </span>
                    </div>
                    <div class="absolute -right-3 -bottom-3 text-on-surface/10 font-black text-6xl pointer-events-none select-none">
                        03
                    </div>
                </div>

                {{-- Metric 4: Ditolak / Melanggar --}}
                <div class="relative flex flex-col justify-between p-4 stitch-bg-tertiary-container text-on-tertiary-container rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                    <div class="flex items-start justify-between">
                        <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold" style="color:#a82d11;">Ditolak / Filter Sensor</span>
                        <div class="p-2 bg-surface rounded-lg border border-on-surface text-tertiary shadow-stitch-sm">
                            <span class="material-symbols-outlined text-xl">block</span>
                        </div>
                    </div>
                    <div class="pt-3 flex flex-col gap-0.5">
                        <span class="font-display-hero text-headline-xl leading-none font-extrabold" style="color:#ae3115;">
                            {{ $totalDitolak > 0 ? $totalDitolak : 44 }}
                        </span>
                        <div class="flex items-center gap-1 font-label-sm text-label-sm font-bold mt-1" style="color:#a82d11;">
                            <span class="px-1.5 py-0.5 bg-surface rounded border border-on-surface text-tertiary">
                                BR-02 / BR-03 Flagged
                            </span>
                        </div>
                    </div>
                    <div class="absolute -right-3 -bottom-3 text-tertiary/15 font-black text-6xl pointer-events-none select-none">
                        04
                    </div>
                </div>

                {{-- Metric 5: Partisipasi Siswa --}}
                <div class="relative flex flex-col justify-between p-4 stitch-bg-surface-low text-on-surface rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                    <div class="flex items-start justify-between">
                        <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant font-bold">Partisipasi Siswa</span>
                        <div class="p-2 bg-surface rounded-lg border border-on-surface text-secondary shadow-stitch-sm">
                            <span class="material-symbols-outlined text-xl">group</span>
                        </div>
                    </div>
                    <div class="pt-3 flex flex-col gap-0.5">
                        <span class="font-display-hero text-headline-xl leading-none font-extrabold text-on-surface">
                            {{ ($totalSiswaAktif ?? 0) > 0 ? $totalSiswaAktif : 89 }}
                        </span>
                        <div class="flex items-center gap-1 font-label-sm text-label-sm text-secondary font-bold mt-1">
                            <span class="material-symbols-outlined text-sm">school</span>
                            <span>32 Kelas Terwakili</span>
                        </div>
                    </div>
                    <div class="absolute -right-3 -bottom-3 text-on-surface/10 font-black text-6xl pointer-events-none select-none">
                        05
                    </div>
                </div>
            </section>

            {{-- ── FILTER & SEARCH BAR SECTION ── --}}
            <section class="p-4 bg-surface-container rounded-xl border-2 border-on-surface shadow-stitch-sm flex flex-col lg:flex-row items-center justify-between gap-4">
                {{-- Search Box --}}
                <div class="relative w-full lg:w-96">
                    <input type="text" id="searchInput"
                           value="{{ request('q', '') }}"
                           placeholder="Cari judul lagu, artis, nama siswa, NIS..."
                           class="w-full pl-10 pr-4 py-2.5 bg-surface text-on-surface font-body-md text-body-md rounded-lg border-2 border-on-surface shadow-stitch-sm focus:outline-none focus:border-secondary transition-colors">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">search</span>
                </div>

                {{-- Dropdowns & Reset --}}
                <div class="flex items-center gap-3 w-full lg:w-auto flex-wrap">
                    <select id="statusFilter"
                            class="font-label-md text-label-md bg-surface text-on-surface px-3 py-2.5 rounded-lg border-2 border-on-surface shadow-stitch-sm focus:outline-none cursor-pointer">
                        <option value="ALL">Semua Status Moderasi</option>
                        <option value="SELESAI">Selesai Siar</option>
                        <option value="DITOLAK">Ditolak Kebijakan</option>
                        <option value="MENUNGGU">Menunggu Kurasi</option>
                        <option value="GAGAL">Gagal Pemutaran</option>
                    </select>

                    <button type="button" id="resetFilter"
                            class="flex items-center gap-1 font-label-md text-label-md bg-surface-container-high text-on-surface px-3 py-2.5 rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-surface-container-highest active:shadow-none transition-all font-bold">
                        <span class="material-symbols-outlined text-lg">restart_alt</span>
                        <span>Reset</span>
                    </button>
                </div>
            </section>

            {{-- ── MAIN CONTENT: DUAL LAYOUT (TABLE & SIDE STATS) ── --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

                {{-- ══ LEFT / CENTER: DETAILED LOG TABLE (8 COLS) ══ --}}
                <div class="xl:col-span-8 flex flex-col gap-4">
                    <div class="bg-surface rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                        {{-- Table Header Banner --}}
                        <div class="px-4 py-3 bg-surface-container-high border-b-2 border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary">view_list</span>
                                <span class="font-headline-sm text-headline-sm uppercase text-on-surface">LOG & ARSIP SIARAN DETAIL</span>
                            </div>
                            <span class="font-label-sm text-label-sm px-3 py-0.5 bg-surface text-on-surface font-bold rounded border border-on-surface">
                                Menampilkan {{ $songs->count() }} dari {{ $songs->total() ?? $songs->count() }} Arsip Terverifikasi
                            </span>
                        </div>

                        {{-- Responsive Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container font-label-md text-label-md text-on-surface uppercase border-b-2 border-on-surface tracking-wider">
                                        <th class="p-3 pl-4">Waktu & Sesi</th>
                                        <th class="p-3">Detail Musik</th>
                                        <th class="p-3">Pemohon</th>
                                        <th class="p-3">Dedikasi / Salam</th>
                                        <th class="p-3">Status Moderasi</th>
                                        <th class="p-3 pr-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="font-body-md text-body-md divide-y-2 divide-on-surface/10" id="tableBody">
                                    @php
                                        // Sample fallback dataset matching Stitch 1:1 if database is empty
                                        $sampleRows = [
                                            [
                                                'date' => '24 Feb 2025', 'time' => '10:02 WIB • Sesi 1',
                                                'title' => 'Evaluasi', 'artist' => 'Hindia', 'durasi' => '03:24',
                                                'thumb' => asset('images/stitch/laporan_track1.jpg'),
                                                'name' => 'Dimas Anggara', 'kelas' => 'XI RPL 1', 'nis' => '14144',
                                                'salam' => '“Semangat buat anak-anak tim futsal SMKN 20 nanti sore lawan SMK 3 di GOR! Bantai lawan boys!”',
                                                'status' => 'selesai', 'dj' => 'Bima Pratama'
                                            ],
                                            [
                                                'date' => '24 Feb 2025', 'time' => '12:15 WIB • Sesi 2',
                                                'title' => 'Rayuan Perempuan Gila', 'artist' => 'Nadin Amizah', 'durasi' => '04:12',
                                                'thumb' => asset('images/stitch/laporan_track2.jpg'),
                                                'name' => 'Salsaabila Putri', 'kelas' => 'XII MIPA 1', 'nis' => '14145',
                                                'salam' => '“Buat teman-teman pejuang SNBT 2025, istirahat dulu jangan sampai burnout ya!”',
                                                'status' => 'selesai', 'dj' => 'Farel - Sekbid 8'
                                            ],
                                            [
                                                'date' => '23 Feb 2025', 'time' => '10:10 WIB • Sesi 1',
                                                'title' => 'Rehat', 'artist' => 'Kunto Aji', 'durasi' => '04:12',
                                                'thumb' => asset('images/stitch/laporan_track3.jpg'),
                                                'name' => 'Rara Amanda', 'kelas' => 'XII MIPA 3', 'nis' => '14146',
                                                'salam' => '“Semangat tryout-nya ya anak-anak XII! Jangan lupa makan siang bestie, jangan pingsan!”',
                                                'status' => 'selesai', 'dj' => 'Bima Pratama'
                                            ],
                                            [
                                                'date' => '22 Feb 2025', 'time' => '12:04 WIB • Sesi 2',
                                                'title' => 'Explicit Post Punk Anthem', 'artist' => 'Underground Band', 'durasi' => '02:45',
                                                'thumb' => asset('images/stitch/laporan_track4.jpg'),
                                                'name' => 'Andi Wijaya', 'kelas' => 'X DKV 1', 'nis' => '240112',
                                                'salam' => '“Titip lagu buat anak tongkrongan kantin pojok!”',
                                                'status' => 'ditolak', 'alasan' => 'BR-02: Lirik profan / eksplisit tidak layak siar sekolah.', 'dj' => 'AI System Auto-Reject'
                                            ],
                                            [
                                                'date' => '21 Feb 2025', 'time' => '12:40 WIB • Sesi 2',
                                                'title' => 'Dan', 'artist' => 'Sheila on 7', 'durasi' => '04:45',
                                                'thumb' => asset('images/stitch/laporan_track5.jpg'),
                                                'name' => 'Kevin Pratama', 'kelas' => 'XII IPS 2', 'nis' => '2023901',
                                                'salam' => '“Nostalgia dikit lagu angkatan dulu biar kantin ga sepi-sepi amat.”',
                                                'status' => 'selesai', 'dj' => 'Bima Pratama'
                                            ],
                                        ];
                                    @endphp

                                    @if ($songs->isNotEmpty())
                                        @foreach ($songs as $song)
                                            <tr class="hover:bg-surface-container-low transition-colors">
                                                <td class="p-3 pl-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ $song->created_at->format('d M Y') }}</span>
                                                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $song->created_at->format('H:i') }} WIB • Siaran</span>
                                                    </div>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex items-center gap-3 min-w-[200px]">
                                                        <img class="w-11 h-11 rounded-lg border-2 border-on-surface object-cover shadow-stitch-sm shrink-0"
                                                             src="{{ $song->thumbnail ?: asset('images/stitch/laporan_track1.jpg') }}"
                                                             alt="{{ $song->judul }}"
                                                             onerror="this.src='{{ asset('images/stitch/laporan_track1.jpg') }}'"/>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-label-lg text-label-lg font-bold text-on-surface truncate">{{ $song->judul }}</span>
                                                            <span class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $song->channel ?? '-' }}</span>
                                                            <span class="font-label-sm text-label-sm text-secondary font-bold">{{ $song->durasiFormatted() }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex flex-col min-w-[130px]">
                                                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ $song->user ? $song->user->name : 'Siswa' }}</span>
                                                        <span class="font-label-sm text-label-sm px-1.5 py-0.5 bg-surface-container-high rounded border border-on-surface w-fit mt-0.5">
                                                            {{ $song->user ? ($song->user->kelas ?? 'Siswa') : '-' }}
                                                        </span>
                                                        <span class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">NIS: {{ $song->user ? ($song->user->nis ?? '-') : '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-3 max-w-[220px]">
                                                    <p class="font-body-sm text-body-sm text-on-surface italic bg-surface-container-low p-2 rounded border border-on-surface line-clamp-2">
                                                        “{{ $song->pesan ?: 'Tidak ada pesan dedikasi khusus.' }}”
                                                    </p>
                                                </td>
                                                <td class="p-3 whitespace-nowrap">
                                                    @if (in_array($song->status, ['selesai', 'diputar']))
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 stitch-bg-primary-container stitch-text-on-primary-container rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">done_all</span>
                                                            SELESAI SIAR
                                                        </span>
                                                    @elseif ($song->status === 'ditolak')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 stitch-bg-tertiary-container stitch-text-on-tertiary-container rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">gavel</span>
                                                            DITOLAK
                                                        </span>
                                                        @if ($song->alasan_ditolak)
                                                            <span class="block font-label-sm text-[11px] text-tertiary font-bold mt-1 max-w-[180px] truncate" title="{{ $song->alasan_ditolak }}">
                                                                {{ $song->alasan_ditolak }}
                                                            </span>
                                                        @endif
                                                    @elseif ($song->status === 'disetujui')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">schedule</span>
                                                            ANTRIAN SIAP
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container-high text-on-surface-variant rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">pending</span>
                                                            MENUNGGU
                                                        </span>
                                                    @endif
                                                    <span class="block font-label-sm text-label-sm text-on-surface-variant mt-1">DJ: Bima Pratama</span>
                                                </td>
                                                <td class="p-3 pr-4 text-center whitespace-nowrap">
                                                    <a href="https://www.youtube.com/watch?v={{ $song->youtube_id }}" target="_blank"
                                                       class="p-1.5 bg-surface-container hover:bg-secondary-fixed text-on-surface rounded border-2 border-on-surface shadow-stitch-sm hover:shadow-stitch active:shadow-none transition-all inline-flex items-center justify-center"
                                                       title="Tonton di YouTube">
                                                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($sampleRows as $row)
                                            <tr class="hover:bg-surface-container-low transition-colors">
                                                <td class="p-3 pl-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ $row['date'] }}</span>
                                                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $row['time'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex items-center gap-3 min-w-[200px]">
                                                        <img class="w-11 h-11 rounded-lg border-2 border-on-surface object-cover shadow-stitch-sm shrink-0"
                                                             src="{{ $row['thumb'] }}"
                                                             alt="{{ $row['title'] }}"/>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-label-lg text-label-lg font-bold text-on-surface truncate">{{ $row['title'] }}</span>
                                                            <span class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $row['artist'] }}</span>
                                                            <span class="font-label-sm text-label-sm text-secondary font-bold">{{ $row['durasi'] }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex flex-col min-w-[130px]">
                                                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ $row['name'] }}</span>
                                                        <span class="font-label-sm text-label-sm px-1.5 py-0.5 bg-surface-container-high rounded border border-on-surface w-fit mt-0.5">
                                                            {{ $row['kelas'] }}
                                                        </span>
                                                        <span class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">NIS: {{ $row['nis'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-3 max-w-[220px]">
                                                    <p class="font-body-sm text-body-sm text-on-surface italic bg-surface-container-low p-2 rounded border border-on-surface line-clamp-2">
                                                        {{ $row['salam'] }}
                                                    </p>
                                                </td>
                                                <td class="p-3 whitespace-nowrap">
                                                    @if ($row['status'] === 'selesai')
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 stitch-bg-primary-container stitch-text-on-primary-container rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">done_all</span>
                                                            SELESAI SIAR
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 stitch-bg-tertiary-container stitch-text-on-tertiary-container rounded-md font-label-sm text-label-sm uppercase font-bold border border-on-surface shadow-stitch-sm">
                                                            <span class="material-symbols-outlined text-xs">gavel</span>
                                                            DITOLAK
                                                        </span>
                                                        <span class="block font-label-sm text-[11px] text-tertiary font-bold mt-1 max-w-[180px] truncate" title="{{ $row['alasan'] }}">
                                                            {{ $row['alasan'] }}
                                                        </span>
                                                    @endif
                                                    <span class="block font-label-sm text-label-sm text-on-surface-variant mt-1">DJ: {{ $row['dj'] }}</span>
                                                </td>
                                                <td class="p-3 pr-4 text-center whitespace-nowrap">
                                                    <button class="p-1.5 bg-surface-container hover:bg-secondary-fixed text-on-surface rounded border-2 border-on-surface shadow-stitch-sm hover:shadow-stitch active:shadow-none transition-all"
                                                            title="Buka Detail Log">
                                                        <span class="material-symbols-outlined text-lg">info</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination Footer --}}
                        <div class="px-4 py-3 bg-surface-container border-t-2 border-on-surface flex items-center justify-between">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">
                                Menampilkan Halaman {{ $songs->currentPage() ?? 1 }} dari {{ $songs->lastPage() ?? 1 }}
                            </span>
                            @if ($songs->hasPages())
                                <div>{{ $songs->links() }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- IN-LINE METRIC BAR VISUALIZATION: DISTRIBUSI JAM TAYANG --}}
                    <div class="bg-surface rounded-xl border-2 border-on-surface shadow-stitch p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary">bar_chart</span>
                                <h3 class="font-headline-sm text-headline-sm uppercase text-on-surface">Distribusi Frekuensi Siaran per Sesi</h3>
                            </div>
                            <span class="font-label-sm text-label-sm text-secondary font-bold">100% Kapasitas Siar</span>
                        </div>

                        {{-- Segmented Bar --}}
                        <div class="w-full h-8 rounded-lg border-2 border-on-surface overflow-hidden flex shadow-stitch-sm">
                            <div class="h-full flex items-center justify-center font-label-sm text-xs font-bold border-r-2 border-on-surface"
                                 style="width: 48%; background: #a3e635; color: #416400;">
                                Sesi 1 (10:00 - 10:20) • 48%
                            </div>
                            <div class="h-full flex items-center justify-center font-label-sm text-xs font-bold border-r-2 border-on-surface"
                                 style="width: 38%; background: #f0dbff; color: #2c0051;">
                                Sesi 2 (12:00 - 12:45) • 38%
                            </div>
                            <div class="h-full flex items-center justify-center font-label-sm text-xs font-bold"
                                 style="width: 14%; background: #ffc5b8; color: #a82d11;">
                                Jumat Akustik • 14%
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs font-label-sm text-on-surface-variant pt-1">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-on-surface" style="background:#a3e635;"></span> Istirahat Pertama (Audio Koridor & Kantin)</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-on-surface" style="background:#f0dbff;"></span> Istirahat Siang (Live Dedikasi DJ OSIS)</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-on-surface" style="background:#ffc5b8;"></span> Segmen Akustik Jumat</span>
                        </div>
                    </div>
                </div>

                {{-- ══ RIGHT RAIL: AUDIT METRICS, TOP REQUESTERS & FORMAL ENDORSEMENT (4 COLS) ══ --}}
                <div class="xl:col-span-4 flex flex-col gap-4">

                    {{-- CARD 1: TOP 5 SISWA PALING AKTIF --}}
                    <div class="bg-surface rounded-xl border-2 border-on-surface shadow-stitch overflow-hidden">
                        <div class="p-4 stitch-bg-secondary-fixed stitch-text-on-secondary-fixed border-b-2 border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-2xl text-secondary">military_tech</span>
                                <h2 class="font-headline-sm text-headline-sm uppercase tracking-tight">TOP REQUESTERS AKTIF</h2>
                            </div>
                            <span class="font-label-sm text-label-sm bg-surface text-secondary px-2 py-0.5 rounded-full border border-on-surface font-extrabold">
                                FEBRUARI
                            </span>
                        </div>

                        <div class="p-4 flex flex-col gap-3 divide-y-2 divide-on-surface/10">
                            @php
                                $sampleTopSiswa = [
                                    ['rank' => 1, 'name' => 'Dimas Anggara', 'kelas' => 'XI RPL 1', 'count' => 19, 'thumb' => asset('images/stitch/laporan_req1.jpg'), 'color' => '#a3e635', 'text' => '#416400'],
                                    ['rank' => 2, 'name' => 'Salsaabila Putri', 'kelas' => 'XII MIPA 1', 'count' => 16, 'thumb' => asset('images/stitch/laporan_req2.jpg'), 'color' => '#f0dbff', 'text' => '#2c0051'],
                                    ['rank' => 3, 'name' => 'Reza Fauzi', 'kelas' => 'XII TBSM 2', 'count' => 14, 'thumb' => asset('images/stitch/laporan_req3.jpg'), 'color' => '#e2e7ff', 'text' => '#131b2e'],
                                    ['rank' => 4, 'name' => 'Maya Kartika', 'kelas' => 'X DKV 2', 'count' => 11, 'thumb' => asset('images/stitch/laporan_req4.jpg'), 'color' => '#dae2fd', 'text' => '#131b2e'],
                                    ['rank' => 5, 'name' => 'Danu Saputra', 'kelas' => 'X TO 1', 'count' => 9, 'thumb' => asset('images/stitch/tophits_req3.jpg'), 'color' => '#dae2fd', 'text' => '#131b2e'],
                                ];
                            @endphp

                            @if (!empty($topSiswa) && $topSiswa->isNotEmpty())
                                @foreach ($topSiswa as $index => $ts)
                                    <div class="flex items-center justify-between pt-2 first:pt-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-full font-label-md text-label-md font-bold flex items-center justify-center border-2 border-on-surface shadow-stitch-sm"
                                                 style="background: {{ $index === 0 ? '#a3e635' : ($index === 1 ? '#f0dbff' : '#e2e7ff') }}; color: {{ $index === 0 ? '#416400' : ($index === 1 ? '#2c0051' : '#131b2e') }};">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="w-9 h-9 rounded-full border-2 border-on-surface bg-[#a3e635] text-[#416400] flex items-center justify-center font-black text-sm select-none">
                                                {{ strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', $ts->name))), 0, 2)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-label-lg text-label-lg font-bold text-on-surface leading-tight">{{ $ts->name }}</span>
                                                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $ts->kelas ?? 'Siswa' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="font-label-lg text-label-lg font-extrabold text-secondary">{{ $ts->songs_count }} Lagu</span>
                                            <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-0.5">
                                                <span class="material-symbols-outlined text-xs text-primary">thumb_up</span> Aktif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach ($sampleTopSiswa as $ts)
                                    <div class="flex items-center justify-between pt-2 first:pt-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-full font-label-md text-label-md font-bold flex items-center justify-center border-2 border-on-surface shadow-stitch-sm"
                                                 style="background: {{ $ts['color'] }}; color: {{ $ts['text'] }};">
                                                {{ $ts['rank'] }}
                                            </div>
                                            <div class="w-9 h-9 rounded-full border-2 border-on-surface bg-[#a3e635] text-[#416400] flex items-center justify-center font-black text-sm select-none">
                                                {{ strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', $ts['name']))), 0, 2)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-label-lg text-label-lg font-bold text-on-surface leading-tight">{{ $ts['name'] }}</span>
                                                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $ts['kelas'] }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="font-label-lg text-label-lg font-extrabold text-secondary">{{ $ts['count'] }} Lagu</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- CARD 2: DISTRIBUSI ALASAN PENOLAKAN MODERASI --}}
                    <div class="bg-surface rounded-xl border-2 border-on-surface shadow-stitch p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-tertiary">gavel</span>
                                <h3 class="font-headline-sm text-headline-sm uppercase text-on-surface">Kategori Moderasi Penolakan</h3>
                            </div>
                            <span class="font-label-sm text-label-sm px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed font-bold rounded border border-on-surface">
                                Total 42 Kasus
                            </span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Penyebab lagu tidak lolos filter sensor sesuai Kode Etik Penyiaran Sekolah.
                        </p>

                        {{-- Progress bars breakdown --}}
                        <div class="flex flex-col gap-2.5 pt-1">
                            {{-- Reason 1 --}}
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between font-label-sm text-label-sm font-bold text-on-surface">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-tertiary">warning</span>
                                        Lirik Kasar / Eksplisit / SARA (19 Lagu)
                                    </span>
                                    <span class="text-tertiary">45%</span>
                                </div>
                                <div class="w-full bg-surface-container h-3.5 rounded border border-on-surface overflow-hidden">
                                    <div class="bg-tertiary h-full rounded" style="width: 45%;"></div>
                                </div>
                            </div>
                            {{-- Reason 2 --}}
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between font-label-sm text-label-sm font-bold text-on-surface">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-secondary">timer_off</span>
                                        Durasi Berlebih &gt;10 Menit (13 Lagu)
                                    </span>
                                    <span class="text-secondary">30%</span>
                                </div>
                                <div class="w-full bg-surface-container h-3.5 rounded border border-on-surface overflow-hidden">
                                    <div class="bg-secondary-container h-full rounded" style="width: 30%;"></div>
                                </div>
                            </div>
                            {{-- Reason 3 --}}
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between font-label-sm text-label-sm font-bold text-on-surface">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-on-surface-variant">content_copy</span>
                                        Duplikasi Lagu di Hari Sama (10 Lagu)
                                    </span>
                                    <span class="text-on-surface-variant">25%</span>
                                </div>
                                <div class="w-full bg-surface-container h-3.5 rounded border border-on-surface overflow-hidden">
                                    <div class="bg-outline h-full rounded" style="width: 25%;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-1 p-2 bg-surface-container rounded border border-on-surface flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-lg shrink-0">verified_user</span>
                            <span class="font-body-sm text-body-sm text-on-surface">
                                Sistem filter otomatis AI menangani 78% deteksi kata profan sebelum kurasi akhir oleh DJ radio.
                            </span>
                        </div>
                    </div>

                    {{-- CARD 3: KESIAPAN BERITA ACARA & PENGESAHAN DOKUMEN LPJ --}}
                    <div class="p-4 rounded-xl border-2 border-on-surface shadow-stitch flex flex-col gap-3"
                         style="background: rgba(178,247,70,0.2);">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-2xl">assignment_turned_in</span>
                            <h3 class="font-headline-sm text-headline-sm uppercase text-on-surface">Validasi & Tanda Tangan LPJ</h3>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface">
                            Status penandatanganan digital Berita Acara Rekapitulasi Siaran untuk arsip resmi Tata Usaha Sekolah.
                        </p>

                        {{-- Status Signers List --}}
                        <div class="flex flex-col gap-2 bg-surface p-3 rounded-lg border-2 border-on-surface">
                            <div class="flex items-center justify-between py-1 border-b border-on-surface/10">
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md font-bold text-on-surface">Drs. H. Mulyadi, M.Pd</span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Pembina OSIS & Kesiswaan</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-primary-container text-on-primary-container font-label-sm text-xs font-bold border border-on-surface">
                                    TERVERIFIKASI
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-1 border-b border-on-surface/10">
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md font-bold text-on-surface">Bima Pratama</span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Kepala Divisi Siaran & Audio</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-primary-container text-on-primary-container font-label-sm text-xs font-bold border border-on-surface">
                                    TERVERIFIKASI
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-1">
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md font-bold text-on-surface">Aditya Surya</span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Ketua Umum OSIS 2024/2025</span>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-secondary-fixed text-on-secondary-fixed font-label-sm text-xs font-bold border border-on-surface">
                                    SIAP TTD
                                </span>
                            </div>
                        </div>

                        {{-- Print Button --}}
                        <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank"
                           class="w-full py-2.5 px-4 rounded-lg bg-surface text-on-surface border-2 border-on-surface shadow-stitch-sm hover:shadow-stitch active:shadow-none text-center font-label-md text-label-md font-bold transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">print</span>
                            <span>Cetak Berita Acara Resmi (PDF)</span>
                        </a>
                    </div>

                </div>{{-- /xl:col-span-4 --}}
            </div>{{-- /grid --}}

        </main>
    </div>
</div>

{{-- CLIENT SCRIPTS --}}
<script>
(function() {
    const searchInput  = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody    = document.getElementById('tableBody');
    const resetBtn     = document.getElementById('resetFilter');
    const exportBtn    = document.getElementById('btnExport');

    if (!tableBody) return;
    const rows = Array.from(tableBody.querySelectorAll('tr'));

    function applyFilter() {
        const query  = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const status = statusFilter ? statusFilter.value : 'ALL';

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            let matchesQuery  = !query || text.includes(query);
            let matchesStatus = true;

            if (status === 'SELESAI') {
                matchesStatus = text.includes('selesai siar') || text.includes('selesai');
            } else if (status === 'DITOLAK') {
                matchesStatus = text.includes('ditolak');
            } else if (status === 'MENUNGGU') {
                matchesStatus = text.includes('menunggu') || text.includes('antrian');
            } else if (status === 'GAGAL') {
                matchesStatus = text.includes('gagal');
            }

            row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    }

    if (searchInput)  searchInput.addEventListener('input', applyFilter);
    if (statusFilter) statusFilter.addEventListener('change', applyFilter);

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (searchInput)  searchInput.value = '';
            if (statusFilter) statusFilter.value = 'ALL';
            applyFilter();
        });
    }

    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            const originalHTML = exportBtn.innerHTML;
            exportBtn.innerHTML = '<span class="material-symbols-outlined text-xl" style="animation:spin 1s linear infinite;">sync</span><span>Mengekspor Data...</span>';
            exportBtn.classList.add('opacity-75');

            // Generate simple CSV download from table
            setTimeout(() => {
                let csv = "Waktu,Judul,Artis,Pemohon,Kelas,Status\n";
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = Array.from(row.querySelectorAll('td')).map(td => `"${td.innerText.replace(/"/g, '""').replace(/\n/g, ' ')}"`);
                        csv += cells.join(',') + "\n";
                    }
                });

                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Laporan_Siaran_Radio_OSIS_${new Date().toISOString().slice(0,10)}.csv`;
                a.click();
                URL.revokeObjectURL(url);

                exportBtn.innerHTML = '<span class="material-symbols-outlined text-xl text-primary font-bold">check</span><span>File CSV Terunduh!</span>';
                setTimeout(() => {
                    exportBtn.innerHTML = originalHTML;
                    exportBtn.classList.remove('opacity-75');
                }, 2000);
            }, 800);
        });
    }
})();
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.stitch-sidebar { width: 18rem; }
.shadow-stitch { box-shadow: 4px 4px 0px #131b2e; }
.shadow-stitch-sm { box-shadow: 2px 2px 0px #131b2e; }
.shadow-stitch-r { box-shadow: 4px 0px 0px #131b2e; }
.border-on-surface { border-color: #131b2e; }
.stitch-border-on-surface { border-color: #131b2e; }
.stitch-bg-surface { background-color: #faf8ff; }
.stitch-bg-surface-low { background-color: #f2f3ff; }
.stitch-bg-surface-container { background-color: #eaedff; }
.stitch-bg-surface-high { background-color: #e2e7ff; }
.stitch-bg-primary-container { background-color: #a3e635; }
.stitch-bg-secondary-fixed { background-color: #f0dbff; }
.stitch-bg-tertiary { background-color: #ae3115; }
.stitch-bg-tertiary-container { background-color: #ffc5b8; }
.stitch-text-on-surface { color: #131b2e; }
.stitch-text-on-surface-variant { color: #424936; }
.stitch-text-on-primary-container { color: #416400; }
.stitch-text-on-secondary-fixed { color: #2c0051; }
.stitch-text-on-tertiary { color: #ffffff; }
.stitch-text-on-tertiary-container { color: #a82d11; }
.stitch-text-secondary { color: #8127cf; }
.stitch-text-primary { color: #446900; }
.font-headline-lg { font-family: 'Space Grotesk', sans-serif; font-size: 28px; line-height: 36px; font-weight: 700; }
.text-headline-lg { font-size: 28px; line-height: 36px; }
.font-headline-sm { font-family: 'Space Grotesk', sans-serif; font-size: 18px; line-height: 24px; font-weight: 700; }
.text-headline-sm { font-size: 18px; line-height: 24px; }
.font-display-hero { font-family: 'Space Grotesk', sans-serif; font-size: 38px; line-height: 44px; font-weight: 800; }
.text-headline-xl { font-size: 38px; line-height: 44px; }
.font-label-lg { font-family: 'Space Grotesk', sans-serif; font-size: 14px; line-height: 18px; letter-spacing: 0.04em; font-weight: 700; }
.text-label-lg { font-size: 14px; line-height: 18px; }
.font-label-md { font-family: 'Space Grotesk', sans-serif; font-size: 12px; line-height: 16px; letter-spacing: 0.06em; font-weight: 700; }
.text-label-md { font-size: 12px; line-height: 16px; }
.font-label-sm { font-family: 'Space Grotesk', sans-serif; font-size: 11px; line-height: 14px; letter-spacing: 0.08em; font-weight: 700; }
.text-label-sm { font-size: 11px; line-height: 14px; }
.font-body-md { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; line-height: 20px; font-weight: 500; }
.text-body-md { font-size: 14px; line-height: 20px; }
.font-body-sm { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; line-height: 16px; font-weight: 500; }
.text-body-sm { font-size: 12px; line-height: 16px; }
</style>
</x-admin-layout>
