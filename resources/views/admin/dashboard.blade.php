<x-admin-layout>
{{--
    SELADA Broadcast Booth — Admin Landing Dashboard
    Theme: Vibrant Youth / Neo-Brutalist Stitch Design
--}}

<div class="flex h-screen bg-[#faf8ff] text-[#131b2e] font-sans overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="dashboard" />

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        {{-- Top Sticky Header Bar --}}
        <header class="h-20 bg-[#faf8ff]/90 backdrop-blur-md border-b-2 border-[#131b2e] sticky top-0 z-40 flex items-center justify-between px-6 shadow-[0px_3px_0px_#131b2e]">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-[#f0dbff] text-[#2c0051] border-2 border-[#131b2e] rounded-full shadow-[2px_2px_0px_#131b2e]">
                    <span class="material-symbols-outlined text-base">alarm</span>
                    <span class="text-xs font-bold font-mono">Istirahat 1: Sisa 18 Menit</span>
                </div>
                <div class="hidden xl:flex items-center gap-1.5 px-2.5 py-1 bg-[#eaedff] border border-[#131b2e] rounded">
                    <span class="material-symbols-outlined text-sm text-[#446900]">mic</span>
                    <span class="text-[11px] uppercase font-black text-[#131b2e]">MIC 01 ACTIVE</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- User Profile Chip --}}
                <div class="flex items-center gap-2.5 bg-[#e2e7ff] border-2 border-[#131b2e] px-3.5 py-1.5 rounded-lg shadow-[2px_2px_0px_#131b2e]">
                    <div class="flex flex-col text-right">
                        <span class="text-xs font-black text-[#131b2e] uppercase leading-none">{{ Auth::user()->name ?? 'Bima Pratama' }}</span>
                        <span class="text-[10px] text-[#8127cf] font-bold">DJ OSIS Audio</span>
                    </div>
                    <img src="{{ asset('images/stitch/users_profile.jpg') }}"
                         onerror="this.src='{{ asset('images/stitch/profil_avatar.jpg') }}';"
                         alt="Profile" class="w-8 h-8 rounded-full object-cover border border-[#131b2e]">
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            title="Logout"
                            class="flex items-center justify-center p-2 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] text-[#131b2e] hover:bg-[#ffdad6] hover:text-[#93000a] shadow-[2px_2px_0px_#131b2e] transition-all">
                        <span class="material-symbols-outlined text-lg">power_settings_new</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- Page Body --}}
        <main class="p-6 xl:p-8 flex flex-col gap-6 max-w-7xl w-full">

            @php
                $menungguCount  = \App\Models\Song::where('status', 'menunggu')->count();
                $antrianCount   = \App\Models\Song::whereIn('status', ['disetujui', 'diputar'])->count();
                $jadwalAktif    = \App\Models\Schedule::where('is_active', true)->count();
                $unreadFeedback = \App\Models\Feedback::where('is_read', false)->count();
                $totalUsers     = \App\Models\User::count();
                $activeSchedule = \App\Models\Schedule::all()->first(fn($s) => $s->isNowActive());
            @endphp

            {{-- 1. Hero Header Banner --}}
            <div class="relative overflow-hidden bg-[#eaedff] border-2 border-[#131b2e] rounded-2xl p-6 md:p-8 shadow-[5px_5px_0px_#131b2e]">
                <div class="absolute -right-12 -top-12 w-64 h-64 bg-[#a3e635]/25 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex flex-col gap-2 max-w-2xl">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#ae3115] text-white text-xs font-mono font-bold tracking-wider uppercase border border-[#131b2e]">
                                Live Studio Cockpit
                            </span>
                            <span class="text-xs font-mono font-bold text-[#8127cf]">SMAN 1 BROADCASTING SYSTEM</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-[#131b2e] uppercase tracking-tight font-display">
                            SELADA Broadcast Booth
                        </h1>
                        <p class="text-sm text-[#424936] leading-relaxed">
                            Pusat kendali operasional radio sekolah OSIS. Kelola siaran jam istirahat, kurasi lagu dari siswa, pantau antrian live, dan kelola aspirasi musik secara terintegrasi.
                        </p>
                    </div>

                    {{-- Main Quick Action Button --}}
                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        <a href="{{ route('admin.player.index') }}"
                           class="flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] font-black text-sm uppercase tracking-wider shadow-[4px_4px_0px_#131b2e] hover:bg-[#b2f746] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            <span class="material-symbols-outlined text-2xl">graphic_eq</span>
                            <span>Buka DJ Player / Studio</span>
                        </a>
                        <a href="{{ route('admin.songs.index') }}"
                           class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white text-[#131b2e] border-2 border-[#131b2e] font-bold text-xs uppercase tracking-wider shadow-[3px_3px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all">
                            <span class="material-symbols-outlined text-lg text-[#8127cf]">playlist_add_check</span>
                            <span>Moderasi ({{ $menungguCount }})</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. KPI Metrics Bento Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Metric 1: Menunggu Moderasi --}}
                <a href="{{ route('admin.songs.index') }}"
                   class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Menunggu Moderasi</span>
                        <span class="text-2xl font-black text-[#ae3115] font-display group-hover:scale-105 transition-transform">{{ $menungguCount }}</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Perlu ditinjau</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#ffdad6] border border-[#131b2e] flex items-center justify-center text-[#ba1a1a]">
                        <span class="material-symbols-outlined text-2xl">pending_actions</span>
                    </div>
                </a>

                {{-- Metric 2: Antrian Siaran --}}
                <a href="{{ route('admin.player.index') }}"
                   class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Antrian Siaran</span>
                        <span class="text-2xl font-black text-[#446900] font-display group-hover:scale-105 transition-transform">{{ $antrianCount }}</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Siap diputar</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#b2f746] border border-[#131b2e] flex items-center justify-center text-[#121f00]">
                        <span class="material-symbols-outlined text-2xl">queue_music</span>
                    </div>
                </a>

                {{-- Metric 3: Jadwal Istirahat --}}
                <a href="{{ route('admin.schedules.index') }}"
                   class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Jadwal Istirahat</span>
                        <span class="text-2xl font-black text-[#8127cf] font-display group-hover:scale-105 transition-transform">{{ $jadwalAktif }} Sesi</span>
                        <span class="text-[11px] text-[#727a64] font-medium">{{ $activeSchedule ? 'Sedang Berlangsung' : 'Semua Siap' }}</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#f0dbff] border border-[#131b2e] flex items-center justify-center text-[#2c0051]">
                        <span class="material-symbols-outlined text-2xl">schedule</span>
                    </div>
                </a>

                {{-- Metric 4: Aspirasi Siswa --}}
                <a href="{{ route('admin.feedbacks.index') }}"
                   class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Kotak Aspirasi</span>
                        <span class="text-2xl font-black text-[#131b2e] font-display group-hover:scale-105 transition-transform">{{ $unreadFeedback }} Baru</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Kritik &amp; Saran</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaedff] border border-[#131b2e] flex items-center justify-center text-[#8127cf]">
                        <span class="material-symbols-outlined text-2xl">forum</span>
                    </div>
                </a>
            </div>

            {{-- 3. Navigation Grid (Semua Menu Admin Selada) --}}
            <div class="flex flex-col gap-3">
                <h3 class="text-lg font-black text-[#131b2e] uppercase tracking-tight font-display">
                    Akses Cepat Modul Studio
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    {{-- Card 1: DJ Player --}}
                    <a href="{{ route('admin.player.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#a3e635] text-[#131b2e] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">graphic_eq</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">DJ Player / Studio</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Pemutar siaran YouTube live, mixing audio, dan kontrol mikrofon.</p>
                        </div>
                    </a>

                    {{-- Card 2: Moderasi Lagu --}}
                    <a href="{{ route('admin.songs.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">playlist_add_check</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Moderasi Lagu</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Setujui, tolak, atau atur urutan lagu yang diajukan siswa.</p>
                        </div>
                    </a>

                    {{-- Card 3: Jadwal Istirahat --}}
                    <a href="{{ route('admin.schedules.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#eaedff] text-[#446900] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">schedule</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Jadwal Istirahat</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Atur slot waktu siaran otomatis speaker sekolah.</p>
                        </div>
                    </a>

                    {{-- Card 4: Kotak Aspirasi --}}
                    <a href="{{ route('admin.feedbacks.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#ffdad2] text-[#3d0600] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">forum</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Kotak Aspirasi</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Tinjau kritik, saran lagu, dan delegasi disposisi ke tim OSIS.</p>
                        </div>
                    </a>

                    {{-- Card 5: Laporan Siaran --}}
                    <a href="{{ route('admin.reports.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#dae2fd] text-[#131b2e] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">bar_chart</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Laporan Siaran</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Rekapitulasi kuota, statistik genre, dan cetak berita acara resmi.</p>
                        </div>
                    </a>

                    {{-- Card 6: Manajemen Pengguna --}}
                    <a href="{{ route('admin.users.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#eaedff] text-[#8127cf] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">group</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Manajemen Pengguna</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Kelola akun siswa, registrasi operator, dan bulk import via TSV.</p>
                        </div>
                    </a>

                    {{-- Card 7: Pengaturan Sistem --}}
                    <a href="{{ route('admin.settings.index') }}"
                       class="p-5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-[#b2f746] text-[#121f00] border border-[#131b2e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">settings</span>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-bold text-sm text-[#131b2e]">Pengaturan Sistem</h4>
                            <p class="text-xs text-[#424936] mt-0.5">Filter kata terlarang (blacklist), kuota harian, dan kebijakan radio.</p>
                        </div>
                    </a>
                </div>
            </div>

        </main>
    </div>

</div>
</x-admin-layout>
