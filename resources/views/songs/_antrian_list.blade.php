@if ($antrian->isEmpty())
    <div class="p-8 text-center bg-white rounded-[12px] shadow-xs border border-[#131b2e]/5">
        <p class="text-3xl mb-2">📻</p>
        <p class="font-display font-bold text-sm text-[#131b2e]">Antrian sedang kosong</p>
        <p class="font-sans text-xs text-[#727a64] mt-1">Jadilah yang pertama mengajukan lagu untuk siaran hari ini!</p>
        @if (!auth()->user()->isAdmin())
            <a href="{{ route('songs.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-[#a3e635] text-[#416400] font-display font-bold rounded-full text-xs hover:brightness-105 active:scale-95 transition shadow-xs">
                <span>⚡</span> Request Sekarang
            </a>
        @endif
    </div>
@else
    <div class="space-y-3">
        @foreach ($antrian as $index => $song)
            <div class="bg-white rounded-[12px] p-2.5 sm:p-3 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] border border-[#131b2e]/5 flex flex-col gap-2 transition hover:shadow-md
                @if ($song->status === 'diputar') ring-2 ring-[#a3e635] bg-[#faf8ff] @endif">

                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        {{-- Thumbnail dengan badge urutan (#1 Upnext, #2, dll) --}}
                        <div class="size-12 rounded-[8px] bg-[#eaedff] relative overflow-hidden shrink-0 shadow-xs">
                            @if ($song->thumbnail)
                                <img src="{{ $song->thumbnail }}" alt="{{ $song->judul }}" class="size-full object-cover">
                            @else
                                <div class="size-full flex items-center justify-center font-display font-bold text-[10px] text-[#424936] bg-[#eaedff]">
                                    SELADA
                                </div>
                            @endif

                            {{-- Rank Badge --}}
                            <div class="absolute top-0.5 left-0.5 size-4 rounded-full flex items-center justify-center font-display font-bold text-[9px] shadow-2xs {{ $index === 0 ? 'bg-[#a3e635] text-[#416400]' : 'bg-[#dae2fd] text-[#131b2e]' }}">
                                {{ $index + 1 }}
                            </div>
                        </div>

                        {{-- Info Lagu --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h4 class="font-display font-bold text-sm text-[#131b2e] tracking-tight truncate max-w-[190px] sm:max-w-xs" title="{{ $song->judul }}">
                                    {{ $song->judul }}
                                </h4>

                                @if ($index === 0)
                                    <span class="bg-[#a3e635] text-[#416400] font-display font-bold text-[10px] tracking-wider uppercase px-2 py-0.5 rounded-full shrink-0">
                                        #UPNEXT
                                    </span>
                                @endif
                            </div>

                            <p class="font-sans font-medium text-xs text-[#424936] truncate">
                                {{ $song->channel ?: 'Artis Tidak Diketahui' }}
                            </p>

                            <p class="font-sans text-[11px] text-[#727a64] truncate">
                                Oleh: <strong class="font-bold text-[#131b2e]">{{ $song->user?->name ?: 'Siswa' }}</strong>
                                @if ($song->user?->kelas)
                                    <span>({{ $song->user->kelas }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Dedication Pill (Salam Dedikasi) --}}
                @if ($song->pesan)
                    <div class="bg-[#f2f3ff] rounded-[8px] px-2.5 py-1.5 flex items-center gap-1.5 text-xs font-sans font-medium text-[#424936] truncate">
                        <img src="{{ asset('images/figma/icon-msg.svg') }}" class="w-3.5 h-3.5 shrink-0" alt="">
                        <span class="truncate">“{{ $song->pesan }}”</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
