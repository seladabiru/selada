<x-app-layout>
    <div class="py-4 sm:py-6" x-data="{
        activeTab: 'antrian',
        setTab(tab) {
            this.activeTab = tab;
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, null, '#' + tab);
            } else {
                window.location.hash = tab;
            }
            window.dispatchEvent(new Event('hashchange'));
        },
        syncFromHash() {
            let hash = (window.location.hash || '').replace(/^#/, '');
            if (hash === 'tophits') hash = 'populer';
            if (['antrian', 'my_songs', 'populer', 'riwayat'].includes(hash)) {
                this.activeTab = hash;
            }
        },
        init() {
            this.syncFromHash();
            window.addEventListener('hashchange', () => this.syncFromHash());
            window.setActiveTab = (tab) => this.setTab(tab);
        }
    }">
        <div class="max-w-md sm:max-w-xl md:max-w-2xl mx-auto px-3 sm:px-4 space-y-3.5 pb-20">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-3 bg-[#a3e635]/20 border border-[#a3e635] text-[#416400] rounded-[12px] text-xs font-sans font-semibold flex items-center gap-2 shadow-xs">
                    <span class="text-sm">✨</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="p-3 bg-[#ffdad6] border border-[#ffb4ab] text-[#93000a] rounded-[12px] text-xs font-sans font-semibold flex items-center gap-2 shadow-xs">
                    <span class="text-sm">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- 1. TOP MARQUEE TICKER (BROADCAST OSIS) --}}
            <div class="bg-[#a3e635] p-1 rounded-[12px] shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] flex items-center gap-2 overflow-hidden">
                <div class="bg-[#faf8ff] rounded-[8px] px-2 py-1 flex items-center gap-1.5 shadow-xs shrink-0">
                    <div class="size-2.5 rounded-full bg-[#ba1a1a] animate-pulse"></div>
                    <span class="font-display font-bold text-[11px] tracking-wider text-[#131b2e] uppercase whitespace-nowrap">
                        BROADCAST OSIS
                    </span>
                </div>
                <div class="overflow-hidden whitespace-nowrap pr-2">
                    <p class="font-sans font-bold text-[13px] text-[#416400] truncate">
                        📻 Istirahat Siang Edisi Pop Senja — Request lagumu sekarang!
                    </p>
                </div>
            </div>

            {{-- 2. NOW PLAYING HERO CARD --}}
            @php
                $sedangDiputar = $antrian->firstWhere('status', 'diputar');
            @endphp
            <div class="bg-white rounded-[12px] p-4 shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] border border-[#131b2e]/5 flex flex-col gap-3 relative">
                {{-- Ambient badge & live indicator --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="-rotate-1">
                            @if($isOnAir ?? false)
                            <div class="bg-[#ffdad6] shadow-xs px-2.5 py-1 rounded-full flex items-center gap-1.5">
                                <div class="size-2 rounded-full bg-[#ba1a1a] animate-ping"></div>
                                <span class="font-display font-bold text-[11px] tracking-wider text-[#93000a] whitespace-nowrap">
                                    LIVE ON AIR 🔴
                                </span>
                            </div>
                            @else
                            <div class="bg-[#e2e7ff] shadow-xs px-2.5 py-1 rounded-full flex items-center gap-1.5">
                                <div class="size-2 rounded-full bg-[#727a64]"></div>
                                <span class="font-display font-bold text-[11px] tracking-wider text-[#424936] whitespace-nowrap">
                                    OFF AIR
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="bg-[#e2e7ff] px-2.5 py-1 rounded-full">
                            <span class="font-display font-bold text-[11px] tracking-wider text-[#131b2e] whitespace-nowrap">
                                STUDIO SELADA
                            </span>
                        </div>
                    </div>

                    {{-- Animated SVG Equalizer --}}
                    <div class="bg-[#eaedff] px-2 py-1 rounded-[8px] flex items-end gap-1 h-5 shrink-0" title="Equalizer Aktif">
                        <div class="w-1 bg-[#446900] rounded-full animate-eq-1"></div>
                        <div class="w-1 bg-[#8127cf] rounded-full animate-eq-2"></div>
                        <div class="w-1 bg-[#ae3115] rounded-full animate-eq-3"></div>
                        <div class="w-1 bg-[#446900] rounded-full animate-eq-4"></div>
                    </div>
                </div>

                {{-- Song Info & Vinyl Artwork --}}
                <div class="flex items-center gap-3.5 pt-1">
                    <div class="size-16 rounded-[12px] bg-[#e2e7ff] relative overflow-hidden shrink-0 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1)]">
                        @if ($sedangDiputar && $sedangDiputar->thumbnail)
                            <img src="{{ $sedangDiputar->thumbnail }}" alt="{{ $sedangDiputar->judul }}" class="size-full object-cover">
                        @else
                            <img src="{{ asset('images/figma/default_rehat.png') }}" alt="Album Art" class="size-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent flex items-center justify-center">
                            <img src="{{ asset('images/figma/icon-play.svg') }}" class="w-4 h-4 drop-shadow" alt="Play">
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-display font-bold text-lg text-[#131b2e] leading-snug truncate max-w-[200px] sm:max-w-xs">
                                {{ $sedangDiputar ? $sedangDiputar->judul : 'Rehat' }}
                            </h3>
                            <span class="bg-[#f0dbff] text-[#2c0051] font-display font-bold text-[10px] tracking-wider px-2 py-0.5 rounded-full shrink-0">
                                {{ $sedangDiputar ? 'Sedang Diputar' : 'Official OSIS Pick' }}
                            </span>
                        </div>

                        <p class="font-sans font-medium text-sm text-[#424936] truncate">
                            {{ $sedangDiputar ? ($sedangDiputar->channel ?: 'Kunto Aji') : 'Kunto Aji' }}
                        </p>

                        <div class="flex items-center gap-1.5 text-xs text-[#424936] font-sans font-medium mt-1">
                            <img src="{{ asset('images/figma/icon-target.svg') }}" class="w-3.5 h-3.5 shrink-0" alt="">
                            <span class="truncate">
                                Untuk: <strong class="text-[#131b2e] font-bold">{{ $sedangDiputar?->user?->name ?: 'Rara' }}</strong>
                                ({{ $sedangDiputar?->user?->kelas ?: 'XII MIPA 3' }})
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Dedication Sticky Note --}}
                <div class="bg-[#f2f3ff] rounded-[12px] p-2.5 flex items-start gap-2 shadow-[0px_1px_1px_rgba(0,0,0,0.05)] border border-[#131b2e]/5">
                    <img src="{{ asset('images/figma/icon-quote.svg') }}" class="w-3.5 h-3.5 mt-0.5 shrink-0" alt="">
                    <p class="font-sans italic font-medium text-xs sm:text-[13px] text-[#131b2e] leading-relaxed">
                        “{{ $sedangDiputar && $sedangDiputar->pesan ? $sedangDiputar->pesan : 'Semangat tryout-nya ya anak-anak XII! Jangan lupa makan siang bestie ✨' }}”
                    </p>
                </div>

                {{-- Song Progress Bar --}}
                <div class="flex flex-col gap-1.5 pt-1">
                    <div class="h-2 bg-[#dae2fd] rounded-full p-0.5 relative shadow-inner overflow-hidden flex items-center">
                        <div class="bg-[#a3e635] h-full rounded-full w-2/3 flex items-center justify-end pr-0.5">
                            <div class="size-1.5 bg-[#446900] rounded-full shadow-xs"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[#424936] font-display font-bold text-[11px] tracking-wider">
                        <span>02:45</span>
                        <div class="flex items-center gap-1 text-[#446900]">
                            <div class="size-1.5 bg-[#446900] rounded-full animate-pulse"></div>
                            <span>Sedang Mengudara</span>
                        </div>
                        <span>{{ $sedangDiputar && $sedangDiputar->durasi > 0 ? $sedangDiputar->durasiFormatted() : '04:12' }}</span>
                    </div>
                </div>
            </div>

            {{-- 3. NOTICE BANNER INFO & QUOTA --}}
            <div class="bg-[#f0dbff] rounded-[12px] p-2.5 flex items-center justify-between shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1)] gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <img src="{{ asset('images/figma/icon-live.svg') }}" class="w-4 h-4 shrink-0" alt="">
                    <div class="flex flex-col min-w-0">
                        <span class="font-display font-bold text-xs text-[#2c0051] tracking-wider leading-tight">
                            Live Polling Aktif • Sync 10s
                        </span>
                        <span class="font-sans font-medium text-xs text-[#2c0051]/90 leading-tight truncate">
                            Sisa Kuota Request: <strong>{{ $sisaKuota }}/{{ $batasPerHari }}</strong>
                            ({{ $sisaKuota > 0 ? 'Tersedia' : 'Sudah Terpakai' }})
                        </span>
                    </div>
                </div>
                <div class="bg-[#faf8ff] rounded-full px-2.5 py-1 text-[11px] font-display font-bold text-[#131b2e] shadow-xs shrink-0 whitespace-nowrap">
                    Reset 15:00
                </div>
            </div>

            {{-- 5. TAB: ANTRIAN AKTIF --}}
            <div id="section-antrian" x-show="activeTab === 'antrian'" class="space-y-3">
                {{-- Queue Section Header --}}
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2">
                        <h2 class="font-display font-bold text-base sm:text-lg text-[#131b2e]">
                            Antrian Berikutnya
                        </h2>
                        <span class="bg-[#dae2fd] text-[#424936] font-display font-bold text-[10px] tracking-wider px-2 py-0.5 rounded-full">
                            Vote Tertinggi Main Duluan
                        </span>
                    </div>

                    <button type="button"
                            onclick="pollAntrian(true)"
                            id="btn-segarkan"
                            class="flex items-center gap-1 text-[#446900] font-display font-bold text-xs hover:opacity-80 transition cursor-pointer">
                        <img src="{{ asset('images/figma/icon-refresh.svg') }}" id="icon-refresh" class="w-3 h-3 transition-transform" alt="">
                        <span>Segarkan</span>
                    </button>
                </div>

                {{-- Song List --}}
                <div id="antrian-container">
                    @include('songs._antrian_list', ['antrian' => $antrian])
                </div>
            </div>

            {{-- 6. TAB: REQUEST SAYA --}}
            <div id="section-my_songs" x-show="activeTab === 'my_songs'" style="display: none;" class="space-y-3">
                <div class="flex items-center justify-between pt-1">
                    <h2 class="font-display font-bold text-base sm:text-lg text-[#131b2e]">
                        Riwayat Request Saya
                    </h2>
                    <span class="text-xs text-[#727a64] font-sans">
                        Total: {{ $mySongs->count() }} request
                    </span>
                </div>

                @if ($mySongs->isEmpty())
                    <div class="p-8 text-center bg-white rounded-[12px] shadow-xs border border-[#131b2e]/5">
                        <p class="text-3xl mb-2">🎧</p>
                        <p class="font-display font-bold text-sm text-[#131b2e]">Kamu belum mengajukan lagu</p>
                        <p class="font-sans text-xs text-[#727a64] mt-1">Ayo request lagu favoritmu untuk diputar saat istirahat!</p>
                        @if (!auth()->user()->isAdmin())
                            <a href="{{ route('songs.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-[#a3e635] text-[#416400] font-display font-bold rounded-full text-xs hover:brightness-105 transition shadow-xs">
                                <span>⚡</span> Ajukan Lagu Sekarang
                            </a>
                        @endif
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($mySongs as $song)
                            <div class="bg-white rounded-[12px] p-3 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] border border-[#131b2e]/5 flex flex-col gap-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="size-12 rounded-[8px] bg-[#eaedff] relative overflow-hidden shrink-0 shadow-xs">
                                            <img src="{{ $song->thumbnail ?: 'https://img.youtube.com/vi/'.$song->youtube_id.'/mqdefault.jpg' }}"
                                                 alt="" class="size-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-display font-bold text-sm text-[#131b2e] truncate">
                                                {{ $song->judul }}
                                            </h4>
                                            <p class="font-sans font-medium text-xs text-[#424936] truncate">
                                                {{ $song->channel ?: '—' }} · {{ $song->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <span class="font-display font-bold text-[10px] tracking-wider px-2 py-0.5 rounded-full shrink-0
                                        @if ($song->status === 'menunggu') bg-[#fff1c5] text-[#7d5700]
                                        @elseif ($song->status === 'disetujui') bg-[#dae2fd] text-[#131b2e]
                                        @elseif ($song->status === 'diputar') bg-[#a3e635] text-[#416400]
                                        @elseif ($song->status === 'selesai') bg-[#f2f3ff] text-[#424936]
                                        @elseif ($song->status === 'ditolak') bg-[#ffdad6] text-[#93000a]
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $song->statusLabel() }}
                                    </span>
                                </div>

                                @if ($song->pesan)
                                    <div class="bg-[#f2f3ff] rounded-[8px] px-2.5 py-1.5 flex items-center gap-1.5 text-xs font-sans font-medium text-[#424936] truncate">
                                        <img src="{{ asset('images/figma/icon-msg.svg') }}" class="w-3.5 h-3.5 shrink-0" alt="">
                                        <span class="truncate">“{{ $song->pesan }}”</span>
                                    </div>
                                @endif

                                @if ($song->status === 'ditolak' && $song->alasan_ditolak)
                                    <div class="p-2 rounded-[8px] bg-[#ffdad6]/60 border border-[#ffb4ab] text-[#93000a] text-xs font-sans">
                                        <strong>Catatan OSIS:</strong> {{ $song->alasan_ditolak }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 7. TAB: TOP HITS --}}
            <div id="section-populer" x-show="activeTab === 'populer'" style="display: none;" class="space-y-3">
                <div class="flex items-center justify-between pt-1">
                    <h2 class="font-display font-bold text-base sm:text-lg text-[#131b2e]">
                        🔥 Lagu Terpopuler di Sekolah
                    </h2>
                    <a href="{{ route('songs.top-hits') }}" class="text-xs text-[#446900] font-display font-bold hover:underline flex items-center gap-1">
                        <span>Chart Lengkap</span>
                        <span class="text-xs">→</span>
                    </a>
                </div>

                @if ($topSongs->isEmpty())
                    <div class="p-8 text-center bg-white rounded-[12px] shadow-xs border border-[#131b2e]/5">
                        <p class="text-3xl mb-2">⭐</p>
                        <p class="font-display font-bold text-sm text-[#131b2e]">Belum ada statistik lagu</p>
                        <p class="font-sans text-xs text-[#727a64] mt-1">Daftar ini akan terisi otomatis setelah beberapa lagu selesai diputar.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($topSongs as $idx => $ts)
                            <div class="bg-white rounded-[12px] p-3 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] border border-[#131b2e]/5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="size-10 rounded-[8px] bg-[#eaedff] relative overflow-hidden shrink-0 shadow-xs">
                                        <img src="{{ $ts->thumbnail ?: 'https://img.youtube.com/vi/'.$ts->youtube_id.'/mqdefault.jpg' }}" alt="" class="size-full object-cover">
                                        <div class="absolute top-0.5 left-0.5 size-4 rounded-full flex items-center justify-center font-display font-bold text-[9px] {{ $idx === 0 ? 'bg-[#a3e635] text-[#416400]' : 'bg-[#dae2fd] text-[#131b2e]' }}">
                                            {{ $idx + 1 }}
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-display font-bold text-sm text-[#131b2e] truncate">
                                            {{ $ts->judul }}
                                        </h4>
                                        <p class="font-sans font-medium text-xs text-[#424936] truncate">
                                            {{ $ts->channel ?: '—' }}
                                        </p>
                                    </div>
                                </div>

                                <span class="bg-[#ffc5b8] text-[#a82d11] font-display font-bold text-xs px-2.5 py-1 rounded-full shrink-0 shadow-xs">
                                    {{ $ts->request_count }}x diputar
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 8. TAB: RIWAYAT SIARAN --}}
            <div id="section-riwayat" x-show="activeTab === 'riwayat'" style="display: none;" class="space-y-3">
                <div class="flex items-center justify-between pt-1">
                    <h2 class="font-display font-bold text-base sm:text-lg text-[#131b2e]">
                        Riwayat Siaran Terakhir
                    </h2>
                    <span class="text-xs text-[#727a64] font-sans">
                        Arsip lagu
                    </span>
                </div>

                @if ($riwayat->isEmpty())
                    <div class="p-8 text-center bg-white rounded-[12px] shadow-xs border border-[#131b2e]/5 text-xs text-[#727a64]">
                        Belum ada riwayat siaran.
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach ($riwayat as $song)
                            <div class="bg-white rounded-[12px] p-3 shadow-xs border border-[#131b2e]/5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <img src="{{ $song->thumbnail }}" alt="" class="w-12 h-9 object-cover rounded-[6px] shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-display font-bold text-xs sm:text-sm text-[#131b2e] truncate">
                                            {{ $song->judul }}
                                        </h4>
                                        <p class="font-sans text-[11px] text-[#727a64] truncate">
                                            {{ $song->channel }} · Oleh {{ $song->user?->name }}
                                            @if ($song->durasi > 0) · {{ $song->durasiFormatted() }} @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="font-display font-bold text-[10px] px-2 py-0.5 rounded-full shrink-0
                                    @if ($song->status === 'selesai') bg-[#f2f3ff] text-[#424936]
                                    @elseif ($song->status === 'ditolak') bg-[#ffdad6] text-[#93000a]
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $song->statusLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 9. STICKY BOTTOM REQUEST TRIGGER BUTTON CONTAINER --}}
            @if (!auth()->user()->isAdmin())
                <div class="pt-2">
                    <a href="{{ route('songs.create') }}"
                       class="bg-[#a3e635] rounded-[12px] p-2.5 flex items-center justify-between shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] hover:brightness-105 transition active:scale-[0.99] block border border-[#446900]/10">
                        <div class="flex items-center gap-2.5">
                            <div class="size-10 rounded-full bg-[#faf8ff] flex items-center justify-center shadow-xs shrink-0">
                                <img src="{{ asset('images/figma/icon-queue.svg') }}" class="w-4 h-4" alt="">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-display font-bold text-base sm:text-lg text-[#416400] leading-tight">
                                    ⚡ Request Lagu Sekarang!
                                </span>
                                <span class="font-sans font-medium text-xs text-[#416400]/90 leading-tight">
                                    Kirim salam dan pesan ke seluruh sekolah
                                </span>
                            </div>
                        </div>
                        <div class="shrink-0 pr-1">
                            <img src="{{ asset('images/figma/icon-arrow-right.svg') }}" class="w-4 h-4" alt="Next">
                        </div>
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- Script AJAX Polling --}}
    <script>
    function pollAntrian(manual = false) {
        const container = document.getElementById('antrian-container');
        const iconRefresh = document.getElementById('icon-refresh');

        if (manual && iconRefresh) {
            iconRefresh.classList.add('rotate-180');
            setTimeout(() => iconRefresh.classList.remove('rotate-180'), 500);
        }

        fetch('{{ route('songs.index') }}?poll=1', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            if (html.trim() && container) {
                container.innerHTML = html;
            }
        })
        .catch(() => {});
    }

    // Polling setiap 10 detik (sesuai Sync 10s di banner)
    setInterval(() => pollAntrian(false), 10000);
    </script>
</x-app-layout>
