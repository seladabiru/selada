<x-admin-layout>
{{--
    Kotak Masukan & Aspirasi Siswa — SELADA Broadcast Booth
    Design source: Stitch (fd498957fdd54c02b2125b7bd78d98d6)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen bg-[#faf8ff] text-[#131b2e] font-sans overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="feedbacks" />

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

            {{-- 1. Breadcrumb & Main Header Card --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-[#f2f3ff] border-2 border-[#131b2e] p-6 rounded-xl shadow-[4px_4px_0px_#131b2e]">
                <div class="flex flex-col gap-1 max-w-3xl">
                    <div class="flex items-center gap-1.5 text-xs text-[#424936] font-mono font-bold uppercase tracking-wider">
                        <span>Admin Console</span>
                        <span>/</span>
                        <span>Suara Siswa</span>
                        <span>/</span>
                        <span class="text-[#8127cf] font-black">Kotak Kritik &amp; Saran OSIS</span>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap mt-1">
                        <h1 class="text-2xl xl:text-3xl font-black text-[#131b2e] tracking-tight uppercase font-display">
                            Kotak Masukan &amp; Aspirasi Radio OSIS
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-[#a3e635] text-[#131b2e] border border-[#131b2e] text-xs uppercase font-mono font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-[#446900] animate-ping"></span>
                            Sync On-Air Aktif
                        </span>
                    </div>
                    <p class="text-sm text-[#424936] mt-1 leading-relaxed">
                        Tinjau saran lagu, kritik teknis speaker, laporan bug sistem, dan aspirasi siswa untuk program siaran radio sekolah.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center flex-wrap gap-2.5">
                    {{-- Form Tandai Semua Telah Dibaca --}}
                    <form method="POST" action="{{ route('admin.feedbacks.markAllRead') }}" class="inline" id="form-mark-all">
                        @csrf
                        <button type="submit"
                                id="btn-mark-all"
                                class="flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border-2 border-[#131b2e] hover:bg-[#eaedff] text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all shadow-[2px_2px_0px_#131b2e] active:translate-y-0.5">
                            <span class="material-symbols-outlined text-base">done_all</span>
                            <span>Tandai Semua Telah Dibaca</span>
                        </button>
                    </form>

                    {{-- Export CSV --}}
                    <a href="{{ route('admin.feedbacks.export') }}"
                       class="flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#8127cf] text-white border-2 border-[#131b2e] hover:bg-[#6c1bb5] font-bold text-xs uppercase tracking-wider transition-all shadow-[3px_3px_0px_#131b2e] active:translate-y-0.5">
                        <span class="material-symbols-outlined text-base">ios_share</span>
                        <span>Ekspor Aspirasi (.CSV)</span>
                    </a>
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

            {{-- 2. 6 Bento Quick Metric Cards Row --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
                {{-- Metric 1: Total Aspirasi --}}
                <div class="flex flex-col p-4 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[4px_4px_0px_#131b2e] transition-all">
                    <div class="flex items-center justify-between text-[#424936] mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Total Aspirasi</span>
                        <span class="material-symbols-outlined text-lg text-[#446900]">inbox</span>
                    </div>
                    <div class="text-2xl font-black text-[#131b2e] font-display">{{ $totalAspirasi }}</div>
                    <span class="text-[11px] text-[#424936] mt-0.5">Keseluruhan masukan</span>
                </div>

                {{-- Metric 2: Belum Dibaca (Hero Callout) --}}
                <div class="flex flex-col p-4 bg-[#ae3115] text-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Belum Dibaca</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ffdad6] animate-pulse"></span>
                    </div>
                    <div class="text-2xl font-black text-white font-display">{{ $unreadCount }}</div>
                    <span class="text-[11px] text-white/90 font-bold mt-0.5">Aspirasi Baru Masuk 🔥</span>
                </div>

                {{-- Metric 3: Saran Lagu --}}
                <div class="flex flex-col p-4 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[4px_4px_0px_#131b2e] transition-all">
                    <div class="flex items-center justify-between text-[#424936] mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Saran Lagu</span>
                        <span class="material-symbols-outlined text-lg text-[#446900]">music_note</span>
                    </div>
                    <div class="text-2xl font-black text-[#131b2e] font-display">{{ $saranCount }}</div>
                    <div class="w-full bg-[#eaedff] border border-[#131b2e]/30 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-[#a3e635] h-full" style="width: {{ $totalAspirasi > 0 ? min(100, round(($saranCount / $totalAspirasi) * 100)) : 50 }}%;"></div>
                    </div>
                </div>

                {{-- Metric 4: Masalah Teknis --}}
                <div class="flex flex-col p-4 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[4px_4px_0px_#131b2e] transition-all">
                    <div class="flex items-center justify-between text-[#424936] mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Masalah Teknis</span>
                        <span class="material-symbols-outlined text-lg text-[#ae3115]">volume_off</span>
                    </div>
                    <div class="text-2xl font-black text-[#131b2e] font-display">{{ $masalahCount }}</div>
                    <div class="w-full bg-[#eaedff] border border-[#131b2e]/30 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-[#ffc5b8] h-full" style="width: {{ $totalAspirasi > 0 ? min(100, round(($masalahCount / $totalAspirasi) * 100)) : 25 }}%;"></div>
                    </div>
                </div>

                {{-- Metric 5: Ide Program --}}
                <div class="flex flex-col p-4 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[4px_4px_0px_#131b2e] transition-all">
                    <div class="flex items-center justify-between text-[#424936] mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Ide Program</span>
                        <span class="material-symbols-outlined text-lg text-[#8127cf]">tips_and_updates</span>
                    </div>
                    <div class="text-2xl font-black text-[#131b2e] font-display">{{ $ideCount }}</div>
                    <div class="w-full bg-[#eaedff] border border-[#131b2e]/30 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-[#9c48ea] h-full" style="width: {{ $totalAspirasi > 0 ? min(100, round(($ideCount / $totalAspirasi) * 100)) : 14 }}%;"></div>
                    </div>
                </div>

                {{-- Metric 6: Kritik Siaran --}}
                <div class="flex flex-col p-4 bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e] hover:shadow-[4px_4px_0px_#131b2e] transition-all">
                    <div class="flex items-center justify-between text-[#424936] mb-1">
                        <span class="text-[10px] uppercase font-mono font-bold tracking-wide">Kritik Siaran</span>
                        <span class="material-symbols-outlined text-lg text-[#ba1a1a]">campaign</span>
                    </div>
                    <div class="text-2xl font-black text-[#131b2e] font-display">{{ $kritikCount }}</div>
                    <div class="w-full bg-[#eaedff] border border-[#131b2e]/30 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-[#ffdad6] h-full" style="width: {{ $totalAspirasi > 0 ? min(100, round(($kritikCount / $totalAspirasi) * 100)) : 11 }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- 3. Main 2-Column Section (List + Sticky Detail Panel) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- LEFT COLUMN: Filter Tabs, Search & Feedback Cards (Col 7 / 8) --}}
                <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4">
                    
                    {{-- Filter Tabs & Search Bar --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border-2 border-[#131b2e] p-2.5 rounded-xl shadow-[3px_3px_0px_#131b2e]">
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0" id="tab-container">
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#131b2e] text-white border border-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all shadow-sm" data-category="all">
                                Semua ({{ $totalAspirasi }})
                            </button>
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#e2e7ff] text-[#424936] hover:text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-1.5" data-category="unread">
                                <span>Belum Dibaca</span>
                                @if($unreadCount > 0)
                                    <span class="px-1.5 py-0.2 bg-[#ae3115] text-white rounded-full text-[10px] font-mono">{{ $unreadCount }}</span>
                                @endif
                            </button>
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#e2e7ff] text-[#424936] hover:text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all" data-category="saran">
                                Saran
                            </button>
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#e2e7ff] text-[#424936] hover:text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all" data-category="kritik">
                                Kritik
                            </button>
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#e2e7ff] text-[#424936] hover:text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all" data-category="masalah">
                                Laporan Masalah
                            </button>
                            <button type="button" class="filter-tab px-3 py-1.5 rounded-lg bg-[#e2e7ff] text-[#424936] hover:text-[#131b2e] font-bold text-xs uppercase tracking-wider transition-all" data-category="fitur">
                                Permintaan Fitur
                            </button>
                        </div>

                        {{-- Search Input --}}
                        <div class="relative min-w-[210px]">
                            <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[#424936] text-lg">search</span>
                            <input type="text"
                                   id="feedback-search"
                                   placeholder="Cari siswa, kelas, isi..."
                                   class="w-full pl-9 pr-3 py-1.5 rounded-lg bg-[#f2f3ff] border border-[#131b2e] text-[#131b2e] text-xs focus:outline-none focus:bg-white transition-colors">
                        </div>
                    </div>

                    {{-- Feedback Cards List --}}
                    <div class="flex flex-col gap-3" id="feedback-list">
                        @forelse($feedbacks as $index => $fb)
                            @php
                                $userName = $fb->user ? $fb->user->name : 'Siswa Anonim';
                                $userMeta = ($fb->user && $fb->user->kelas ? $fb->user->kelas : 'Siswa') . ' (NIS: ' . ($fb->user ? $fb->user->nis : '-') . ')';
                                $initials = $fb->initials;
                                
                                // Category styling
                                $catLower = strtolower($fb->kategori);
                                if (str_contains($catLower, 'fitur') || str_contains($catLower, 'ide')) {
                                    $badgeClass = 'bg-[#f0dbff] text-[#2c0051] border border-[#131b2e]';
                                    $avatarClass = 'bg-[#f0dbff] text-[#2c0051] border border-[#131b2e]';
                                    $catKey = 'fitur';
                                } elseif (str_contains($catLower, 'masalah') || str_contains($catLower, 'teknis')) {
                                    $badgeClass = 'bg-[#ffdad2] text-[#3d0600] border border-[#131b2e]';
                                    $avatarClass = 'bg-[#ffdad2] text-[#3d0600] border border-[#131b2e]';
                                    $catKey = 'masalah';
                                } elseif (str_contains($catLower, 'kritik')) {
                                    $badgeClass = 'bg-[#ffdad6] text-[#93000a] border border-[#131b2e]';
                                    $avatarClass = 'bg-[#ffdad6] text-[#93000a] border border-[#131b2e]';
                                    $catKey = 'kritik';
                                } else {
                                    $badgeClass = 'bg-[#a3e635] text-[#131b2e] border border-[#131b2e]';
                                    $avatarClass = 'bg-[#a3e635] text-[#131b2e] border border-[#131b2e]';
                                    $catKey = 'saran';
                                }

                                $timeFormatted = $fb->created_at ? $fb->created_at->locale('id')->diffForHumans() : 'Baru saja';
                            @endphp

                            <div class="feedback-card group p-4 rounded-xl bg-white border-2 border-[#131b2e] shadow-[3px_3px_0px_#131b2e] hover:shadow-[5px_5px_0px_#131b2e] transition-all cursor-pointer relative {{ $index === 0 ? 'ring-2 ring-[#8127cf] bg-[#f2f3ff]' : '' }}"
                                 data-id="{{ $fb->id }}"
                                 data-sender="{{ $userName }}"
                                 data-meta="{{ $userMeta }}"
                                 data-category="{{ $catKey }}"
                                 data-category-label="{{ $fb->kategori }}"
                                 data-badge-class="{{ $badgeClass }}"
                                 data-title="{{ $fb->judul }}"
                                 data-body="{{ $fb->pesan }}"
                                 data-reply="{{ $fb->balasan ?? '' }}"
                                 data-pic="{{ $fb->pic_osis ?? 'DJ OSIS Audio' }}"
                                 data-status="{{ $fb->status_tindak_lanjut ?? 'Menunggu Tinjauan' }}"
                                 data-unread="{{ $fb->is_read ? 'false' : 'true' }}"
                                 data-time="{{ $timeFormatted }}">
                                
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        {{-- Avatar Initial --}}
                                        <div class="w-10 h-10 rounded-full {{ $avatarClass }} flex items-center justify-center font-bold font-display text-sm uppercase shadow-sm flex-shrink-0">
                                            {{ $initials }}
                                        </div>

                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="font-bold text-[#131b2e] text-sm font-display">{{ $userName }}</span>
                                                <span class="px-2 py-0.5 rounded bg-[#e2e7ff] text-[#424936] text-[10px] font-mono font-bold">{{ $userMeta }}</span>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-black {{ $badgeClass }}">{{ $fb->kategori }}</span>
                                            </div>

                                            <div class="flex items-center gap-2 mt-1">
                                                @if(!$fb->is_read)
                                                    <span class="w-2 h-2 rounded-full bg-[#8127cf] animate-ping"></span>
                                                    <span class="text-xs font-black text-[#8127cf] font-mono">Belum Dibaca</span>
                                                    <span class="text-[#424936] text-xs">• {{ $timeFormatted }}</span>
                                                @else
                                                    <span class="text-[#424936] text-xs font-mono">Sudah Dibaca • {{ $timeFormatted }}</span>
                                                    @if($fb->pic_osis)
                                                        <span class="px-1.5 py-0.2 rounded bg-[#eaedff] border border-[#131b2e]/20 text-[#8127cf] text-[10px] font-bold font-mono">{{ $fb->pic_osis }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Quick Actions --}}
                                    <div class="flex items-center gap-1">
                                        {{-- Toggle Read --}}
                                        <form method="POST" action="{{ route('admin.feedbacks.toggle', $fb) }}" class="inline" onclick="event.stopPropagation();">
                                            @csrf
                                            <button type="submit"
                                                    class="p-1.5 rounded-lg bg-[#eaedff] hover:bg-[#a3e635] text-[#131b2e] border border-[#131b2e] transition-colors shadow-sm"
                                                    title="{{ $fb->is_read ? 'Tandai Belum Dibaca' : 'Tandai Telah Dibaca' }}">
                                                <span class="material-symbols-outlined text-base">check</span>
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.feedbacks.destroy', $fb) }}"
                                              onsubmit="return confirm('Hapus aspirasi ini?')"
                                              class="inline" onclick="event.stopPropagation();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 rounded-lg bg-[#eaedff] hover:bg-[#ffdad6] text-[#ba1a1a] border border-[#131b2e] transition-colors shadow-sm"
                                                    title="Hapus Aspirasi">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Card Body snippet --}}
                                <div class="mt-2 pl-0 sm:pl-13">
                                    <h3 class="text-sm font-black text-[#131b2e] font-display group-hover:text-[#8127cf] transition-colors">
                                        {{ $fb->judul }}
                                    </h3>
                                    <p class="text-xs text-[#424936] mt-1 line-clamp-2 leading-relaxed">
                                        {{ $fb->pesan }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white border-2 border-[#131b2e] rounded-xl shadow-[3px_3px_0px_#131b2e]">
                                <span class="material-symbols-outlined text-4xl text-[#727a64] mb-2">inbox</span>
                                <p class="text-sm font-bold text-[#424936]">Belum ada data kritik atau saran siswa.</p>
                                <p class="text-xs text-[#727a64] mt-1">Aspirasi yang dikirim siswa melalui portal akan muncul di sini secara otomatis.</p>
                            </div>
                        @endforelse
                    </div>

                </div>

                {{-- RIGHT COLUMN: Sticky Detail Masukan & Tindak Lanjut (Col 5 / 4) --}}
                <div class="lg:col-span-5 xl:col-span-4 sticky top-24 flex flex-col gap-4">
                    
                    @php
                        $firstFb = $selectedFeedback ?? $feedbacks->first();
                    @endphp

                    {{-- Main Detail Card --}}
                    <div class="bg-white border-2 border-[#131b2e] rounded-xl shadow-[4px_4px_0px_#131b2e] p-5 flex flex-col gap-4">
                        
                        {{-- Detail Header --}}
                        <div class="flex items-center justify-between pb-2 border-b border-[#131b2e]/10">
                            <span class="text-xs uppercase font-mono tracking-wider text-[#8127cf] font-black flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">rate_review</span>
                                Detail Masukan &amp; Tindak Lanjut
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-black bg-[#f0dbff] text-[#2c0051] border border-[#131b2e]" id="detail-category-badge">
                                {{ $firstFb ? $firstFb->kategori : 'Permintaan Fitur' }}
                            </span>
                        </div>

                        {{-- Sender Profile Info --}}
                        <div class="flex flex-col gap-1 p-3 bg-[#f2f3ff] border border-[#131b2e] rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-sm text-[#131b2e] font-display" id="detail-sender">
                                    {{ $firstFb && $firstFb->user ? $firstFb->user->name : 'Dimas Anggara' }}
                                </span>
                                <span class="text-[11px] text-[#424936] font-mono" id="detail-time">
                                    {{ $firstFb && $firstFb->created_at ? $firstFb->created_at->diffForHumans() : 'Hari ini' }}
                                </span>
                            </div>
                            <span class="text-xs text-[#424936] font-mono" id="detail-meta">
                                {{ $firstFb && $firstFb->user ? ($firstFb->user->kelas . ' • NIS ' . $firstFb->user->nis) : 'XI RPL 1 • NIS 14144' }}
                            </span>
                        </div>

                        {{-- Message Content --}}
                        <div class="flex flex-col gap-1.5">
                            <h2 class="text-base font-black text-[#131b2e] font-display leading-snug" id="detail-title">
                                {{ $firstFb ? $firstFb->judul : 'Tambahin fitur playlist kolaboratif bareng satu kelas min!' }}
                            </h2>
                            <p class="text-xs text-[#424936] bg-[#faf8ff] border border-[#131b2e]/30 p-3 rounded-lg whitespace-pre-line leading-relaxed max-h-48 overflow-y-auto" id="detail-body">
                                {{ $firstFb ? $firstFb->pesan : 'Halo min DJ, seru banget kalau tiap kelas ada jatah satu lagu per minggu yang di-vote bareng-bareng di kelas biar gak rebutan kuota per siswa.' }}
                            </p>
                        </div>

                        {{-- Action & Response Form --}}
                        <form id="detail-action-form" method="POST" action="{{ $firstFb ? route('admin.feedbacks.reply', $firstFb) : '#' }}" class="flex flex-col gap-3 pt-1">
                            @csrf
                            
                            {{-- Disposisi & Delegasi PIC OSIS --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] uppercase font-mono font-bold tracking-wider text-[#424936]">
                                    Disposisi &amp; Delegasi PIC OSIS
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="pic_osis" id="select-pic" class="px-2.5 py-1.5 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] text-[#131b2e] text-xs font-mono font-bold focus:outline-none focus:ring-1 focus:ring-[#8127cf]">
                                        <option value="DJ OSIS Audio" {{ $firstFb && $firstFb->pic_osis === 'DJ OSIS Audio' ? 'selected' : '' }}>DJ OSIS Audio</option>
                                        <option value="Sie Publikasi" {{ $firstFb && $firstFb->pic_osis === 'Sie Publikasi' ? 'selected' : '' }}>Sie Publikasi</option>
                                        <option value="Sie Perlengkapan" {{ $firstFb && $firstFb->pic_osis === 'Sie Perlengkapan' ? 'selected' : '' }}>Sie Perlengkapan</option>
                                        <option value="Ketua OSIS" {{ $firstFb && $firstFb->pic_osis === 'Ketua OSIS' ? 'selected' : '' }}>Ketua OSIS</option>
                                    </select>
                                    <select name="status_tindak_lanjut" id="select-status" class="px-2.5 py-1.5 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] text-[#131b2e] text-xs font-mono font-bold focus:outline-none focus:ring-1 focus:ring-[#8127cf]">
                                        <option value="Menunggu Tinjauan" {{ $firstFb && $firstFb->status_tindak_lanjut === 'Menunggu Tinjauan' ? 'selected' : '' }}>Menunggu Tinjauan</option>
                                        <option value="Dalam Pengerjaan" {{ $firstFb && $firstFb->status_tindak_lanjut === 'Dalam Pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                                        <option value="Selesai / Direspons" {{ $firstFb && $firstFb->status_tindak_lanjut === 'Selesai / Direspons' ? 'selected' : '' }}>Selesai / Direspons</option>
                                        <option value="Ditolak" {{ $firstFb && $firstFb->status_tindak_lanjut === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Catatan Internal OSIS / Draft Balasan --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="reply-notes" class="text-[10px] uppercase font-mono font-bold tracking-wider text-[#424936]">
                                    Catatan Internal OSIS / Draft Balasan
                                </label>
                                <textarea name="balasan"
                                          id="reply-notes"
                                          rows="3"
                                          placeholder="Ketik balasan untuk siswa atau catatan koordinasi tim siaran..."
                                          class="w-full p-2.5 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] text-[#131b2e] text-xs leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#8127cf]">{{ $firstFb ? $firstFb->balasan : '' }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-center gap-2 pt-1">
                                <button type="submit"
                                        id="btn-save-action"
                                        class="flex-1 py-2.5 rounded-lg bg-[#446900] text-white border-2 border-[#131b2e] hover:bg-[#355200] font-black text-xs uppercase tracking-wider transition-all shadow-[2px_2px_0px_#131b2e] flex items-center justify-center gap-1.5 active:translate-y-0.5">
                                    <span class="material-symbols-outlined text-base">send</span>
                                    <span>Simpan &amp; Kirim Update</span>
                                </button>
                                <button type="button"
                                        onclick="alert('Catatan disimpan ke bookmark browser!')"
                                        class="p-2.5 rounded-lg bg-[#eaedff] border-2 border-[#131b2e] hover:bg-[#dae2fd] text-[#131b2e] transition-colors shadow-[2px_2px_0px_#131b2e]"
                                        title="Bookmark Catatan">
                                    <span class="material-symbols-outlined text-base">bookmark</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Jadwal Evaluasi Siaran Card --}}
                    <div class="bg-white border-2 border-[#131b2e] p-4 rounded-xl shadow-[3px_3px_0px_#131b2e] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] flex items-center justify-center font-bold">
                                <span class="material-symbols-outlined text-base">podcasts</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-[#131b2e] font-display">Jadwal Evaluasi Siaran</span>
                                <span class="text-[11px] text-[#424936] font-mono">Jumat ini, 15:30 @ Studio OSIS</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-[#f0dbff] text-[#2c0051] border border-[#131b2e] text-[10px] font-mono font-bold">
                            Rapat Rutin
                        </span>
                    </div>

                </div>

            </div>

        </main>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     INTERACTION SCRIPT
══════════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const feedbackCards = document.querySelectorAll('.feedback-card');
        const tabButtons = document.querySelectorAll('.filter-tab');
        const searchInput = document.getElementById('feedback-search');
        
        const detailActionForm = document.getElementById('detail-action-form');
        const detailCategoryBadge = document.getElementById('detail-category-badge');
        const detailSender = document.getElementById('detail-sender');
        const detailMeta = document.getElementById('detail-meta');
        const detailTime = document.getElementById('detail-time');
        const detailTitle = document.getElementById('detail-title');
        const detailBody = document.getElementById('detail-body');
        const replyNotes = document.getElementById('reply-notes');
        const selectPic = document.getElementById('select-pic');
        const selectStatus = document.getElementById('select-status');

        // Card Selection Handler
        feedbackCards.forEach(card => {
            card.addEventListener('click', () => {
                feedbackCards.forEach(c => {
                    c.classList.remove('ring-2', 'ring-[#8127cf]', 'bg-[#f2f3ff]');
                });
                card.classList.add('ring-2', 'ring-[#8127cf]', 'bg-[#f2f3ff]');

                // Update detail view contents
                if (detailCategoryBadge) {
                    detailCategoryBadge.textContent = card.dataset.categoryLabel;
                    detailCategoryBadge.className = `px-2 py-0.5 rounded text-[10px] font-mono font-black ${card.dataset.badgeClass}`;
                }
                
                if (detailSender) detailSender.textContent = card.dataset.sender;
                if (detailMeta) detailMeta.textContent = card.dataset.meta;
                if (detailTime) detailTime.textContent = card.dataset.time;
                if (detailTitle) detailTitle.textContent = card.dataset.title;
                if (detailBody) detailBody.textContent = card.dataset.body;
                
                if (replyNotes) {
                    replyNotes.value = card.dataset.reply || '';
                    replyNotes.placeholder = `Catatan internal untuk masukan dari ${card.dataset.sender}...`;
                }

                if (selectPic && card.dataset.pic) {
                    selectPic.value = card.dataset.pic;
                }
                if (selectStatus && card.dataset.status) {
                    selectStatus.value = card.dataset.status;
                }

                // Update action form route to this feedback ID
                if (detailActionForm && card.dataset.id) {
                    detailActionForm.action = `/admin/feedbacks/${card.dataset.id}/reply`;
                }
            });
        });

        // Tabs Filter Handler
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => {
                    b.classList.remove('bg-[#131b2e]', 'text-white');
                    b.classList.add('bg-[#e2e7ff]', 'text-[#424936]');
                });
                btn.classList.add('bg-[#131b2e]', 'text-white');
                btn.classList.remove('bg-[#e2e7ff]', 'text-[#424936]');

                const cat = btn.dataset.category;
                feedbackCards.forEach(card => {
                    if (cat === 'all') {
                        card.style.display = 'block';
                    } else if (cat === 'unread') {
                        card.style.display = card.dataset.unread === 'true' ? 'block' : 'none';
                    } else {
                        card.style.display = card.dataset.category === cat ? 'block' : 'none';
                    }
                });
            });
        });

        // Search Input Filter
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                feedbackCards.forEach(card => {
                    const text = (card.dataset.sender + ' ' + card.dataset.meta + ' ' + card.dataset.title + ' ' + card.dataset.body).toLowerCase();
                    if (text.includes(query)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
</x-admin-layout>
