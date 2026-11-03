<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                    📻 Pemutar Siaran Radio Sekolah
                </h2>
                <p class="text-xs text-gray-500 mt-1">Operator OSIS / Bel Pemutar Musik</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.songs.index') }}" class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    🎵 Moderasi Lagu
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Status Bar Jadwal Siaran --}}
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                        @if ($jadwalAktifSekarang)
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        @else
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-400"></span>
                        @endif
                    </span>
                    <div>
                        @if ($jadwalAktifSekarang)
                            <p class="text-sm font-semibold text-green-700">
                                Sedang Dalam Jam Siaran: {{ $jadwalAktifSekarang->nama_sesi }}
                                ({{ substr($jadwalAktifSekarang->jam_mulai, 0, 5) }} - {{ substr($jadwalAktifSekarang->jam_selesai, 0, 5) }})
                            </p>
                            <p class="text-xs text-green-600">Lagu otomatis diputar untuk mengisi jam istirahat sekolah.</p>
                        @else
                            <p class="text-sm font-semibold text-gray-700">
                                Di Luar Jam Siaran Resmi
                            </p>
                            <p class="text-xs text-gray-500">
                                Anda tetap dapat memutar lagu secara manual menggunakan tombol putar di bawah.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="text-right">
                    <span id="current-clock" class="text-lg font-mono font-bold text-gray-700">--:--:--</span>
                    <p class="text-xs text-gray-400">Waktu Server</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Player & Now Playing (2 Kolom) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Container YouTube Video Player --}}
                    <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-xl aspect-video relative flex items-center justify-center">
                        <div id="youtube-player" class="w-full h-full"></div>

                        {{-- Overlay saat tidak ada lagu --}}
                        <div id="player-idle-overlay" class="absolute inset-0 bg-gray-900 flex flex-col items-center justify-center text-center p-6 {{ $sedangDiputar ? 'hidden' : '' }}">
                            <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center text-2xl text-gray-400 mb-3">
                                🎵
                            </div>
                            <h3 class="text-white font-semibold text-lg">Belum Ada Lagu yang Diputar</h3>
                            <p class="text-gray-400 text-sm max-w-sm mt-1">
                                Klik tombol <strong>"Putar Lagu Pertama"</strong> atau pilih salah satu lagu dari daftar antrian di samping.
                            </p>
                            @if ($antrian->isNotEmpty())
                                <button type="button" onclick="playSong({{ $antrian->first()->id }})"
                                    class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg transition">
                                    ▶ Putar Lagu Pertama di Antrian
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Kontrol & Status Pemutaran --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <img id="now-thumbnail"
                                     src="{{ $sedangDiputar?->thumbnail ?: 'https://via.placeholder.com/120x90?text=No+Song' }}"
                                     alt="thumbnail"
                                     class="w-16 h-12 object-cover rounded-lg flex-shrink-0 border border-gray-100">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded bg-green-100 text-green-700">
                                            Now Playing
                                        </span>
                                        <span id="player-status-badge" class="text-xs text-gray-500">Standby</span>
                                    </div>
                                    <h3 id="now-title" class="font-bold text-gray-800 text-base truncate mt-1">
                                        {{ $sedangDiputar?->judul ?: 'Tidak ada lagu aktif' }}
                                    </h3>
                                    <p id="now-meta" class="text-xs text-gray-400 truncate">
                                        @if ($sedangDiputar)
                                            {{ $sedangDiputar->channel }} · Request oleh: <strong>{{ $sedangDiputar->user?->name }}</strong> ({{ $sedangDiputar->user?->kelas }})
                                        @else
                                            Pilih lagu dari antrian untuk mulai siaran
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Tombol Kontrol --}}
                            <div class="flex items-center gap-2">
                                <button type="button" id="btn-toggle-play" onclick="togglePlayPause()"
                                    class="p-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-semibold text-sm flex items-center gap-1.5"
                                    title="Play / Pause">
                                    <span id="play-icon">⏸</span>
                                    <span id="play-text">Pause</span>
                                </button>

                                <button type="button" onclick="skipCurrentSong(false)"
                                    class="px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow transition font-semibold text-sm flex items-center gap-1.5"
                                    title="Lewati ke lagu berikutnya">
                                    <span>Lanjut</span>
                                    <span>⏭</span>
                                </button>
                            </div>
                        </div>

                        {{-- Kotak Pesan & Salam Siswa untuk Penyiar/DJ --}}
                        <div id="now-pesan-box" class="mt-4 p-3.5 bg-indigo-50/80 border border-indigo-100 rounded-xl {{ $sedangDiputar?->pesan ? '' : 'hidden' }}">
                            <div class="flex items-start gap-2">
                                <span class="text-base">📢</span>
                                <div>
                                    <p class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Salam / Dedikasi Lagu dari Siswa:</p>
                                    <p id="now-pesan-text" class="text-xs text-indigo-800 italic mt-0.5">
                                        "{{ $sedangDiputar?->pesan }}"
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Info Error Alert --}}
                        <div id="player-error-alert" class="hidden mt-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center justify-between">
                            <span id="player-error-text">Video gagal diputar.</span>
                            <button type="button" onclick="skipCurrentSong(true)" class="underline font-bold">Skip Lagu Ini</button>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Antrian Siaran yang Disetujui (1 Kolom) --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full max-h-[600px]">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Antrian Siaran</h3>
                                <p class="text-[11px] text-gray-400">Lagu yang telah disetujui</p>
                            </div>
                            <span id="queue-counter" class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                {{ $antrian->count() }} lagu
                            </span>
                        </div>

                        <div id="queue-container" class="divide-y divide-gray-50 overflow-y-auto flex-1">
                            @forelse ($antrian as $index => $song)
                                <div id="queue-item-{{ $song->id }}" class="p-3 hover:bg-gray-50 transition flex items-center gap-3">
                                    <span class="text-xs font-mono font-bold text-gray-400 w-4 text-center">
                                        {{ $index + 1 }}
                                    </span>
                                    <img src="{{ $song->thumbnail }}" alt="" class="w-14 h-9 object-cover rounded flex-shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $song->judul }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">
                                            {{ $song->user?->name }} · {{ $song->durasiFormatted() }}
                                        </p>
                                    </div>
                                    <button type="button" onclick="playSong({{ $song->id }})"
                                        class="px-2.5 py-1 text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded-lg transition flex-shrink-0"
                                        title="Putar sekarang">
                                        ▶
                                    </button>
                                </div>
                            @empty
                                <div id="empty-queue-message" class="p-8 text-center text-gray-400 text-xs">
                                    Tidak ada lagu disetujui di antrian.<br>
                                    Setujui lagu di menu <strong>Moderasi</strong> untuk menambahkan.
                                </div>
                            @endforelse
                        </div>

                        <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                            <a href="{{ route('admin.songs.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                                + Kelola & Moderasi Lagu Masuk →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Script YouTube IFrame API & Logika Player Siaran --}}
    <script>
        let player = null;
        let isPlayerReady = false;
        let currentSongId = {{ $sedangDiputar?->id ?: 'null' }};
        let currentYoutubeId = '{{ $sedangDiputar?->youtube_id ?: '' }}';
        const csrfToken = '{{ csrf_token() }}';

        // Load YouTube IFrame Player API async
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '100%',
                width: '100%',
                videoId: currentYoutubeId,
                playerVars: {
                    autoplay: currentYoutubeId ? 1 : 0,
                    controls: 1,
                    rel: 0,
                    modestbranding: 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
        }

        function onPlayerReady(event) {
            isPlayerReady = true;
            if (currentYoutubeId) {
                event.target.playVideo();
                updatePlayButton(true);
            }
        }

        function onPlayerStateChange(event) {
            const badge = document.getElementById('player-status-badge');
            if (event.data === YT.PlayerState.PLAYING) {
                badge.textContent = 'Memutar';
                badge.className = 'text-xs text-green-600 font-semibold';
                updatePlayButton(true);
            } else if (event.data === YT.PlayerState.PAUSED) {
                badge.textContent = 'Dijeda (Paused)';
                badge.className = 'text-xs text-yellow-600';
                updatePlayButton(false);
            } else if (event.data === YT.PlayerState.ENDED) {
                badge.textContent = 'Lagu Selesai';
                // Otomatis tandai lagu selesai dan lanjut ke berikutnya
                finishCurrentSong();
            }
        }

        function onPlayerError(event) {
            const alertBox = document.getElementById('player-error-alert');
            const alertText = document.getElementById('player-error-text');
            alertBox.classList.remove('hidden');

            let msg = 'Terjadi kesalahan saat memutar video YouTube (Kode: ' + event.data + ').';
            if (event.data === 101 || event.data === 150) {
                msg = 'Pemilik video melarang pemutaran di aplikasi luar / embed.';
            }
            alertText.textContent = msg;

            // Otomatis skip setelah 3 detik jika terjadi error embed
            setTimeout(() => {
                skipCurrentSong(true, msg);
            }, 3000);
        }

        function togglePlayPause() {
            if (!player || !isPlayerReady) return;
            const state = player.getPlayerState();
            if (state === YT.PlayerState.PLAYING) {
                player.pauseVideo();
                updatePlayButton(false);
            } else {
                player.playVideo();
                updatePlayButton(true);
            }
        }

        function updatePlayButton(isPlaying) {
            const icon = document.getElementById('play-icon');
            const text = document.getElementById('play-text');
            if (isPlaying) {
                icon.textContent = '⏸';
                text.textContent = 'Pause';
            } else {
                icon.textContent = '▶';
                text.textContent = 'Play';
            }
        }

        // Putar lagu pilihan
        function playSong(songId) {
            fetch(`/admin/player/${songId}/play`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.song) {
                    currentSongId = data.song.id;
                    currentYoutubeId = data.song.youtube_id;
                    updateNowPlayingUI(data.song);

                    if (player && isPlayerReady) {
                        player.loadVideoById(currentYoutubeId);
                    }

                    // Hapus item dari antrian samping
                    const item = document.getElementById(`queue-item-${songId}`);
                    if (item) item.remove();
                }
            })
            .catch(err => console.error('Gagal memutar lagu:', err));
        }

        // Tandai lagu selesai & otomatis putar berikutnya
        function finishCurrentSong() {
            if (!currentSongId) return;

            fetch(`/admin/player/${currentSongId}/finish`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.has_next && data.song) {
                    currentSongId = data.song.id;
                    currentYoutubeId = data.song.youtube_id;
                    updateNowPlayingUI(data.song);

                    if (player && isPlayerReady) {
                        player.loadVideoById(currentYoutubeId);
                    }

                    const item = document.getElementById(`queue-item-${data.song.id}`);
                    if (item) item.remove();
                } else {
                    currentSongId = null;
                    currentYoutubeId = '';
                    showIdleUI();
                }
            })
            .catch(err => console.error('Gagal menyelesaikan lagu:', err));
        }

        // Skip lagu saat ini
        function skipCurrentSong(isError = false, alasan = '') {
            if (!currentSongId) {
                // Jika tidak ada lagu aktif tapi antrian ada, putar pertama
                const firstQueueBtn = document.querySelector('#queue-container button');
                if (firstQueueBtn) firstQueueBtn.click();
                return;
            }

            document.getElementById('player-error-alert').classList.add('hidden');

            fetch(`/admin/player/${currentSongId}/skip`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    error: isError,
                    alasan: alasan || (isError ? 'Video tidak dapat di-embed' : 'Di-skip oleh admin')
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.has_next && data.song) {
                    currentSongId = data.song.id;
                    currentYoutubeId = data.song.youtube_id;
                    updateNowPlayingUI(data.song);

                    if (player && isPlayerReady) {
                        player.loadVideoById(currentYoutubeId);
                    }

                    const item = document.getElementById(`queue-item-${data.song.id}`);
                    if (item) item.remove();
                } else {
                    currentSongId = null;
                    currentYoutubeId = '';
                    showIdleUI();
                }
            })
            .catch(err => console.error('Gagal skip lagu:', err));
        }

        function updateNowPlayingUI(song) {
            document.getElementById('player-idle-overlay').classList.add('hidden');
            document.getElementById('player-error-alert').classList.add('hidden');
            document.getElementById('now-title').textContent = song.judul;
            document.getElementById('now-thumbnail').src = song.thumbnail || 'https://via.placeholder.com/120x90';
            document.getElementById('now-meta').innerHTML = `${song.channel || '—'} · Request oleh: <strong>${song.user || 'Siswa'}</strong> (${song.kelas || ''})`;

            const pesanBox = document.getElementById('now-pesan-box');
            const pesanText = document.getElementById('now-pesan-text');
            if (song.pesan) {
                pesanText.textContent = `"${song.pesan}"`;
                pesanBox.classList.remove('hidden');
            } else {
                pesanBox.classList.add('hidden');
            }
        }

        function showIdleUI() {
            document.getElementById('player-idle-overlay').classList.remove('hidden');
            document.getElementById('now-title').textContent = 'Tidak ada lagu aktif';
            document.getElementById('now-meta').textContent = 'Pilih lagu dari antrian untuk mulai siaran';
            document.getElementById('player-status-badge').textContent = 'Selesai';
            document.getElementById('now-pesan-box').classList.add('hidden');
            if (player && isPlayerReady) {
                player.stopVideo();
            }
        }

        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clockEl = document.getElementById('current-clock');
            if (clockEl) clockEl.textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-app-layout>

