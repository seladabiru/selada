@if ($antrian->isEmpty())
    <div class="px-5 py-12 text-center text-gray-400">
        <p class="text-4xl mb-3">🎵</p>
        <p class="text-sm">Antrian kosong. Jadilah yang pertama request lagu!</p>
        @if (!auth()->user()->isAdmin())
            <a href="{{ route('songs.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition">
                <span>+</span> Request sekarang
            </a>
        @endif
    </div>
@else
    <div class="divide-y divide-gray-100">
        @foreach ($antrian as $index => $song)
            @php
                $hasVoted = $song->isVotedBy(auth()->user());
                $voteCount = $song->votes_count ?? $song->votes->count();
            @endphp
            <div class="flex items-start sm:items-center gap-3 p-3.5 sm:px-5 sm:py-3.5 transition hover:bg-gray-50/80
                @if ($song->status === 'diputar') bg-emerald-50/60 border-l-4 border-emerald-500 @endif">

                {{-- Nomor urut / ikon sedang diputar --}}
                <div class="w-6 sm:w-7 flex-shrink-0 text-center pt-1 sm:pt-0">
                    @if ($song->status === 'diputar')
                        <span class="inline-block text-emerald-600 font-bold text-base animate-pulse" title="Sedang mengudara">▶</span>
                    @else
                        <span class="text-gray-400 text-xs sm:text-sm font-mono font-medium">{{ $index + 1 }}</span>
                    @endif
                </div>

                {{-- Thumbnail --}}
                <div class="relative flex-shrink-0">
                    @if ($song->thumbnail)
                        <img src="{{ $song->thumbnail }}"
                             alt="thumbnail"
                             class="w-16 h-12 sm:w-20 sm:h-12 object-cover rounded-lg shadow-xs
                                    @if ($song->status !== 'diputar') opacity-90 @endif">
                    @else
                        <div class="w-16 h-12 sm:w-20 sm:h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-[10px] font-bold">
                            YOUTUBE
                        </div>
                    @endif
                    @if ($song->durasi > 0)
                        <span class="absolute bottom-1 right-1 bg-black/75 text-white text-[9px] font-semibold px-1 py-0.2 rounded">
                            {{ $song->durasiFormatted() }}
                        </span>
                    @endif
                </div>

                {{-- Info lagu --}}
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 line-clamp-1
                                   @if ($song->status === 'diputar') text-emerald-900 @endif">
                            {{ $song->judul }}
                        </p>
                        @if ($voteCount >= 3)
                            <span class="inline-flex items-center text-[10px] font-bold px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">
                                🔥 Populer
                            </span>
                        @endif
                    </div>

                    <p class="text-[11px] sm:text-xs text-gray-500 truncate mt-0.5">
                        {{ $song->channel ?: '—' }} · Oleh <strong class="text-gray-700">{{ $song->user?->name }}</strong>
                        @if ($song->user?->kelas)
                            <span class="text-gray-400">({{ $song->user->kelas }})</span>
                        @endif
                    </p>

                    @if ($song->pesan)
                        <p class="text-[11px] text-indigo-700 bg-indigo-50/80 rounded-md px-2 py-0.5 mt-1 inline-block line-clamp-1 max-w-full">
                            💬 "{{ $song->pesan }}"
                        </p>
                    @endif
                </div>

                {{-- Vote & Status Actions --}}
                <div class="flex-shrink-0 flex flex-col items-end gap-1.5 pl-1">
                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Upvote AJAX --}}
                        <button type="button"
                                onclick="toggleVote(this, {{ $song->id }})"
                                data-song-id="{{ $song->id }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold transition border
                                    {{ $hasVoted
                                        ? 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100 shadow-xs'
                                        : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100' }}"
                                title="{{ $hasVoted ? 'Batalkan vote' : 'Sukai & dukung lagu ini' }}">
                            <span class="text-xs">{{ $hasVoted ? '❤️' : '🤍' }}</span>
                            <span class="vote-count text-[11px] font-bold">{{ $voteCount }}</span>
                        </button>

                        {{-- Link YouTube --}}
                        <a href="https://www.youtube.com/watch?v={{ $song->youtube_id }}"
                           target="_blank"
                           rel="noopener"
                           class="hidden sm:inline-flex items-center text-[11px] text-gray-400 hover:text-rose-600 p-1 rounded transition"
                           title="Buka di YouTube">
                            ↗ YT
                        </a>
                    </div>

                    {{-- Status badge --}}
                    <span class="text-[10px] sm:text-[11px] px-2 py-0.5 rounded-full font-bold
                        @if ($song->status === 'menunggu')  bg-amber-100 text-amber-800
                        @elseif ($song->status === 'disetujui') bg-blue-100 text-blue-800
                        @elseif ($song->status === 'diputar')   bg-emerald-100 text-emerald-800
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ $song->statusLabel() }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endif
