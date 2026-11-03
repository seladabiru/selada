<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <span>📻</span> Radio Selada
                </h2>
                <p class="text-xs text-gray-500 hidden sm:block mt-0.5">Sistem siaran lagu dan salam dedikasi siswa</p>
            </div>

            @if (!auth()->user()->isAdmin())
                <div class="flex items-center gap-2">
                    <span class="hidden md:inline-flex text-xs px-3 py-1.5 rounded-full font-medium {{ $sisaKuota > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        Kuota: <strong>{{ $requestHariIni }}/{{ $batasPerHari }}</strong> hari ini
                    </span>
                    <a href="{{ route('songs.create') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                        <span>+</span> Request Lagu
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-8" x-data="{ activeTab: 'antrian' }">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Live ON AIR Banner --}}
            @php
                $sedangDiputar = $antrian->firstWhere('status', 'diputar');
            @endphp
            @if ($sedangDiputar)
                <div class="p-4 sm:p-5 bg-gradient-to-br from-gray-950 via-slate-900 to-indigo-950 text-white rounded-2xl shadow-md border border-indigo-900/40 relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                            {{-- Thumbnail dengan badge live pulse --}}
                            <div class="relative flex-shrink-0">
                                @if ($sedangDiputar->thumbnail)
                                    <img src="{{ $sedangDiputar->thumbnail }}" alt="Now playing" class="w-20 h-14 sm:w-24 sm:h-16 object-cover rounded-xl shadow border border-white/10">
                                @else
                                    <div class="w-20 h-14 sm:w-24 sm:h-16 bg-gray-800 rounded-xl flex items-center justify-center text-xs text-gray-500">RADIO</div>
                                @endif
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                </span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-widest bg-rose-600 text-white flex items-center gap-1 shadow-xs">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        ON AIR
                                    </span>
                                    <span class="text-[11px] text-indigo-200 truncate">Sedang mengudara di sekolah</span>
                                </div>
                                <h3 class="font-bold text-white text-sm sm:text-base truncate mt-1">
                                    {{ $sedangDiputar->judul }}
                                </h3>
                                <p class="text-[11px] sm:text-xs text-indigo-200 truncate">
                                    {{ $sedangDiputar->channel ?: '—' }} · Pemohon: <strong class="text-white">{{ $sedangDiputar->user?->name }}</strong>
                                    @if ($sedangDiputar->user?->kelas) ({{ $sedangDiputar->user->kelas }}) @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Salam Dedikasi Siswa --}}
                    @if ($sedangDiputar->pesan)
                        <div class="mt-3 pt-2.5 border-t border-white/10 text-xs text-indigo-100 flex items-start gap-2 bg-white/5 p-2.5 rounded-xl">
                            <span class="text-sm">📢</span>
                            <div class="min-w-0">
                                <span class="font-semibold text-amber-300">Salam Dedikasi:</span>
                                <p class="italic text-gray-200 text-xs sm:text-sm mt-0.5">"{{ $sedangDiputar->pesan }}"</p>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-3.5 sm:p-4 bg-white rounded-2xl shadow-xs border border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-gray-300"></span>
                        <span class="truncate">Radio Sekolah Standby — Siaran dimulai saat jam istirahat.</span>
                    </div>
                    <span class="font-semibold text-indigo-600 hidden sm:inline">Selada FM</span>
                </div>
            @endif

            {{-- Kuota Bar Mobile --}}
            @if (!auth()->user()->isAdmin())
                <div class="sm:hidden flex items-center justify-between p-3 bg-indigo-50/60 rounded-xl text-xs text-indigo-900 border border-indigo-100">
                    <span>Status Kuota Hari Ini:</span>
                    <span class="font-bold {{ $sisaKuota > 0 ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $requestHariIni }} / {{ $batasPerHari }} request (Sisa: {{ $sisaKuota }})
                    </span>
                </div>
            @endif

            {{-- Navigation Tabs: Touch-Friendly for Mobile --}}
            <div class="flex items-center border-b border-gray-200 overflow-x-auto no-scrollbar gap-1 sm:gap-2">
                <button type="button"
                        @click="activeTab = 'antrian'"
                        :class="activeTab === 'antrian' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="px-3.5 py-2.5 text-xs sm:text-sm border-b-2 whitespace-nowrap transition flex items-center gap-1.5">
                    <span>📻</span> Antrian Aktif
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-indigo-100 text-indigo-700 font-bold">
                        {{ $antrian->count() }}
                    </span>
                </button>

                <button type="button"
                        @click="activeTab = 'my_songs'"
                        :class="activeTab === 'my_songs' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="px-3.5 py-2.5 text-xs sm:text-sm border-b-2 whitespace-nowrap transition flex items-center gap-1.5">
                    <span>🙋‍♂️</span> Request Saya
                    @if ($mySongs->isNotEmpty())
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-gray-100 text-gray-700 font-bold">
                            {{ $mySongs->count() }}
                        </span>
                    @endif
                </button>

                <button type="button"
                        @click="activeTab = 'populer'"
                        :class="activeTab === 'populer' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="px-3.5 py-2.5 text-xs sm:text-sm border-b-2 whitespace-nowrap transition flex items-center gap-1.5">
                    <span>🔥</span> Lagu Terpopuler
                </button>

                <button type="button"
                        @click="activeTab = 'riwayat'"
                        :class="activeTab === 'riwayat' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                        class="px-3.5 py-2.5 text-xs sm:text-sm border-b-2 whitespace-nowrap transition flex items-center gap-1.5">
                    <span>📜</span> Riwayat Siaran
                </button>
            </div>

            {{-- 1. TAB: ANTRIAN AKTIF --}}
            <div x-show="activeTab === 'antrian'" class="space-y-3">
                <div class="bg-white shadow-xs rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm sm:text-base text-gray-800">Daftar Antrian</h3>
                            <span class="text-xs text-gray-400">({{ $antrian->count() }} lagu)</span>
                        </div>
                        <span class="text-[11px] text-gray-400 hidden sm:inline" id="last-updated">
                            Live sync aktif
                        </span>
                    </div>

                    <div id="antrian-container">
                        @include('songs._antrian_list', ['antrian' => $antrian])
                    </div>
                </div>
            </div>

            {{-- 2. TAB: REQUEST SAYA (Riwayat Per Siswa) --}}
            <div x-show="activeTab === 'my_songs'" style="display: none;" class="space-y-3">
                <div class="bg-white shadow-xs rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-sm sm:text-base text-gray-800">Riwayat Pengajuan Lagu Saya</h3>
                        <span class="text-xs text-gray-400">Total: {{ $mySongs->count() }} request</span>
                    </div>

                    @if ($mySongs->isEmpty())
                        <div class="p-10 text-center text-gray-400">
                            <p class="text-4xl mb-2">🎧</p>
                            <p class="text-sm font-medium text-gray-600">Kamu belum pernah mengajukan lagu.</p>
                            <p class="text-xs text-gray-400 mt-1">Ayo ajukan lagu favoritmu untuk menemani jam istirahat sekolah!</p>
                            @if (!auth()->user()->isAdmin())
                                <a href="{{ route('songs.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition">
                                    + Ajukan Lagu Pertama
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($mySongs as $song)
                                <div class="p-3.5 sm:p-4 hover:bg-gray-50/70 transition">
                                    <div class="flex items-start gap-3">
                                        <img src="{{ $song->thumbnail ?: 'https://img.youtube.com/vi/'.$song->youtube_id.'/mqdefault.jpg' }}"
                                             alt=""
                                             class="w-16 h-12 object-cover rounded-lg flex-shrink-0 shadow-2xs">

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-2">
                                                <h4 class="text-xs sm:text-sm font-bold text-gray-900 line-clamp-1">
                                                    {{ $song->judul }}
                                                </h4>
                                                <span class="text-[10px] sm:text-xs px-2.5 py-0.5 rounded-full font-bold shrink-0
                                                    @if ($song->status === 'menunggu') bg-amber-100 text-amber-800
                                                    @elseif ($song->status === 'disetujui') bg-blue-100 text-blue-800
                                                    @elseif ($song->status === 'diputar') bg-emerald-100 text-emerald-800
                                                    @elseif ($song->status === 'selesai') bg-gray-100 text-gray-700
                                                    @elseif ($song->status === 'ditolak') bg-rose-100 text-rose-800
                                                    @else bg-gray-100 text-gray-500 @endif">
                                                    {{ $song->statusLabel() }}
                                                </span>
                                            </div>

                                            <p class="text-[11px] text-gray-500 mt-0.5">
                                                {{ $song->channel ?: '—' }} · Diajukan pada {{ $song->created_at->isoFormat('D MMM Y, HH:mm') }}
                                            </p>

                                            @if ($song->pesan)
                                                <p class="text-[11px] text-indigo-700 bg-indigo-50/70 rounded px-2 py-0.5 mt-1.5 inline-block">
                                                    💬 "{{ $song->pesan }}"
                                                </p>
                                            @endif

                                            @if ($song->status === 'ditolak' && $song->alasan_ditolak)
                                                <div class="mt-2 p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                                                    <strong>Alasan Ditolak OSIS:</strong> {{ $song->alasan_ditolak }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. TAB: LAGU TERPOPULER --}}
            <div x-show="activeTab === 'populer'" style="display: none;" class="space-y-3">
                <div class="bg-white shadow-xs rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-sm sm:text-base text-gray-800">🔥 Top 5 Lagu Terpopuler di Sekolah</h3>
                            <p class="text-xs text-gray-400">Lagu yang paling sering diminta siswa</p>
                        </div>
                    </div>

                    @if ($topSongs->isEmpty())
                        <div class="p-8 text-center text-gray-400 text-xs">
                            Belum ada statistik lagu terpopuler.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($topSongs as $idx => $ts)
                                <div class="flex items-center gap-3 p-3.5 sm:p-4 hover:bg-gray-50 transition">
                                    <div class="w-7 text-center font-extrabold text-base {{ $idx === 0 ? 'text-amber-500' : ($idx === 1 ? 'text-slate-400' : ($idx === 2 ? 'text-amber-700' : 'text-gray-400')) }}">
                                        #{{ $idx + 1 }}
                                    </div>

                                    <img src="{{ $ts->thumbnail ?: 'https://img.youtube.com/vi/'.$ts->youtube_id.'/mqdefault.jpg' }}"
                                         alt=""
                                         class="w-16 h-11 object-cover rounded-lg flex-shrink-0 shadow-2xs">

                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-900 truncate">
                                            {{ $ts->judul }}
                                        </h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $ts->channel ?: '—' }}</p>
                                    </div>

                                    <div class="flex-shrink-0 text-right">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            {{ $ts->request_count }}x diputar
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. TAB: RIWAYAT UMUM --}}
            <div x-show="activeTab === 'riwayat'" style="display: none;" class="space-y-3">
                <div class="bg-white shadow-xs rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 border-b border-gray-100">
                        <h3 class="font-bold text-sm sm:text-base text-gray-800">Riwayat Siaran Terakhir (20 Lagu)</h3>
                    </div>

                    @if ($riwayat->isEmpty())
                        <div class="p-8 text-center text-gray-400 text-xs">
                            Belum ada riwayat siaran.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($riwayat as $song)
                                <div class="flex items-center gap-3 p-3 sm:px-5 sm:py-3 opacity-80 hover:opacity-100 transition">
                                    <img src="{{ $song->thumbnail }}" alt="" class="w-14 h-10 object-cover rounded flex-shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">{{ $song->judul }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">
                                            {{ $song->channel }} · Oleh {{ $song->user?->name }}
                                            @if ($song->durasi > 0) · {{ $song->durasiFormatted() }} @endif
                                        </p>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-medium shrink-0
                                        @if ($song->status === 'selesai') bg-gray-100 text-gray-600
                                        @elseif ($song->status === 'ditolak') bg-rose-100 text-rose-700
                                        @else bg-gray-100 text-gray-500 @endif">
                                        {{ $song->statusLabel() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Floating Action Button (FAB) Mobile Khusus Siswa --}}
    @if (!auth()->user()->isAdmin())
        <div class="fixed bottom-5 right-5 sm:hidden z-30">
            <a href="{{ route('songs.create') }}"
               class="flex items-center justify-center w-14 h-14 bg-indigo-600 text-white rounded-full shadow-lg active:scale-90 hover:bg-indigo-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
        </div>
    @endif

    {{-- Script AJAX Upvote & Polling --}}
    <script>
    function toggleVote(button, songId) {
        button.disabled = true;

        fetch(`/songs/${songId}/vote`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            button.disabled = false;
            if (data.success) {
                const countElem = button.querySelector('.vote-count');
                if (countElem) {
                    countElem.textContent = data.votes_count;
                }

                // Toggle tampilan visual
                if (data.voted) {
                    button.classList.remove('bg-gray-50', 'text-gray-500', 'border-gray-200');
                    button.classList.add('bg-rose-50', 'text-rose-600', 'border-rose-200');
                    button.querySelector('span:first-child').textContent = '❤️';
                } else {
                    button.classList.remove('bg-rose-50', 'text-rose-600', 'border-rose-200');
                    button.classList.add('bg-gray-50', 'text-gray-500', 'border-gray-200');
                    button.querySelector('span:first-child').textContent = '🤍';
                }
            }
        })
        .catch(err => {
            button.disabled = false;
            console.error('Vote error:', err);
        });
    }

    (function () {
        const container = document.getElementById('antrian-container');
        const lastUpdated = document.getElementById('last-updated');

        function pollAntrian() {
            fetch('{{ route('songs.index') }}?poll=1', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                if (html.trim()) {
                    container.innerHTML = html;
                    if (lastUpdated) {
                        const now = new Date();
                        lastUpdated.textContent = 'Sinkron: ' + now.toLocaleTimeString('id-ID');
                    }
                }
            })
            .catch(() => {});
        }

        // Poll setiap 10 detik
        setInterval(pollAntrian, 10000);
    })();
    </script>
</x-app-layout>
