<x-admin-layout>
{{--
    Pengaturan Global Sistem — SELADA Broadcast Booth
    Design source: Stitch (5ddb0f282bc34ae6a25aec486266d9fb)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen bg-[#faf8ff] text-[#131b2e] font-sans overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="settings" />

    {{-- ══════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════════════ --}}
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
                    @php $ini = strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', Auth::user()->name ?? 'U'))), 0, 2)); @endphp
                    <div class="w-8 h-8 rounded-full border-2 border-[#131b2e] bg-[#a3e635] text-[#416400] flex items-center justify-center font-black text-xs select-none shadow-[2px_2px_0px_#131b2e]">{{ $ini }}</div>
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

            {{-- 1. Top Bar & Breadcrumb Hero Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-[#f2f3ff] border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e]">
                <div class="flex flex-col gap-1 max-w-3xl">
                    <div class="flex items-center gap-1.5 text-xs text-[#8127cf] font-mono font-bold uppercase tracking-wider">
                        <span>ADMIN CONSOLE</span>
                        <span class="material-symbols-outlined text-sm text-[#424936]">chevron_right</span>
                        <span class="text-[#424936]">KONFIGURASI SISTEM</span>
                        <span class="material-symbols-outlined text-sm text-[#424936]">chevron_right</span>
                        <span class="bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] px-2 py-0.5 rounded font-black">PENGATURAN GLOBAL</span>
                    </div>
                    <h1 class="text-2xl xl:text-3xl font-black text-[#131b2e] tracking-tight uppercase font-display mt-1">
                        Pengaturan Global &amp; Kebijakan Radio OSIS
                    </h1>
                    <p class="text-sm text-[#424936] leading-relaxed">
                        Konfigurasi batasan kuota request, filter kata terlarang, durasi siaran maksimal, dan parameter algoritma antrian lagu.
                    </p>
                </div>

                {{-- Quick Action Buttons --}}
                <div class="flex items-center gap-2.5 shrink-0">
                    {{-- Form Reset Default --}}
                    <form method="POST" action="{{ route('admin.settings.reset') }}" onsubmit="return confirm('Kembalikan semua pengaturan ke nilai default resmi SMKN 20?')" class="inline">
                        @csrf
                        <button type="submit"
                                id="resetBtn"
                                class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] font-bold text-xs uppercase tracking-wider hover:bg-[#ffdad6] hover:text-[#93000a] shadow-[2px_2px_0px_#131b2e] transition-all active:translate-y-0.5">
                            <span class="material-symbols-outlined text-base">restart_alt</span>
                            <span>Reset Default</span>
                        </button>
                    </form>

                    {{-- Save Button (triggers main form submit) --}}
                    <button type="button"
                            id="saveAllBtn"
                            onclick="document.getElementById('settingsForm').submit();"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_#131b2e] hover:bg-[#b2f746] transition-all active:translate-y-0.5">
                        <span class="material-symbols-outlined text-base">save</span>
                        <span>Simpan Perubahan</span>
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

            {{-- 2. Quick Telemetry & Rules Badge Strip (4 Cards) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Card 1: Aturan Bisnis --}}
                <div class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Aturan Bisnis</span>
                        <span class="text-base font-black text-[#131b2e] font-display">BR-01 s/d BR-05</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">gavel</span>
                    </div>
                </div>

                {{-- Card 2: Status Mesin --}}
                <div class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Status Mesin</span>
                        <span class="text-base font-black text-[#446900] flex items-center gap-1.5 font-display">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a3e635] border border-[#131b2e] animate-pulse"></span> SINKRON
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#b2f746] text-[#121f00] border border-[#131b2e] flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">sync_saved_locally</span>
                    </div>
                </div>

                {{-- Card 3: Blacklist Terdata --}}
                <div class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Blacklist Terdata</span>
                        <span class="text-base font-black text-[#ae3115] font-display" id="blacklistCountHero">
                            {{ $blacklistCount }} KATA KUNCI
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#ffc5b8] text-[#3d0600] border border-[#131b2e] flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">shield</span>
                    </div>
                </div>

                {{-- Card 4: Durasi Slot Maks --}}
                <div class="p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Durasi Slot Maks</span>
                        <span class="text-base font-black text-[#131b2e] font-display" id="durasiHeroBadge">
                            {{ $maksDurasiDetik }} Detik ({{ round($maksDurasiDetik / 60, 1) }}m)
                        </span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#eaedff] text-[#131b2e] border border-[#131b2e] flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">timelapse</span>
                    </div>
                </div>
            </div>

            {{-- 3. MAIN FORM SETTINGS (12-Column Grid) --}}
            <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    {{-- LEFT COLUMN: Section 1 & Section 2 (7 Cols) --}}
                    <div class="lg:col-span-7 flex flex-col gap-6">
                        
                        {{-- SECTION 1: Kebijakan Kuota & Request Siswa --}}
                        <div class="bg-white border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e] flex flex-col gap-4">
                            
                            {{-- Header Section 1 --}}
                            <div class="flex items-center justify-between pb-3 bg-[#eaedff] border-b-2 border-[#131b2e] -mx-6 -mt-6 p-4 rounded-t-[10px]">
                                <div class="flex items-center gap-3">
                                    <span class="p-2 rounded-lg bg-[#a3e635] text-[#131b2e] border border-[#131b2e] material-symbols-outlined text-xl">how_to_reg</span>
                                    <div>
                                        <h2 class="text-lg font-black text-[#131b2e] font-display leading-tight">1. Kebijakan Kuota &amp; Request Siswa</h2>
                                        <span class="text-[10px] text-[#8127cf] uppercase font-mono font-bold tracking-wider">Validasi Integritas BR-01, BR-02, BR-03</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-[10px] font-mono font-black">AKTIF</span>
                            </div>

                            {{-- Param 1: Kuota Request Harian Siswa (BR-01) --}}
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex flex-col max-w-md">
                                    <label class="text-sm font-bold text-[#131b2e]" for="batasRequestInput">Kuota Request Harian Siswa</label>
                                    <p class="text-xs text-[#424936]">Batas maksimal pengajuan per NIS per hari (BR-01).</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="relative w-28">
                                        <input type="number"
                                               id="batasRequestInput"
                                               name="batas_request_per_hari"
                                               min="1"
                                               max="10"
                                               value="{{ old('batas_request_per_hari', $batasRequest) }}"
                                               class="w-full bg-white border-2 border-[#131b2e] text-[#131b2e] font-black text-center py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-sm font-mono text-sm">
                                        <span class="absolute right-2 top-2.5 text-[11px] font-mono font-bold text-[#424936] pointer-events-none">/Hari</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Param 1b: Bypass Kuota untuk Pengurus OSIS --}}
                            <div class="flex items-center justify-between p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-sm font-bold text-[#131b2e]">Bypass Kuota untuk Pengurus OSIS</span>
                                    <p class="text-xs text-[#424936]">Akun operator dan DJ radio memiliki kuota tak terbatas untuk pengisi waktu jeda.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox"
                                           id="bypassOsisToggle"
                                           name="bypass_osis"
                                           value="1"
                                           {{ old('bypass_osis', $bypassOsis) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-8 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#131b2e] after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#a3e635] peer-checked:after:bg-[#131b2e]"></div>
                                </label>
                            </div>

                            {{-- Param 2: Maksimal Durasi Video Lagu (BR-03) --}}
                            <div class="flex flex-col gap-3 p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-bold text-[#131b2e]">Maksimal Durasi Video Lagu</span>
                                        <p class="text-xs text-[#424936]">Parameter <code class="bg-[#eaedff] px-1 py-0.5 rounded text-[#8127cf] font-mono font-bold">maks_durasi_detik</code> (BR-03)</p>
                                    </div>
                                    <span class="text-xs font-black font-mono px-3 py-1 bg-[#eaedff] border border-[#131b2e] text-[#131b2e] rounded-lg shadow-sm" id="sliderValueText">
                                        {{ $maksDurasiDetik }} Detik ({{ round($maksDurasiDetik / 60, 1) }} Menit)
                                    </span>
                                </div>

                                <div class="py-2">
                                    <input type="range"
                                           id="durasiSlider"
                                           name="maks_durasi_detik"
                                           min="120"
                                           max="900"
                                           step="30"
                                           value="{{ old('maks_durasi_detik', $maksDurasiDetik) }}"
                                           class="w-full h-3 bg-[#dae2fd] rounded-lg appearance-none cursor-pointer accent-[#446900]">
                                    <div class="flex justify-between text-[11px] font-mono text-[#424936] mt-1">
                                        <span>120 Detik (2m)</span>
                                        <span>480 Detik (8m)</span>
                                        <span>600 Detik (10m)</span>
                                        <span>900 Detik (15m)</span>
                                    </div>
                                </div>

                                {{-- Caution Callout --}}
                                <div class="flex items-start gap-2.5 p-3 rounded-lg bg-[#ffdad2] border border-[#131b2e] text-[#3d0600]">
                                    <span class="material-symbols-outlined text-lg shrink-0 mt-0.5 text-[#ae3115]">warning</span>
                                    <p class="text-xs leading-relaxed">
                                        <strong>Catatan Proteksi Waktu:</strong> Mencegah 1 lagu berdurasi sangat panjang mendominasi jam istirahat sekolah yang hanya berlangsung 20-30 menit.
                                    </p>
                                </div>
                            </div>

                            {{-- Param 3: Pencegahan Duplikasi Lagu Harian (BR-02) --}}
                            <div class="flex items-center justify-between p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-sm font-bold text-[#131b2e]">Pencegahan Duplikasi Lagu Harian</span>
                                    <p class="text-xs text-[#424936]">
                                        Tolak lagu yang masih berstatus <span class="text-[#8127cf] font-bold">menunggu</span>, <span class="text-[#446900] font-bold">disetujui</span>, atau <span class="text-[#ae3115] font-bold">diputar</span> di tanggal yang sama.
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                    <input type="checkbox"
                                           id="dupCheckToggle"
                                           name="prevent_duplicate"
                                           value="1"
                                           {{ old('prevent_duplicate', $preventDuplicate) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-8 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#131b2e] after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#a3e635] peer-checked:after:bg-[#131b2e]"></div>
                                </label>
                            </div>

                            {{-- Param 4: Batas Karakter Pesan Dedikasi --}}
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-sm font-bold text-[#131b2e]">Batas Karakter Titip Salam / Dedikasi</span>
                                    <p class="text-xs text-[#424936]">Panjang maksimum catatan dedikasi siswa untuk pembacaan siaran on-air.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                           id="charLimitInput"
                                           name="char_limit"
                                           min="50"
                                           max="500"
                                           value="{{ old('char_limit', $charLimit) }}"
                                           class="w-24 bg-white border-2 border-[#131b2e] text-[#131b2e] font-black text-center py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-sm font-mono text-sm">
                                    <span class="text-xs font-mono font-bold text-[#424936]">Karakter</span>
                                </div>
                            </div>

                        </div>

                        {{-- SECTION 2: Otomasi & Logika Moderasi Lagu --}}
                        <div class="bg-white border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e] flex flex-col gap-4">
                            
                            {{-- Header Section 2 --}}
                            <div class="flex items-center justify-between pb-3 bg-[#eaedff] border-b-2 border-[#131b2e] -mx-6 -mt-6 p-4 rounded-t-[10px]">
                                <div class="flex items-center gap-3">
                                    <span class="p-2 rounded-lg bg-[#8127cf] text-white border border-[#131b2e] material-symbols-outlined text-xl">auto_fix_high</span>
                                    <div>
                                        <h2 class="text-lg font-black text-[#131b2e] font-display leading-tight">2. Otomasi &amp; Logika Moderasi Lagu</h2>
                                        <span class="text-[10px] text-[#8127cf] uppercase font-mono font-bold tracking-wider">Algoritma Kurasi Cepat FR-MOD</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-[#b2f746] text-[#121f00] border border-[#131b2e] text-[10px] font-mono font-black">INTELLIGENT</span>
                            </div>

                            {{-- Auto Moderasi Toggle --}}
                            <div class="flex flex-col gap-2 p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#131b2e]">Mode Moderasi Otomatis (Auto-Approval)</span>
                                        <span class="text-[10px] font-mono font-bold text-[#446900]">PARAMETER: moderasi_otomatis</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox"
                                               id="autoApproveToggle"
                                               name="moderasi_otomatis"
                                               value="1"
                                               {{ old('moderasi_otomatis', $moderasiOtomatis) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-14 h-8 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#131b2e] after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#a3e635] peer-checked:after:bg-[#131b2e]"></div>
                                    </label>
                                </div>
                                <p class="text-xs text-[#424936] mt-1 leading-relaxed">
                                    Jika aktif, lagu tanpa kata terlarang otomatis berstatus <span class="bg-[#a3e635] text-[#131b2e] border border-[#131b2e] px-1.5 py-0.5 rounded font-black text-[11px] font-mono">Disetujui</span> tanpa perlu kurasi manual operator DJ terlebih dahulu.
                                </p>
                            </div>

                            {{-- YouTube Embeddable & Age Restrict Deteksi --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Embed Check --}}
                                <div class="p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e] flex flex-col justify-between gap-3">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#131b2e]">Validasi YouTube IFrame</span>
                                        <p class="text-xs text-[#424936] mt-1 leading-relaxed">Hanya izinkan link video yang mengaktifkan izin embed dari kreatornya.</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-[#131b2e]/10">
                                        <span class="text-[10px] font-mono font-bold text-[#8127cf]">EMBED REQUIRED</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="youtube_embed_required"
                                                   value="1"
                                                   {{ old('youtube_embed_required', $youtubeEmbed) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-[#131b2e] after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a3e635] peer-checked:after:bg-[#131b2e]"></div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Age Restriction Check --}}
                                <div class="p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e] flex flex-col justify-between gap-3">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#131b2e]">Tolak Batasan Usia (18+)</span>
                                        <p class="text-xs text-[#424936] mt-1 leading-relaxed">Blokir otomatis video YouTube dengan filter Age-Restricted untuk siaran sekolah.</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-[#131b2e]/10">
                                        <span class="text-[10px] font-mono font-bold text-[#ae3115]">STRICT RESTRICT</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="reject_age_restricted"
                                                   value="1"
                                                   {{ old('reject_age_restricted', $rejectAgeRestricted) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-[#dae2fd] border border-[#131b2e] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-[#131b2e] after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a3e635] peer-checked:after:bg-[#131b2e]"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- RIGHT COLUMN: Section 3 & Section 4 (5 Cols) --}}
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        
                        {{-- SECTION 3: Filter Kata Terlarang (BR-04) --}}
                        <div class="bg-white border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e] flex flex-col gap-4">
                            
                            {{-- Header Section 3 --}}
                            <div class="flex items-center justify-between pb-3 bg-[#eaedff] border-b-2 border-[#131b2e] -mx-6 -mt-6 p-4 rounded-t-[10px]">
                                <div class="flex items-center gap-3">
                                    <span class="p-2 rounded-lg bg-[#ae3115] text-white border border-[#131b2e] material-symbols-outlined text-xl">filter_alt</span>
                                    <div>
                                        <h2 class="text-lg font-black text-[#131b2e] font-display leading-tight">3. Filter Kata Terlarang</h2>
                                        <span class="text-[10px] text-[#ae3115] uppercase font-mono font-bold tracking-wider">Blacklist Engine (BR-04)</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-[#ffdad2] text-[#3d0600] border border-[#131b2e] text-[10px] font-mono font-black">STRICT</span>
                            </div>

                            <p class="text-xs text-[#424936] leading-relaxed">
                                Penyaringan otomatis terhadap kata kasar, konten SARA, vulgar, ataupun ujaran kebencian di judul lagu, artis, dan pesan titip salam.
                            </p>

                            {{-- Input Tambah Kata --}}
                            <div class="flex gap-2">
                                <input type="text"
                                       id="keywordInput"
                                       placeholder="Ketik kata baru lalu tekan Enter..."
                                       class="flex-1 bg-[#f2f3ff] border-2 border-[#131b2e] text-[#131b2e] text-xs px-3.5 py-2.5 rounded-lg focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#8127cf] shadow-sm">
                                <button type="button"
                                        id="addKeywordBtn"
                                        class="px-4 py-2.5 rounded-lg bg-[#8127cf] text-white border-2 border-[#131b2e] font-bold text-xs uppercase tracking-wider flex items-center gap-1 shadow-[2px_2px_0px_#131b2e] active:translate-y-0.5 transition-all">
                                    <span class="material-symbols-outlined text-base">add</span>
                                    <span>Tambah</span>
                                </button>
                            </div>

                            {{-- Hidden input containing comma-separated blacklist keywords --}}
                            <input type="hidden" name="blacklist_kata" id="hiddenBlacklistInput" value="{{ $blacklistKata }}">

                            {{-- Tag Pills Container --}}
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] uppercase font-mono font-bold text-[#424936]">
                                        Daftar Kata Aktif (<span id="keywordCount">{{ $blacklistCount }}</span>):
                                    </span>
                                    <button type="button" id="clearAllKeywords" class="text-[#ae3115] hover:underline text-[11px] font-mono font-bold">
                                        Hapus Semua
                                    </button>
                                </div>
                                
                                <div id="keywordsList" class="flex flex-wrap gap-2 p-3 bg-[#f2f3ff] border-2 border-[#131b2e] rounded-lg min-h-[140px] content-start">
                                    @foreach($blacklistArray as $word)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#ffdad6] text-[#93000a] border border-[#131b2e] font-mono text-xs font-bold keyword-tag">
                                            <span>{{ $word }}</span>
                                            <button type="button" onclick="this.parentElement.remove(); syncKeywords();" class="material-symbols-outlined text-sm hover:opacity-75">close</button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Pilihan Aksi Jika Terdeteksi --}}
                            <div class="flex flex-col gap-2 pt-1 border-t border-[#131b2e]/10">
                                <span class="text-xs font-black uppercase text-[#131b2e] font-mono">Tindakan Saat Terdeteksi:</span>
                                <div class="grid grid-cols-1 gap-2">
                                    <label class="flex items-center gap-3 p-3 rounded-lg bg-[#f2f3ff] border border-[#131b2e] cursor-pointer hover:bg-[#eaedff] transition-colors">
                                        <input type="radio"
                                               name="blacklist_action"
                                               value="auto_reject"
                                               {{ old('blacklist_action', $blacklistAction) === 'auto_reject' ? 'checked' : '' }}
                                               class="w-4 h-4 text-[#ae3115] border-2 border-[#131b2e] focus:ring-0 cursor-pointer">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-[#131b2e]">Tolak Otomatis &amp; Notifikasi Siswa</span>
                                            <span class="text-[11px] text-[#424936]">Lagu langsung ditolak dan alasan SARA/Kata Kasar dicatat ke log siswa.</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-lg bg-[#f2f3ff] border border-[#131b2e] cursor-pointer hover:bg-[#eaedff] transition-colors">
                                        <input type="radio"
                                               name="blacklist_action"
                                               value="flag_review"
                                               {{ old('blacklist_action', $blacklistAction) === 'flag_review' ? 'checked' : '' }}
                                               class="w-4 h-4 text-[#ae3115] border-2 border-[#131b2e] focus:ring-0 cursor-pointer">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-[#131b2e]">Tandai Flag Merah untuk Review DJ</span>
                                            <span class="text-[11px] text-[#424936]">Lagu ditahan di antrian dengan highlight kuning/merah untuk disetujui manual.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>

                        {{-- SECTION 4: Sistem & Pengumuman Sekolah --}}
                        <div class="bg-white border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e] flex flex-col gap-4">
                            
                            {{-- Header Section 4 --}}
                            <div class="flex items-center justify-between pb-3 bg-[#eaedff] border-b-2 border-[#131b2e] -mx-6 -mt-6 p-4 rounded-t-[10px]">
                                <div class="flex items-center gap-3">
                                    <span class="p-2 rounded-lg bg-[#dae2fd] text-[#131b2e] border border-[#131b2e] material-symbols-outlined text-xl">campaign</span>
                                    <div>
                                        <h2 class="text-lg font-black text-[#131b2e] font-display leading-tight">4. Sistem &amp; Pengumuman Sekolah</h2>
                                        <span class="text-[10px] text-[#424936] uppercase font-mono font-bold tracking-wider">Player Health &amp; Marquee</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-[10px] font-mono font-black">RUNNING</span>
                            </div>

                            {{-- Running Text Pengumuman --}}
                            <div class="flex flex-col gap-1.5">
                                <div class="flex justify-between items-center">
                                    <label for="marqueeInput" class="text-xs font-bold text-[#131b2e]">Teks Pengumuman Running Text</label>
                                    <span class="text-[10px] font-mono font-bold text-[#446900]">Tampil di Beranda Siswa</span>
                                </div>
                                <textarea id="marqueeInput"
                                          name="marquee_text"
                                          rows="2"
                                          class="w-full bg-[#f2f3ff] border-2 border-[#131b2e] text-[#131b2e] text-xs p-3 rounded-lg focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#8127cf] shadow-sm leading-relaxed">{{ old('marquee_text', $marqueeText) }}</textarea>
                            </div>

                            {{-- Video Error Tolerance Skip (FR-PLY-03) --}}
                            <div class="flex items-center justify-between p-4 rounded-lg bg-[#f2f3ff] border border-[#131b2e]">
                                <div class="flex flex-col max-w-xs">
                                    <span class="text-sm font-bold text-[#131b2e]">Toleransi Video Error Skip (FR-PLY-03)</span>
                                    <p class="text-xs text-[#424936]">Durasi tunggu bila video YouTube error/unplayable sebelum auto-skip ke lagu berikutnya.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                           id="errorToleranceInput"
                                           name="error_tolerance_detik"
                                           min="1"
                                           max="10"
                                           value="{{ old('error_tolerance_detik', $errorTolerance) }}"
                                           class="w-16 bg-white border-2 border-[#131b2e] text-[#131b2e] font-black text-center py-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8127cf] shadow-sm font-mono text-sm">
                                    <span class="text-xs font-mono font-bold text-[#131b2e]">Detik</span>
                                </div>
                            </div>

                            {{-- Pemeliharaan & Cadangan Data --}}
                            <div class="flex flex-col gap-2 pt-2 border-t border-[#131b2e]/10">
                                <span class="text-xs font-black uppercase text-[#131b2e] font-mono">Pemeliharaan &amp; Cadangan Data</span>
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('admin.settings.downloadDb') }}"
                                       class="flex items-center justify-center gap-1.5 p-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] hover:bg-[#a3e635] font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_#131b2e] transition-all active:translate-y-0.5">
                                        <span class="material-symbols-outlined text-base">database</span>
                                        <span>Unduh Backup DB</span>
                                    </a>
                                    <a href="{{ route('admin.settings.downloadLogs') }}"
                                       class="flex items-center justify-center gap-1.5 p-2.5 rounded-lg bg-white border-2 border-[#131b2e] text-[#131b2e] hover:bg-[#f0dbff] font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_#131b2e] transition-all active:translate-y-0.5">
                                        <span class="material-symbols-outlined text-base">receipt_long</span>
                                        <span>Ekspor Log Siaran</span>
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- 4. Live Preview Status Bar of Engine Settings --}}
                <div class="mt-6 bg-[#e2e7ff] border-2 border-[#131b2e] p-5 rounded-xl shadow-[4px_4px_0px_#131b2e] flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#a3e635] border border-[#131b2e] text-[#131b2e] flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-2xl text-[#416400]">verified</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs uppercase font-mono font-black text-[#8127cf]">Sinkronisasi Siaran Realtime</span>
                            <span class="text-sm font-bold text-[#131b2e]">Semua perubahan parameter langsung berdampak pada sesi DJ Player berikutnya.</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                id="footerSaveBtn"
                                class="flex items-center gap-2 px-6 py-3 rounded-lg bg-[#a3e635] text-[#131b2e] border-2 border-[#131b2e] font-black text-sm uppercase tracking-wider shadow-[3px_3px_0px_#131b2e] hover:bg-[#b2f746] transition-all active:translate-y-0.5">
                            <span class="material-symbols-outlined text-xl">done_all</span>
                            <span>Simpan Perubahan Pengaturan</span>
                        </button>
                    </div>
                </div>
            </form>

        </main>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     INTERACTION SCRIPT
══════════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Slider interaction
        const slider = document.getElementById('durasiSlider');
        const sliderDisplay = document.getElementById('sliderValueText');
        const durasiBadge = document.getElementById('durasiHeroBadge');

        if (slider) {
            slider.addEventListener('input', function() {
                const val = parseInt(this.value, 10);
                const minutes = (val / 60).toFixed(1);
                if (sliderDisplay) sliderDisplay.innerText = `${val} Detik (${minutes} Menit)`;
                if (durasiBadge) durasiBadge.innerText = `${val} Detik (${minutes}m)`;
            });
        }

        // Add Keyword functionality
        const keywordInput = document.getElementById('keywordInput');
        const addBtn = document.getElementById('addKeywordBtn');
        const keywordsList = document.getElementById('keywordsList');
        const hiddenBlacklist = document.getElementById('hiddenBlacklistInput');
        const keywordCountSpan = document.getElementById('keywordCount');
        const blacklistHero = document.getElementById('blacklistCountHero');

        window.syncKeywords = function () {
            const tags = keywordsList.querySelectorAll('.keyword-tag span');
            const words = [];
            tags.forEach(t => words.push(t.innerText.trim()));
            if (hiddenBlacklist) hiddenBlacklist.value = words.join(', ');
            if (keywordCountSpan) keywordCountSpan.innerText = words.length;
            if (blacklistHero) blacklistHero.innerText = `${words.length} KATA KUNCI`;
        };

        function addKeyword() {
            const val = keywordInput.value.trim().toLowerCase();
            if (!val) return;

            // Check if already in list
            const existingTags = keywordsList.querySelectorAll('.keyword-tag span');
            let alreadyExists = false;
            existingTags.forEach(t => {
                if (t.innerText.toLowerCase() === val) alreadyExists = true;
            });
            if (alreadyExists) {
                keywordInput.value = '';
                return;
            }

            // Create pill
            const pill = document.createElement('span');
            pill.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#ffdad6] text-[#93000a] border border-[#131b2e] font-mono text-xs font-bold keyword-tag';
            
            const txt = document.createElement('span');
            txt.innerText = val;
            
            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'material-symbols-outlined text-sm hover:opacity-75';
            delBtn.innerText = 'close';
            delBtn.onclick = function() {
                pill.remove();
                syncKeywords();
            };

            pill.appendChild(txt);
            pill.appendChild(delBtn);
            keywordsList.appendChild(pill);

            keywordInput.value = '';
            syncKeywords();
        }

        if (addBtn) addBtn.addEventListener('click', addKeyword);
        if (keywordInput) {
            keywordInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addKeyword();
                }
            });
        }

        const clearBtn = document.getElementById('clearAllKeywords');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                keywordsList.innerHTML = '';
                syncKeywords();
            });
        }

        // Sync hidden keywords input before form submit
        const settingsForm = document.getElementById('settingsForm');
        if (settingsForm) {
            settingsForm.addEventListener('submit', function () {
                syncKeywords();
            });
        }
    });
</script>
</x-admin-layout>
