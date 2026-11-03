<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">🎵 Moderasi Lagu</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            {{-- ── MENUNGGU MODERASI ── --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Menunggu Moderasi</h3>
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $menunggu->count() }} lagu</span>
                </div>

                @if ($menunggu->isEmpty())
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">Tidak ada lagu yang menunggu. ✓</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach ($menunggu as $song)
                            <div class="flex items-start gap-3 px-5 py-4">
                                {{-- Thumbnail --}}
                                @if ($song->thumbnail)
                                    <img src="{{ $song->thumbnail }}" class="w-20 h-12 object-cover rounded flex-shrink-0">
                                @else
                                    <div class="w-20 h-12 bg-gray-100 rounded flex-shrink-0"></div>
                                @endif

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-800 text-sm truncate">{{ $song->judul }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $song->channel }} ·
                                        @if ($song->durasi > 0) {{ $song->durasiFormatted() }} · @endif
                                        oleh <strong>{{ $song->user->name }}</strong> ({{ $song->user->kelas }}) ·
                                        {{ $song->created_at->diffForHumans() }}
                                    </p>
                                    <a href="https://www.youtube.com/watch?v={{ $song->youtube_id }}"
                                       target="_blank" rel="noopener"
                                       class="text-xs text-blue-500 hover:underline">
                                        ↗ Buka di YouTube
                                    </a>
                                    @if ($song->pesan)
                                        <p class="text-xs text-indigo-700 bg-indigo-50 border border-indigo-100 rounded px-2.5 py-1 mt-1.5 inline-block">
                                            💬 Pesan/Salam: "{{ $song->pesan }}"
                                        </p>
                                    @endif
                                </div>

                                {{-- Tombol aksi --}}
                                <div class="flex flex-col gap-2 flex-shrink-0">
                                    {{-- Setujui --}}
                                    <form method="POST" action="{{ route('admin.songs.approve', $song) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-xs px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                            ✓ Setujui
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <button type="button"
                                        onclick="document.getElementById('tolak-{{ $song->id }}').classList.toggle('hidden')"
                                        class="text-xs px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                        ✗ Tolak
                                    </button>

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('admin.songs.destroy', $song) }}"
                                          onsubmit="return confirm('Hapus lagu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Form alasan tolak (hidden by default) --}}
                            <div id="tolak-{{ $song->id }}" class="hidden px-5 pb-4 bg-red-50">
                                <form method="POST" action="{{ route('admin.songs.reject', $song) }}" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="alasan"
                                        placeholder="Alasan penolakan (opsional)..."
                                        class="flex-1 text-sm border-gray-300 rounded-lg px-3 py-1.5 focus:ring-red-400 focus:border-red-400">
                                    <button type="submit"
                                        class="text-sm px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── ANTRIAN DISETUJUI ── --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Antrian Disetujui</h3>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $antrian->count() }} lagu</span>
                </div>

                @if ($antrian->isEmpty())
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">Antrian kosong.</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach ($antrian as $i => $song)
                            <div class="flex items-center gap-3 px-5 py-3
                                @if($song->status === 'diputar') bg-green-50 @endif">

                                {{-- Urutan / ikon diputar --}}
                                <div class="w-6 text-center flex-shrink-0">
                                    @if ($song->status === 'diputar')
                                        <span class="text-green-500">▶</span>
                                    @else
                                        <span class="text-gray-400 text-sm">{{ $i + 1 }}</span>
                                    @endif
                                </div>

                                @if ($song->thumbnail)
                                    <img src="{{ $song->thumbnail }}" class="w-16 h-10 object-cover rounded flex-shrink-0">
                                @endif

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $song->judul }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $song->user->name }}
                                        @if ($song->durasi > 0) · {{ $song->durasiFormatted() }} @endif
                                    </p>
                                </div>

                                {{-- Kontrol urutan + skip --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    @if ($song->status === 'disetujui')
                                        <form method="POST" action="{{ route('admin.songs.moveUp', $song) }}">
                                            @csrf
                                            <button type="submit" title="Naikan urutan"
                                                class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded">↑</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.songs.moveDown', $song) }}">
                                            @csrf
                                            <button type="submit" title="Turunkan urutan"
                                                class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded">↓</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.songs.skip', $song) }}"
                                          onsubmit="return confirm('Skip lagu ini?')">
                                        @csrf
                                        <button type="submit" title="Skip"
                                            class="p-1.5 text-xs text-orange-500 hover:text-orange-700 hover:bg-orange-50 rounded">
                                            ⏭ Skip
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.songs.destroy', $song) }}"
                                          onsubmit="return confirm('Hapus lagu ini dari antrian?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="p-1.5 text-xs text-red-400 hover:text-red-600 hover:bg-red-50 rounded">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
