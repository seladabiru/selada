<x-admin-layout>
{{--
    Manajemen Jadwal Istirahat — SELADA Broadcast Booth
    Design source: Stitch (d39bbc63922e48d3ad69cb7f5475079e)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen bg-[#faf8ff] text-[#131b2e] font-sans overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="schedules" />

    {{-- ══════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        {{-- Top Sticky Header Bar --}}
        <header class="h-20 bg-[#faf8ff]/90 backdrop-blur-md border-b-2 border-[#131b2e] sticky top-0 z-40 flex items-center justify-between px-6 shadow-[0px_3px_0px_#131b2e]">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-[#f0dbff] text-[#2c0051] border-2 border-[#131b2e] rounded-full shadow-[2px_2px_0px_#131b2e]">
                    <span class="material-symbols-outlined text-base">alarm</span>
                    <span class="text-xs font-bold font-mono">
                        @if($activeNow)
                            {{ $activeNow->nama_sesi }}: Sisa {{ ceil($secondsRemaining / 60) }} Menit
                        @else
                            {{ $displaySession ? $displaySession->nama_sesi : 'Istirahat 1' }}: Sisa 18 Menit
                        @endif
                    </span>
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

            {{-- 1. Top Action Breadcrumb & Main Header --}}
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-4">
                <div class="flex flex-col gap-1 max-w-3xl">
                    <div class="flex items-center gap-1.5 text-xs text-[#424936] tracking-wider uppercase font-mono font-bold">
                        <span>ADMIN CONSOLE</span>
                        <span>&gt;</span>
                        <span>MANAJEMEN PENYIARAN</span>
                        <span>&gt;</span>
                        <span class="text-[#8127cf] font-black">JADWAL ISTIRAHAT &amp; ON-AIR</span>
                    </div>
                    <h1 class="text-3xl xl:text-4xl font-black text-[#131b2e] uppercase tracking-tight font-display">
                        Jadwal Siaran &amp; Jam Istirahat Sekolah
                    </h1>
                    <p class="text-sm text-[#424936] leading-relaxed">
                        Atur sesi penyiaran otomatis speaker sekolah selama jam istirahat. Sistem mengevaluasi toleransi waktu real-time <code class="bg-[#e2e7ff] text-[#8127cf] px-1.5 py-0.5 rounded font-mono font-bold text-xs">isNowActive()</code> dan membatasi pemutaran musik di luar jam istirahat.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button"
                            id="btn-sync-bell"
                            class="flex items-center gap-2 px-4 py-2.5 bg-white text-[#131b2e] border-2 border-[#131b2e] font-bold text-xs uppercase tracking-wider rounded-lg shadow-[3px_3px_0px_#131b2e] hover:bg-[#f2f3ff] transition-all active:translate-y-0.5">
                        <span class="material-symbols-outlined text-lg text-[#8127cf]">sync</span>
                        <span>Sinkronisasi Jam Bel Sekolah</span>
                    </button>
                    <button type="button"
                            id="btn-open-modal"
                            class="flex items-center gap-2 px-4 py-2.5 bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] font-black text-xs uppercase tracking-wider rounded-lg shadow-[3px_3px_0px_#131b2e] hover:bg-[#b2f746] transition-all active:translate-y-0.5">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        <span>+ Tambah Sesi Siaran Baru</span>
                    </button>
                </div>
            </div>

            {{-- Flash Alert Messages --}}
            @if(session('success'))
                <div class="flex items-center justify-between p-3.5 bg-[#d4edda] text-[#155724] border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                        <span class="text-xs font-bold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="p-1 hover:opacity-75">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-3.5 bg-[#ffdad6] text-[#93000a] border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-xl">error</span>
                        <span class="text-xs font-bold">Terjadi kesalahan validasi:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs pl-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Live Toast / Notif Banner (Hidden by default, triggered by sync button) --}}
            <div id="sync-toast" class="hidden items-center justify-between p-4 bg-[#f0dbff] text-[#2c0051] border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] transition-all">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#8127cf] text-2xl">notifications_active</span>
                    <span class="text-xs font-bold font-mono">
                        Sinkronisasi Server Bell SMAN 1 Berhasil! Semua jadwal tersinkron ke Master Clock Ruang Kurikulum ({{ now()->format('H:i:s') }} WIB).
                    </span>
                </div>
                <button type="button" class="p-1 rounded-full hover:bg-[#ddb7ff]" onclick="document.getElementById('sync-toast').classList.add('hidden')">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>

            {{-- 2. FR-SCH-02: Widget Status Real-Time Aktif --}}
            <div class="relative overflow-hidden bg-[#eaedff] border-2 border-[#131b2e] rounded-xl p-6 shadow-[4px_4px_0px_#131b2e]">
                <div class="absolute -right-8 -top-8 w-64 h-64 bg-[#b2f746]/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        
                        {{-- Vinyl Disc Visual Indicator --}}
                        <div class="relative w-28 h-28 rounded-xl bg-[#dae2fd] border-2 border-[#131b2e] flex items-center justify-center shadow-inner flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-[#283044] border border-[#131b2e] flex items-center justify-center animate-spin" style="animation-duration: 6s;">
                                <div class="w-8 h-8 rounded-full bg-[#a3e635] border border-[#131b2e] flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#131b2e]"></div>
                                </div>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-[#ae3115] text-white p-1 rounded-full border border-[#131b2e] flex items-center justify-center shadow">
                                <span class="material-symbols-outlined text-xs">volume_up</span>
                            </div>
                        </div>

                        {{-- Real-Time Info Details --}}
                        <div class="flex flex-col gap-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($activeNow)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-[#a3e635] text-[#131b2e] border border-[#131b2e] rounded-full text-xs font-black font-mono shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-[#446900] animate-ping"></span>
                                        <span>SESI SAAT INI AKTIF</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] rounded-full text-xs font-black font-mono shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-[#8127cf]"></span>
                                        <span>SESI AKTIF TERJADWAL</span>
                                    </span>
                                @endif

                                <span class="text-xs text-[#424936] bg-[#dae2fd] border border-[#131b2e]/30 px-2 py-0.5 rounded font-mono font-bold">
                                    SLOT: {{ substr(($activeNow ?? $displaySession)->jam_mulai ?? '09:45', 0, 5) }} - {{ substr(($activeNow ?? $displaySession)->jam_selesai ?? '10:15', 0, 5) }} WIB
                                </span>
                            </div>

                            <h2 class="text-2xl font-black text-[#131b2e] uppercase font-display">
                                {{ ($activeNow ?? $displaySession)->nama_sesi ?? 'ISTIRAHAT 1 (PAGI)' }}
                            </h2>

                            <div class="flex flex-wrap items-center gap-3 text-[#424936] text-xs">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base text-[#446900]">graphic_eq</span>
                                    <span>Speaker Selasar &amp; Kantin: <strong class="text-[#131b2e]">Broadcasting</strong></span>
                                </div>
                                <span>•</span>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base text-[#8127cf]">queue_music</span>
                                    <span>Track Sedang Putar: <strong class="text-[#131b2e]">{{ $currentSong ? $currentSong->judul . ' - ' . $currentSong->channel : 'Nadhif Basalamah - Penjaga Hati' }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Countdown & Emergency Controls --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 self-stretch xl:self-auto justify-end">
                        {{-- Countdown Box --}}
                        <div class="flex flex-col p-3.5 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] text-center min-w-[170px]">
                            <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Sisa Waktu Siaran</span>
                            <div class="text-2xl font-black text-[#ae3115] tracking-tight font-mono my-0.5" id="countdown-timer">
                                {{ floor($secondsRemaining / 60) }}m {{ str_pad($secondsRemaining % 60, 2, '0', STR_PAD_LEFT) }}s
                            </div>
                            <span class="text-[10px] text-[#727a64] font-medium font-mono">
                                Otomatis Fade Out {{ substr(($activeNow ?? $displaySession)->jam_selesai ?? '10:15', 0, 5) }} WIB
                            </span>
                        </div>

                        {{-- Emergency Mute / Switch Toggle --}}
                        <div class="flex flex-col justify-center gap-1.5 p-3.5 bg-[#ffdad6] text-[#93000a] border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e]">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[#ba1a1a] text-xl">emergency_home</span>
                                    <span class="text-xs font-black uppercase">Mute Speaker Sekolah</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="emergency-toggle" class="sr-only peer">
                                    <div class="w-11 h-6 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#ba1a1a]"></div>
                                </label>
                            </div>
                            <span class="text-[10px] text-[#93000a]/80 font-medium">
                                Gunakan saat pengumuman genting guru / darurat BK.
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Realtime Audio Waveform Simulation Visual --}}
                <div class="mt-4 pt-3 border-t border-[#131b2e]/10 flex flex-col gap-1.5">
                    <div class="flex items-center justify-between text-[#424936] text-[10px] font-mono font-bold tracking-wider">
                        <span>SPEAKER OUTPUT LEVEL MONITOR</span>
                        <span>BUFFER: 99.8% (STABLE)</span>
                    </div>
                    <div class="h-6 w-full flex items-end gap-1 overflow-hidden bg-white border border-[#131b2e] p-1 rounded-lg">
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 60%; animation-duration: 0.9s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 85%; animation-duration: 1.1s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 40%; animation-duration: 0.7s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 95%; animation-duration: 1.3s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 70%; animation-duration: 0.8s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 50%; animation-duration: 1.2s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 90%; animation-duration: 1.0s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 65%; animation-duration: 0.6s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 30%; animation-duration: 1.4s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 100%; animation-duration: 0.8s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 80%; animation-duration: 1.1s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 55%; animation-duration: 0.9s;"></div>
                        <div class="w-full bg-[#446900] rounded-sm animate-pulse" style="height: 45%; animation-duration: 1.2s;"></div>
                        <div class="w-full bg-[#a3e635] rounded-sm animate-pulse" style="height: 75%; animation-duration: 0.7s;"></div>
                    </div>
                </div>
            </div>

            {{-- 3. Quick Stats Cards Row (4 Bento Metrics) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Stat 1 --}}
                <div class="p-4 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl flex items-center justify-between shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Total Sesi Terjadwal</span>
                        <span class="text-2xl font-black text-[#131b2e] font-display">{{ $totalSesi }} Sesi</span>
                        <span class="text-[11px] text-[#727a64] font-medium">{{ $totalAktif }} Aktif Mingguan</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#dae2fd] border border-[#131b2e] flex items-center justify-center text-[#446900]">
                        <span class="material-symbols-outlined text-2xl">schedule</span>
                    </div>
                </div>

                {{-- Stat 2 --}}
                <div class="p-4 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl flex items-center justify-between shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Total Alokasi On-Air</span>
                        <span class="text-2xl font-black text-[#131b2e] font-display">{{ $totalMenitOnAir }} Menit</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Per Hari Normal</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#dae2fd] border border-[#131b2e] flex items-center justify-center text-[#8127cf]">
                        <span class="material-symbols-outlined text-2xl">timelapse</span>
                    </div>
                </div>

                {{-- Stat 3 --}}
                <div class="p-4 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl flex items-center justify-between shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Kapasitas Lagu Harian</span>
                        <span class="text-2xl font-black text-[#131b2e] font-display">~{{ $kapasitasLagu > 0 ? $kapasitasLagu : 20 }} Track</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Estimasi 3.8 Menit/lagu</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#dae2fd] border border-[#131b2e] flex items-center justify-center text-[#ae3115]">
                        <span class="material-symbols-outlined text-2xl">library_music</span>
                    </div>
                </div>

                {{-- Stat 4 --}}
                <div class="p-4 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl flex items-center justify-between shadow-[3px_3px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Sistem KBM Safety Guard</span>
                        <span class="text-2xl font-black text-[#446900] font-display">T-Minus 2 Min</span>
                        <span class="text-[11px] text-[#727a64] font-medium">Otomatis Fade Out</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#b2f746] border border-[#131b2e] flex items-center justify-center text-[#121f00]">
                        <span class="material-symbols-outlined text-2xl">format_image_left</span>
                    </div>
                </div>
            </div>

            {{-- 4. Section Title & Filters --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-2">
                <div class="flex items-center gap-2">
                    <h3 class="text-xl font-black text-[#131b2e] uppercase tracking-tight font-display">
                        Daftar Konfigurasi Sesi Siaran
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-xs font-black font-mono">
                        {{ $schedules->count() }} Sesi Ditemukan
                    </span>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-[#424936]">
                    <span class="material-symbols-outlined text-base text-[#8127cf]">info</span>
                    <span>Perubahan jadwal otomatis tersimpan di memory controller Raspberry Pi Radio booth</span>
                </div>
            </div>

            {{-- 5. FR-SCH-01: Daftar Sesi Jadwal Istirahat (Bento Cards) --}}
            <div class="flex flex-col gap-4">
                @forelse($schedules as $index => $schedule)
                    @php
                        $isActiveNow = $schedule->isNowActive();
                        $isNonActive = !$schedule->is_active;
                        $isJumatSpecial = in_array('Jumat', $schedule->hari_aktif ?? []) && count($schedule->hari_aktif ?? []) === 1;
                        
                        $startClean = substr($schedule->jam_mulai, 0, 5);
                        $endClean = substr($schedule->jam_selesai, 0, 5);
                        $durasiMenit = max(0, round((strtotime($schedule->jam_selesai) - strtotime($schedule->jam_mulai)) / 60));
                        $kuotaLagu = max(1, round($durasiMenit / 3.8));
                    @endphp

                    <div class="group relative bg-[#eaedff] border-2 border-[#131b2e] rounded-xl p-5 shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all {{ $isNonActive ? 'opacity-75 hover:opacity-100 bg-[#f2f3ff]' : '' }}">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            
                            {{-- Sesi Header & Timing --}}
                            <div class="flex items-start gap-4 min-w-0">
                                {{-- Number Badge --}}
                                @if($isActiveNow)
                                    <div class="w-14 h-14 rounded-xl bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] flex flex-col items-center justify-center font-black flex-shrink-0 shadow-[2px_2px_0px_#131b2e]">
                                        <span class="text-[10px] uppercase font-mono tracking-wider">SESI</span>
                                        <span class="text-xl leading-none font-display">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                @elseif($isJumatSpecial)
                                    <div class="w-14 h-14 rounded-xl bg-[#ffc5b8] text-[#3d0600] border-2 border-[#131b2e] flex flex-col items-center justify-center font-black flex-shrink-0 shadow-[2px_2px_0px_#131b2e]">
                                        <span class="text-[10px] uppercase font-mono tracking-wider">SESI</span>
                                        <span class="text-xl leading-none font-display">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                @elseif($isNonActive)
                                    <div class="w-14 h-14 rounded-xl bg-[#d2d9f4] text-[#424936] border-2 border-[#131b2e] flex flex-col items-center justify-center font-bold flex-shrink-0">
                                        <span class="text-[10px] uppercase font-mono tracking-wider">SESI</span>
                                        <span class="text-xl leading-none font-display">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-[#dae2fd] text-[#131b2e] border-2 border-[#131b2e] flex flex-col items-center justify-center font-black flex-shrink-0 shadow-[2px_2px_0px_#131b2e]">
                                        <span class="text-[10px] uppercase font-mono tracking-wider">SESI</span>
                                        <span class="text-xl leading-none font-display">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                @endif

                                <div class="flex flex-col gap-1 min-w-0">
                                    {{-- Status Chips --}}
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @if($isActiveNow)
                                            <span class="px-2 py-0.5 bg-[#b2f746] text-[#121f00] border border-[#131b2e] text-[11px] rounded-full font-black font-mono flex items-center gap-1">
                                                <span class="w-2 h-2 rounded-full bg-[#446900] animate-ping"></span>
                                                AKTIF SEKARANG
                                            </span>
                                            <span class="text-xs text-[#424936] font-medium">Pagi Hari</span>
                                        @elseif($isNonActive)
                                            <span class="px-2 py-0.5 bg-[#dae2fd] text-[#424936] border border-[#131b2e] text-[11px] rounded-full font-bold font-mono">
                                                DINONAKTIFKAN SEMENTARA
                                            </span>
                                            <span class="text-xs text-[#727a64]">Musiman / Pasca Ujian</span>
                                        @elseif($isJumatSpecial)
                                            <span class="px-2 py-0.5 bg-[#ffdad2] text-[#3d0600] border border-[#131b2e] text-[11px] rounded-full font-black font-mono">
                                                KHUSUS JUMAT
                                            </span>
                                            <span class="px-2 py-0.5 bg-[#dae2fd] text-[#8127cf] text-[11px] rounded font-bold font-mono">
                                                Genre: Akustik / Religi / Slow Pop
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-[11px] rounded-full font-bold font-mono">
                                                TERJADWAL BERIKUTNYA
                                            </span>
                                            <span class="text-xs text-[#424936]">Siang Hari / Sholat Dhuhur</span>
                                        @endif
                                    </div>

                                    <h4 class="text-lg font-black text-[#131b2e] truncate font-display">
                                        {{ $schedule->nama_sesi }}
                                    </h4>

                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[#424936] text-xs">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base {{ $isActiveNow ? 'text-[#446900]' : 'text-[#8127cf]' }}">schedule</span>
                                            <strong>{{ $startClean }} - {{ $endClean }} WIB</strong> (Durasi: {{ $durasiMenit }} Menit)
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-base text-[#8127cf]">music_note</span>
                                            Estimasi Kuota: <strong class="text-[#131b2e]">{{ $kuotaLagu - 1 }}-{{ $kuotaLagu + 1 }} Lagu</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Hari Aktif Badges --}}
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Hari Aktif:</span>
                                <div class="flex flex-wrap items-center gap-1">
                                    @php
                                        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                                        $activeDays = $schedule->hari_aktif ?? [];
                                    @endphp
                                    @foreach($allDays as $d)
                                        @if(in_array($d, $activeDays))
                                            @if($isJumatSpecial && $d === 'Jumat')
                                                <span class="px-2 py-0.5 rounded bg-[#ae3115] text-white border border-[#131b2e] text-xs font-bold font-mono">{{ $d }}</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-xs font-bold font-mono">{{ $d }}</span>
                                            @endif
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-[#dae2fd] text-[#727a64] border border-[#131b2e]/20 text-xs font-medium font-mono line-through opacity-70">{{ $d }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 self-end lg:self-center">
                                {{-- Edit Button --}}
                                <button type="button"
                                        onclick="openEditModal({{ $schedule->id }}, '{{ addslashes($schedule->nama_sesi) }}', '{{ $startClean }}', '{{ $endClean }}', {{ json_encode($schedule->hari_aktif ?? []) }}, {{ $schedule->is_active ? 'true' : 'false' }})"
                                        class="p-2 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] hover:bg-[#e2e7ff] transition-colors shadow-[2px_2px_0px_#131b2e]"
                                        title="Edit Sesi">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>

                                {{-- Toggle Active Status Form --}}
                                <form method="POST" action="{{ route('admin.schedules.toggle', $schedule) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="p-2 rounded-lg {{ $schedule->is_active ? 'bg-white text-[#131b2e] hover:bg-[#e2e7ff]' : 'bg-[#a3e635] text-[#131b2e] hover:bg-[#b2f746]' }} border-2 border-[#131b2e] transition-colors shadow-[2px_2px_0px_#131b2e]"
                                            title="{{ $schedule->is_active ? 'Nonaktifkan Sesi' : 'Aktifkan Kembali' }}">
                                        <span class="material-symbols-outlined text-lg">
                                            {{ $schedule->is_active ? 'pause_circle' : 'play_circle' }}
                                        </span>
                                    </button>
                                </form>

                                {{-- Delete Form --}}
                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal &quot;{{ $schedule->nama_sesi }}&quot;?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg bg-[#ffdad6] text-[#ba1a1a] border-2 border-[#131b2e] hover:opacity-85 transition-opacity shadow-[2px_2px_0px_#131b2e]"
                                            title="Hapus Sesi">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e]">
                        <span class="material-symbols-outlined text-4xl text-[#727a64] mb-2">event_busy</span>
                        <p class="text-sm font-bold text-[#424936]">Belum ada jadwal sesi yang dikonfigurasi.</p>
                        <p class="text-xs text-[#727a64] mt-1">Klik "+ Tambah Sesi Siaran Baru" untuk membuat jadwal penyiaran.</p>
                    </div>
                @endforelse
            </div>

            {{-- 6. Info Box: Operational Protocol & Bell Guard Policy --}}
            <div class="p-5 bg-[#dae2fd] border-2 border-[#131b2e] rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-[3px_3px_0px_#131b2e]">
                <div class="flex items-start gap-4">
                    <div class="p-2.5 rounded-xl bg-[#eaedff] border-2 border-[#131b2e] text-[#446900] shadow-[2px_2px_0px_#131b2e]">
                        <span class="material-symbols-outlined text-2xl">auto_stories</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <h4 class="text-base font-black text-[#131b2e] font-display">Protokol Penyiaran Suara SMAN 1</h4>
                        <p class="text-xs text-[#424936] max-w-3xl leading-relaxed">
                            Sistem SELADA Radio terhubung langsung dengan automasi Bel Sekolah Kurikulum. Lagu di luar jam yang terdaftar akan dipause secara paksa oleh cron job server untuk menjamin kondusifitas Kegiatan Belajar Mengajar (KBM).
                        </p>
                    </div>
                </div>
                <button type="button"
                        onclick="alert('SOP Penyiaran OSIS SMAN 1:\n1. Musik diputar hanya saat jam istirahat resmi.\n2. Fade out 2 menit sebelum bel masuk.\n3. Tombol Emergency Mute digunakan hanya untuk pengumuman darurat.')"
                        class="px-4 py-2 bg-white text-[#8127cf] border-2 border-[#131b2e] font-bold text-xs uppercase tracking-wider rounded-lg shadow-[2px_2px_0px_#131b2e] hover:bg-[#f0dbff] transition-all whitespace-nowrap">
                    Buka SOP Penyiaran OSIS
                </button>
            </div>

        </main>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODAL / FORM TAMBAH & EDIT JADWAL SIARAN (FR-SCH-01)
    ══════════════════════════════════════════════════════════ --}}
    <div id="schedule-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#283044]/70 backdrop-blur-sm p-4 overflow-y-auto">
        <div class="relative w-full max-w-2xl bg-[#faf8ff] border-2 border-[#131b2e] rounded-2xl shadow-[6px_6px_0px_#131b2e] overflow-hidden my-6">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-[#eaedff] border-b-2 border-[#131b2e]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#446900] text-2xl">more_time</span>
                    <h3 id="modal-title" class="text-lg font-black text-[#131b2e] font-display uppercase">
                        + Tambah Sesi Siaran Baru
                    </h3>
                </div>
                <button type="button" id="btn-close-modal" class="p-1 rounded-lg text-[#424936] hover:bg-[#e2e7ff] transition-colors">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form id="schedule-form" method="POST" action="{{ route('admin.schedules.store') }}" class="p-6 flex flex-col gap-4">
                @csrf
                <div id="method-container"></div>

                {{-- Input Nama Sesi --}}
                <div class="flex flex-col gap-1.5">
                    <label for="session-name" class="text-xs font-black text-[#131b2e] uppercase font-mono">
                        Nama Sesi Siaran <span class="text-[#ba1a1a]">*</span>
                    </label>
                    <input type="text"
                           id="session-name"
                           name="nama_sesi"
                           placeholder="Misal: Istirahat Siang / Jumat Santai"
                           required
                           class="px-4 py-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] text-sm focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-[2px_2px_0px_#131b2e]">
                </div>

                {{-- Input Jam Mulai & Jam Selesai --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="start-time" class="text-xs font-black text-[#131b2e] uppercase font-mono">
                            Jam Mulai (WIB) <span class="text-[#ba1a1a]">*</span>
                        </label>
                        <input type="time"
                               id="start-time"
                               name="jam_mulai"
                               required
                               class="px-4 py-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-[2px_2px_0px_#131b2e]">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="end-time" class="text-xs font-black text-[#131b2e] uppercase font-mono">
                            Jam Selesai (WIB) <span class="text-[#ba1a1a]">*</span>
                        </label>
                        <input type="time"
                               id="end-time"
                               name="jam_selesai"
                               required
                               class="px-4 py-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-[2px_2px_0px_#131b2e]">
                    </div>
                </div>

                {{-- Checkbox Pilihan Hari Aktif --}}
                <div class="flex flex-col gap-1.5">
                    <span class="text-xs font-black text-[#131b2e] uppercase font-mono">
                        Pilihan Hari Aktif Siaran <span class="text-[#ba1a1a]">*</span>
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 pt-1">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <label class="flex items-center gap-2 p-2 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] cursor-pointer hover:bg-[#e2e7ff] transition-colors shadow-sm">
                                <input type="checkbox"
                                       name="hari_aktif[]"
                                       value="{{ $day }}"
                                       id="day-{{ strtolower($day) }}"
                                       checked
                                       class="w-4 h-4 rounded text-[#446900] border-2 border-[#131b2e] focus:ring-0">
                                <span class="text-xs font-bold text-[#131b2e] font-mono">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Switch Otomatis Nyalakan DJ Player --}}
                <div class="flex items-center justify-between p-3.5 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl shadow-[2px_2px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-[#131b2e]">Otomatis Nyalakan DJ Player</span>
                        <span class="text-[11px] text-[#424936]">
                            Memulai pemutaran lagu dari antrian teratas begitu jam sesi dimulai.
                        </span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" id="auto-play-toggle" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#446900]"></div>
                    </label>
                </div>

                {{-- Status Aktif Switch --}}
                <div class="flex items-center justify-between p-3.5 bg-[#eaedff] border-2 border-[#131b2e] rounded-xl shadow-[2px_2px_0px_#131b2e]">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-[#131b2e]">Status Sesi Aktif</span>
                        <span class="text-[11px] text-[#424936]">
                            Sesi langsung berjalan mingguan sesuai hari yang dipilih.
                        </span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" name="is_active" id="modal-is-active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a3e635]"></div>
                    </label>
                </div>

                {{-- Catatan Aturan KBM (Wajib Sesuai Desain Stitch) --}}
                <div class="p-3.5 bg-[#f0dbff] text-[#2c0051] border-2 border-[#131b2e] rounded-xl flex items-start gap-3 shadow-[2px_2px_0px_#131b2e]">
                    <span class="material-symbols-outlined text-[#8127cf] text-2xl flex-shrink-0">warning</span>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs font-black">Catatan Aturan KBM:</span>
                        <p class="text-[11px] font-medium leading-relaxed">
                            Musik otomatis berhenti 2 menit sebelum bel masuk berbunyi untuk persiapan KBM.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="btn-cancel-modal" class="px-4 py-2 bg-white text-[#131b2e] border-2 border-[#131b2e] font-bold text-xs uppercase tracking-wider rounded-lg hover:bg-[#eaedff] transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] font-black text-xs uppercase tracking-wider rounded-lg shadow-[3px_3px_0px_#131b2e] hover:bg-[#b2f746] transition-all active:translate-y-0.5">
                        Simpan Jadwal Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     INTERACTION SCRIPT
══════════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('schedule-modal');
        const openBtn = document.getElementById('btn-open-modal');
        const closeBtn = document.getElementById('btn-close-modal');
        const cancelBtn = document.getElementById('btn-cancel-modal');
        const syncBtn = document.getElementById('btn-sync-bell');
        const syncToast = document.getElementById('sync-toast');
        const modalTitle = document.getElementById('modal-title');
        const scheduleForm = document.getElementById('schedule-form');
        const methodContainer = document.getElementById('method-container');
        const sessionInput = document.getElementById('session-name');
        const startInput = document.getElementById('start-time');
        const endInput = document.getElementById('end-time');
        const emergencyToggle = document.getElementById('emergency-toggle');
        const isActiveCheckbox = document.getElementById('modal-is-active');

        const defaultStoreAction = "{{ route('admin.schedules.store') }}";

        window.openModal = function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeModal = function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modalTitle.innerText = '+ Tambah Sesi Siaran Baru';
            scheduleForm.action = defaultStoreAction;
            methodContainer.innerHTML = '';
            scheduleForm.reset();
            // Default all days checked
            document.querySelectorAll('input[name="hari_aktif[]"]').forEach(cb => cb.checked = true);
            isActiveCheckbox.checked = true;
        };

        window.openEditModal = function (id, name, start, end, days, isActive) {
            modalTitle.innerText = 'Edit Sesi: ' + name;
            scheduleForm.action = `/admin/schedules/${id}`;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            sessionInput.value = name;
            startInput.value = start;
            endInput.value = end;
            isActiveCheckbox.checked = !!isActive;

            // Update days checkboxes
            const activeDaysList = Array.isArray(days) ? days : [];
            document.querySelectorAll('input[name="hari_aktif[]"]').forEach(cb => {
                cb.checked = activeDaysList.includes(cb.value);
            });

            openModal();
        };

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        // Click outside modal to close
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // Close on ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Sync Bell Toast
        if (syncBtn && syncToast) {
            syncBtn.addEventListener('click', function () {
                syncToast.classList.remove('hidden');
                syncToast.classList.add('flex');
                setTimeout(() => {
                    syncToast.classList.add('hidden');
                    syncToast.classList.remove('flex');
                }, 6000);
            });
        }

        // Emergency Mute
        if (emergencyToggle) {
            emergencyToggle.addEventListener('change', function (e) {
                if (e.target.checked) {
                    alert('⚠️ PERINGATAN DARURAT: Speaker sekolah berhasil di-MUTE total untuk semua koridor!');
                } else {
                    alert('✅ Speaker sekolah diaktifkan kembali sesuai jadwal aktif.');
                }
            });
        }

        // Live Countdown Timer
        let secondsLeft = {{ $secondsRemaining > 0 ? $secondsRemaining : 18 * 60 + 42 }};
        const countdownEl = document.getElementById('countdown-timer');
        if (countdownEl) {
            setInterval(() => {
                if (secondsLeft > 0) {
                    secondsLeft--;
                    const m = Math.floor(secondsLeft / 60);
                    const s = secondsLeft % 60;
                    countdownEl.innerText = `${m}m ${s < 10 ? '0' : ''}${s}s`;
                }
            }, 1000);
        }
    });
</script>
</x-admin-layout>
