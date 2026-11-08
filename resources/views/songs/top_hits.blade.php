<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/selada-radio-logo.png') }}">
    <title>Top Hits & Tangga Lagu — SELADA Radio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @layer base {
            html, body { width: 100vw; margin: 0; padding: 0; min-height: 100vh; }
            body { overscroll-behavior: none; min-height: max(884px, 100dvh); }
            .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
            .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
        }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="font-body-md text-on-surface flex flex-col min-h-screen" style="background-color:#faf8ff;color:#131b2e;">

@include('layouts.navigation')

{{-- ===================== MAIN CONTENT ===================== --}}
<main class="flex flex-col relative w-full px-4 sm:px-6 md:max-w-4xl md:mx-auto pt-4 sm:pt-6 pb-28 flex-grow" style="background:#faf8ff;">
    <div class="flex flex-col w-full gap-5 select-none">

        {{-- ══════════════════════════════════════════════
             1. TOP BANNER & TITLE AREA
        ══════════════════════════════════════════════ --}}
        <section class="flex flex-col gap-2 pt-1">
            <div class="flex items-center justify-between gap-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full shadow-sm"
                     style="background:#8127cf;color:#ffffff;">
                    <span class="material-symbols-outlined text-[16px]">local_fire_department</span>
                    <span class="font-label-sm text-label-sm tracking-wider uppercase font-bold">Chart Resmi SMKN 20</span>
                </div>
                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full"
                     style="background:#a3e635;color:#416400;">
                    <span class="w-2 h-2 rounded-full animate-ping" style="background:#446900;"></span>
                    <span class="font-label-sm text-label-sm font-bold uppercase">Updated Real-Time</span>
                </div>
            </div>

            <div class="flex flex-col mt-1">
                <h1 class="font-headline-xl-mobile text-headline-xl-mobile text-on-surface tracking-tight flex items-center gap-1.5">
                    TOP HITS SMKN 20 📻
                </h1>
                <p class="font-body-sm text-body-sm" style="color:#424936;">
                    Tangga Lagu & Hall of Fame Radio OSIS Semester Ini
                </p>
            </div>

            {{-- Period Switcher Pills --}}
            <div class="grid grid-cols-3 gap-1.5 p-1 rounded-xl shadow-sm mt-1" style="background:#eaedff;" id="chart-tabs">
                <a href="{{ route('songs.top-hits', ['period' => 'week']) }}"
                   class="tab-btn py-2 px-1 rounded-lg font-label-md text-label-md font-bold text-center transition-all {{ ($period ?? 'week') === 'week' ? 'bg-surface font-bold text-on-surface shadow-sm' : 'text-on-surface-variant' }}"
                   style="{{ ($period ?? 'week') === 'week' ? 'background:#ffffff;color:#131b2e;' : 'color:#424936;' }}">
                    Minggu Ini 🔥
                </a>
                <a href="{{ route('songs.top-hits', ['period' => 'month']) }}"
                   class="tab-btn py-2 px-1 rounded-lg font-label-md text-label-md text-center transition-all {{ ($period ?? 'week') === 'month' ? 'bg-surface font-bold text-on-surface shadow-sm' : 'text-on-surface-variant' }}"
                   style="{{ ($period ?? 'week') === 'month' ? 'background:#ffffff;color:#131b2e;' : 'color:#424936;' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('songs.top-hits', ['period' => 'alltime']) }}"
                   class="tab-btn py-2 px-1 rounded-lg font-label-md text-label-md text-center transition-all {{ ($period ?? 'week') === 'alltime' ? 'bg-surface font-bold text-on-surface shadow-sm' : 'text-on-surface-variant' }}"
                   style="{{ ($period ?? 'week') === 'alltime' ? 'background:#ffffff;color:#131b2e;' : 'color:#424936;' }}">
                    All-Time 🏆
                </a>
            </div>

        </section>

        {{-- ══════════════════════════════════════════════
             2. VISUAL TOP 3 PODIUM (CHAMPIONS PODIUM)
        ══════════════════════════════════════════════ --}}
        <section class="flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[22px]" style="color:#8127cf;">workspace_premium</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">The Champions Podium</h2>
                </div>
                <span class="font-label-sm text-label-sm" style="color:#424936;">Peringkat Pemutaran</span>
            </div>

            {{-- #1 Champion Card (Full Width Standout Hero) --}}
            @php
                $c1Judul     = $top1 ? $top1->judul : 'Evaluasi';
                $c1Channel   = $top1 ? ($top1->channel ?? 'Hindia') : 'Hindia';
                $c1Requester = $top1 && $top1->user ? ($top1->user->name . ' (' . ($top1->user->kelas ?? 'XI RPL 1') . ')') : 'Dimas (XI RPL 1) + 8 siswa lain';
                $c1Thumb     = $top1 && $top1->thumbnail ? $top1->thumbnail : asset('images/stitch/tophits_evaluasi.jpg');
            @endphp
            <div class="relative overflow-hidden rounded-2xl shadow-md p-4 flex flex-col gap-3"
                 style="background:#ffffff;">
                {{-- Glow Decorator --}}
                <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full blur-2xl pointer-events-none"
                     style="background:rgba(163,230,53,0.4);"></div>

                {{-- Top Badge Row --}}
                <div class="flex items-center justify-between relative z-10">
                    <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full shadow-sm font-label-sm text-label-sm font-bold"
                         style="background:#a3e635;color:#416400;">
                        <span class="material-symbols-outlined text-[16px]" style="color:#446900;">military_tech</span>
                        #1 JUARA UTAMA
                    </div>
                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-[11px] font-bold"
                         style="background:#ffc5b8;color:#a82d11;">
                         <span>👑</span> {{ $top1?->on_air_count ?? 0 }}x On Air
                    </div>
                </div>

                {{-- Main Track Info with Vinyl Visual --}}
                <div class="flex items-center gap-3 relative z-10">
                    <div class="relative w-20 h-20 flex-shrink-0">
                        {{-- Album Thumbnail --}}
                        <img class="w-16 h-16 rounded-xl object-cover shadow-sm relative z-10"
                             src="{{ $c1Thumb }}"
                             alt="{{ $c1Judul }}"
                             onerror="this.src='{{ asset('images/stitch/tophits_evaluasi.jpg') }}'"/>
                        {{-- Animated Vinyl Record Peek --}}
                        <div class="absolute right-0 top-1 w-14 h-14 rounded-full flex items-center justify-center shadow-sm animate-spin-slow"
                             style="background:#283044;">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background:#f0dbff;">
                                <div class="w-1.5 h-1.5 rounded-full" style="background:#2c0051;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface truncate tracking-tight">{{ $c1Judul }}</h3>
                        <p class="font-body-md text-body-md font-bold truncate" style="color:#8127cf;">{{ $c1Channel }}</p>
                        <div class="flex items-center gap-1.5 mt-1" style="color:#424936;">
                            <span class="material-symbols-outlined text-[15px]" style="color:#ae3115;">record_voice_over</span>
                            <span class="font-body-sm text-body-sm truncate">{{ $c1Requester }}</span>
                        </div>
                    </div>
                </div>

                {{-- Meta Bar --}}
                <div class="flex items-center justify-between pt-2 rounded-xl px-3 py-2 relative z-10"
                     style="background:#f2f3ff;">
                    <div class="flex items-center gap-1 font-label-sm text-[12px]" style="color:#424936;">
                        <span class="material-symbols-outlined text-[15px]" style="color:#446900;">broadcast_on_home</span>
                        {{ $top1?->on_air_count ?? 0 }}x On Air
                    </div>
                </div>
            </div>

            {{-- #2 & #3 Split Podium Cards --}}
            <div class="grid grid-cols-2 gap-2.5">
                {{-- #2 Silver Card --}}
                @php
                    $c2Judul     = $top2 ? $top2->judul : 'Apa Mungkin';
                    $c2Channel   = $top2 ? ($top2->channel ?? 'Bernadya') : 'Bernadya';
                    $c2Requester = $top2 && $top2->user ? ($top2->user->name . ' (' . ($top2->user->kelas ?? 'X DKV 2') . ')') : 'Maya (X DKV 2)';
                    $c2Thumb     = $top2 && $top2->thumbnail ? $top2->thumbnail : asset('images/stitch/tophits_apa_mungkin.jpg');
                @endphp
                <div class="relative overflow-hidden rounded-2xl p-3 flex flex-col justify-between gap-2 shadow-sm"
                     style="background:#f2f3ff;">
                    <div class="flex items-center justify-between">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-label-md text-label-md font-extrabold shadow-sm"
                              style="background:#dae2fd;color:#131b2e;">2</span>
                        <span class="px-1.5 py-0.5 rounded font-label-sm text-[10px] font-bold"
                              style="background:#ffffff;color:#424936;">🥈 Silver</span>
                    </div>
                    <div class="flex flex-col items-center text-center mt-1">
                        <img class="w-14 h-14 rounded-lg object-cover shadow-sm mb-2"
                             src="{{ $c2Thumb }}"
                             alt="{{ $c2Judul }}"
                             onerror="this.src='{{ asset('images/stitch/tophits_apa_mungkin.jpg') }}'"/>
                        <h4 class="font-headline-sm text-[15px] leading-tight text-on-surface font-bold truncate w-full">{{ $c2Judul }}</h4>
                        <p class="font-body-sm text-body-sm truncate w-full font-bold" style="color:#8127cf;">{{ $c2Channel }}</p>
                        <p class="font-label-sm text-[11px] truncate w-full mt-1" style="color:#424936;">{{ $c2Requester }}</p>
                    </div>
                </div>

                {{-- #3 Bronze Card --}}
                @php
                    $c3Judul     = $top3 ? $top3->judul : 'Dan';
                    $c3Channel   = $top3 ? ($top3->channel ?? 'Sheila on 7') : 'Sheila on 7';
                    $c3Requester = $top3 && $top3->user ? ($top3->user->name . ' (' . ($top3->user->kelas ?? 'XII IPS 2') . ')') : 'Kevin (XII IPS 2)';
                    $c3Thumb     = $top3 && $top3->thumbnail ? $top3->thumbnail : asset('images/stitch/tophits_dan.jpg');
                @endphp
                <div class="relative overflow-hidden rounded-2xl p-3 flex flex-col justify-between gap-2 shadow-sm"
                     style="background:#f2f3ff;">
                    <div class="flex items-center justify-between">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-label-md text-label-md font-extrabold shadow-sm"
                              style="background:#ffdad2;color:#3d0600;">3</span>
                        <span class="px-1.5 py-0.5 rounded font-label-sm text-[10px] font-bold"
                              style="background:#ffffff;color:#424936;">🥉 Bronze</span>
                    </div>
                    <div class="flex flex-col items-center text-center mt-1">
                        <img class="w-14 h-14 rounded-lg object-cover shadow-sm mb-2"
                             src="{{ $c3Thumb }}"
                             alt="{{ $c3Judul }}"
                             onerror="this.src='{{ asset('images/stitch/tophits_dan.jpg') }}'"/>
                        <h4 class="font-headline-sm text-[15px] leading-tight text-on-surface font-bold truncate w-full">{{ $c3Judul }}</h4>
                        <p class="font-body-sm text-body-sm truncate w-full font-bold" style="color:#8127cf;">{{ $c3Channel }}</p>
                        <p class="font-label-sm text-[11px] truncate w-full mt-1" style="color:#424936;">{{ $c3Requester }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════
             3. INTERACTIVE LEADERBOARD RANK #4 TO #8
        ══════════════════════════════════════════════ --}}
        <section class="flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[22px]" style="color:#446900;">format_list_numbered</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Peringkat #4 - #8</h2>
                </div>
                <div class="font-label-sm text-label-sm flex items-center gap-1" style="color:#424936;">
                    <span>Geser / Tap +1</span>
                </div>
            </div>

            @php
                // Default placeholders if database has fewer than 8 songs
                $defaultLeaderboard = [
                    ['rank' => 4, 'trend' => 'up', 'title' => 'Gala Bunga Matahari', 'artist' => 'Sal Priadi', 'req' => 'Anisa (XI IPA 2)', 'thumb' => asset('images/stitch/tophits_gala_bunga.jpg')],
                    ['rank' => 5, 'trend' => 'same', 'title' => 'Sialan', 'artist' => 'Juicy Luicy', 'req' => 'Bagas (XI TKJ 1)', 'thumb' => asset('images/stitch/tophits_komang.jpg')],
                    ['rank' => 6, 'trend' => 'up', 'title' => 'Rayuan Perempuan Gila', 'artist' => 'Nadin Amizah', 'req' => 'Salsa (XI MIPA 1)', 'thumb' => asset('images/stitch/tophits_sial.jpg')],
                    ['rank' => 7, 'trend' => 'down', 'title' => 'Anything You Want', 'artist' => 'Reality Club', 'req' => 'Farhan (XII MIPA 3)', 'thumb' => asset('images/stitch/tophits_hujan.jpg')],
                    ['rank' => 8, 'trend' => 'new', 'title' => 'Tarian Penghancur Raya', 'artist' => '.Feast', 'req' => 'Danu (X TO 1)', 'thumb' => asset('images/stitch/tophits_sorai.jpg')],
                ];
            @endphp

            <div class="flex flex-col gap-2.5">
                @if ($leaderboard->isNotEmpty())
                    @foreach ($leaderboard as $index => $song)
                        @php
                            $rankNum = $index + 4;
                            $trendIcon = match($rankNum) {
                                4, 6 => 'arrow_drop_up',
                                5 => 'remove',
                                7 => 'arrow_drop_down',
                                default => 'star'
                            };
                            $trendColor = match($rankNum) {
                                4, 6 => '#446900',
                                7 => '#ae3115',
                                default => '#727a64'
                            };
                            $thumb = $song->thumbnail ?: ($defaultLeaderboard[$index]['thumb'] ?? asset('images/stitch/tophits_gala_bunga.jpg'));
                        @endphp
                        <div class="song-card rounded-2xl p-3 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow"
                             style="background:#ffffff;">
                            <div class="flex flex-col items-center justify-center w-7 flex-shrink-0">
                                <span class="font-headline-sm text-headline-sm text-on-surface font-extrabold">#{{ $rankNum }}</span>
                                <span class="material-symbols-outlined text-[16px]" style="color:{{ $trendColor }};">{{ $trendIcon }}</span>
                            </div>
                            <img class="w-12 h-12 rounded-xl object-cover shadow-sm flex-shrink-0"
                                 src="{{ $thumb }}"
                                 alt="{{ $song->judul }}"
                                 onerror="this.src='{{ $defaultLeaderboard[$index]['thumb'] ?? asset('images/stitch/tophits_gala_bunga.jpg') }}'"/>
                            <div class="flex flex-col min-w-0 flex-1">
                                <span class="font-headline-sm text-[15px] leading-tight text-on-surface truncate">{{ $song->judul }}</span>
                                <span class="font-body-sm text-body-sm truncate" style="color:#8127cf;">{{ $song->channel ?? 'Unknown Artist' }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5 font-label-sm text-[11px] truncate" style="color:#424936;">
                                    <span>Req: {{ $song->user ? $song->user->name : 'Siswa' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach ($defaultLeaderboard as $item)
                        <div class="song-card rounded-2xl p-3 flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow"
                             style="background:#ffffff;">
                            <div class="flex flex-col items-center justify-center w-7 flex-shrink-0">
                                <span class="font-headline-sm text-headline-sm text-on-surface font-extrabold">#{{ $item['rank'] }}</span>
                                @if ($item['trend'] === 'up')
                                    <span class="material-symbols-outlined text-[16px]" style="color:#446900;">arrow_drop_up</span>
                                @elseif ($item['trend'] === 'down')
                                    <span class="material-symbols-outlined text-[16px]" style="color:#ae3115;">arrow_drop_down</span>
                                @elseif ($item['trend'] === 'new')
                                    <span class="material-symbols-outlined text-[14px]" style="color:#ae3115;">star</span>
                                @else
                                    <span class="material-symbols-outlined text-[16px]" style="color:#727a64;">remove</span>
                                @endif
                            </div>
                            <img class="w-12 h-12 rounded-xl object-cover shadow-sm flex-shrink-0"
                                 src="{{ $item['thumb'] }}"
                                 alt="{{ $item['title'] }}"/>
                            <div class="flex flex-col min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-headline-sm text-[15px] leading-tight text-on-surface truncate">{{ $item['title'] }}</span>
                                    @if ($item['trend'] === 'new')
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase font-label-sm"
                                              style="background:#ffc5b8;color:#a82d11;">New</span>
                                    @endif
                                </div>
                                <span class="font-body-sm text-body-sm truncate" style="color:#8127cf;">{{ $item['artist'] }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5 font-label-sm text-[11px] truncate" style="color:#424936;">
                                    <span>Req: {{ $item['req'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        {{-- ══════════════════════════════════════════════
             4. TOP REQUESTERS BULAN INI (FR-REP-02)
        ══════════════════════════════════════════════ --}}
        <section class="flex flex-col gap-2.5 mt-1">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[22px]" style="color:#8127cf;">stars</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Top Requesters Bulan Ini</h2>
                </div>
                <span class="font-label-sm text-label-sm" style="color:#424936;">Siswa Paling Hits</span>
            </div>

            <div class="grid grid-cols-3 gap-2">
                {{-- Top Requester 1 --}}
                @php
                    $r1 = $topRequesters->get(0);
                    $r1Name  = $r1 ? $r1->name : 'Dimas';
                    $r1Kelas = $r1 ? ($r1->kelas ?? 'XI RPL 1') : 'XI RPL 1';
                    $r1Count = $r1 ? ($r1->songs_count ?? 6) : 6;
                @endphp
                <div class="rounded-2xl p-3 flex flex-col items-center text-center shadow-sm relative"
                     style="background:#f0dbff;color:#2c0051;">
                    <div class="absolute -top-2 px-2 py-0.5 rounded-full font-label-sm text-[10px] font-bold"
                         style="background:#8127cf;color:#ffffff;">
                        🥇 Rank 1
                    </div>
                    <div class="w-12 h-12 rounded-full p-0.5 mt-2 shadow-sm" style="background:#ffffff;">
                        <div class="w-full h-full rounded-full flex items-center justify-center font-black text-base select-none" style="background:#8127cf;color:#ffffff;">
                            {{ strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', $r1Name))), 0, 2)) }}
                        </div>
                    </div>
                    <p class="font-headline-sm text-[13px] leading-tight font-bold truncate w-full mt-2" style="color:#2c0051;">{{ $r1Name }}</p>
                    <span class="font-label-sm text-[11px] truncate w-full" style="opacity:0.8;">{{ $r1Kelas }}</span>
                    <div class="mt-2 px-2 py-1 rounded-lg font-label-sm text-[11px] font-extrabold shadow-sm"
                         style="background:#ffffff;color:#8127cf;">
                        {{ $r1Count }}x On-Air
                    </div>
                </div>

                {{-- Top Requester 2 --}}
                @php
                    $r2 = $topRequesters->get(1);
                    $r2Name  = $r2 ? $r2->name : 'Salsaabila P.';
                    $r2Kelas = $r2 ? ($r2->kelas ?? 'XII MIPA 1') : 'XII MIPA 1';
                    $r2Count = $r2 ? ($r2->songs_count ?? 5) : 5;
                @endphp
                <div class="rounded-2xl p-3 flex flex-col items-center text-center shadow-sm relative"
                     style="background:#e2e7ff;color:#131b2e;">
                    <div class="absolute -top-2 px-2 py-0.5 rounded-full font-label-sm text-[10px] font-bold"
                         style="background:#dae2fd;color:#131b2e;">
                        🥈 Rank 2
                    </div>
                    <div class="w-12 h-12 rounded-full p-0.5 mt-2 shadow-sm" style="background:#ffffff;">
                        <div class="w-full h-full rounded-full flex items-center justify-center font-black text-base select-none" style="background:#a3e635;color:#416400;">
                            {{ strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', $r2Name))), 0, 2)) }}
                        </div>
                    </div>
                    <p class="font-headline-sm text-[13px] leading-tight font-bold truncate w-full mt-2">{{ $r2Name }}</p>
                    <span class="font-label-sm text-[11px] truncate w-full" style="color:#424936;">{{ $r2Kelas }}</span>
                    <div class="mt-2 px-2 py-1 rounded-lg font-label-sm text-[11px] font-extrabold shadow-sm"
                         style="background:#ffffff;color:#131b2e;">
                        {{ $r2Count }}x On-Air
                    </div>
                </div>

                {{-- Top Requester 3 --}}
                @php
                    $r3 = $topRequesters->get(2);
                    $r3Name  = $r3 ? $r3->name : 'Reza Fauzi';
                    $r3Kelas = $r3 ? ($r3->kelas ?? 'XII TBSM 2') : 'XII TBSM 2';
                    $r3Count = $r3 ? ($r3->songs_count ?? 4) : 4;
                @endphp
                <div class="rounded-2xl p-3 flex flex-col items-center text-center shadow-sm relative"
                     style="background:#e2e7ff;color:#131b2e;">
                    <div class="absolute -top-2 px-2 py-0.5 rounded-full font-label-sm text-[10px] font-bold"
                         style="background:#ffdad2;color:#a82d11;">
                        🥉 Rank 3
                    </div>
                    <div class="w-12 h-12 rounded-full p-0.5 mt-2 shadow-sm" style="background:#ffffff;">
                        <div class="w-full h-full rounded-full flex items-center justify-center font-black text-base select-none" style="background:#ae3115;color:#ffffff;">
                            {{ strtoupper(substr(implode('', array_map(fn($w) => $w[0], explode(' ', $r3Name))), 0, 2)) }}
                        </div>
                    </div>
                    <p class="font-headline-sm text-[13px] leading-tight font-bold truncate w-full mt-2">{{ $r3Name }}</p>
                    <span class="font-label-sm text-[11px] truncate w-full" style="color:#424936;">{{ $r3Kelas }}</span>
                    <div class="mt-2 px-2 py-1 rounded-lg font-label-sm text-[11px] font-extrabold shadow-sm"
                         style="background:#ffffff;color:#131b2e;">
                        {{ $r3Count }}x On-Air
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════
             5. STICKY PROMPT ACTION CARD
        ══════════════════════════════════════════════ --}}
        <section class="mt-2 rounded-2xl p-4 shadow-lg flex flex-col gap-3 relative overflow-hidden"
                 style="background:#283044;color:#eef0ff;">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                     style="background:#a3e635;color:#416400;">
                    <span class="material-symbols-outlined text-[24px]">rocket_launch</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline-sm text-[16px] leading-tight font-bold">Mau lagumu tembus Top 10?</span>
                    <p class="font-body-sm text-body-sm" style="opacity:0.8;">Kirim request lagu favoritmu di antrian sekarang!</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-1">
                <a href="{{ route('songs.index') }}#antrian"
                   class="py-2.5 px-3 rounded-xl text-center font-label-md text-label-md font-extrabold active:scale-95 transition-transform flex items-center justify-center gap-1 shadow-sm"
                   style="background:#a3e635;color:#416400;">
                    <span class="material-symbols-outlined text-[18px]">queue_music</span>
                    Lihat Antrian
                </a>
                <a href="{{ route('songs.create') }}"
                   class="py-2.5 px-3 rounded-xl text-center font-label-md text-label-md font-bold active:scale-95 transition-transform flex items-center justify-center gap-1 shadow-sm"
                   style="background:#dae2fd;color:#131b2e;">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Request Baru
                </a>
            </div>
        </section>

    </div>{{-- /gap flex flex-col --}}
</main>

<style>
@keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin-slow { animation: spinSlow 8s linear infinite; }

.font-headline-sm  { font-family: 'Space Grotesk', sans-serif; font-size: 18px; line-height: 24px; font-weight: 700; }
.text-headline-sm  { font-size: 18px; line-height: 24px; }
.font-headline-md  { font-family: 'Space Grotesk', sans-serif; font-size: 22px; line-height: 28px; font-weight: 700; }
.text-headline-md  { font-size: 22px; line-height: 28px; }
.font-headline-xl-mobile { font-family: 'Space Grotesk', sans-serif; font-size: 28px; line-height: 34px; letter-spacing: -0.01em; font-weight: 700; }
.text-headline-xl-mobile { font-size: 28px; line-height: 34px; }
.font-label-sm  { font-family: 'Space Grotesk', sans-serif; font-size: 11px; line-height: 14px; letter-spacing: 0.08em; font-weight: 700; }
.text-label-sm  { font-size: 11px; line-height: 14px; }
.font-label-md  { font-family: 'Space Grotesk', sans-serif; font-size: 12px; line-height: 16px; letter-spacing: 0.06em; font-weight: 700; }
.text-label-md  { font-size: 12px; line-height: 16px; }
.font-body-sm   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; line-height: 18px; font-weight: 500; }
.text-body-sm   { font-size: 13px; line-height: 18px; }
.font-body-md   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; line-height: 22px; font-weight: 500; }
.text-body-md   { font-size: 15px; line-height: 22px; }
.text-on-surface { color: #131b2e; }
</style>

</body>
</html>
