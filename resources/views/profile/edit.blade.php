<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Siswa — SELADA Radio</title>

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
    <div class="flex flex-col w-full gap-6 pb-14">

        {{-- ── SUCCESS / ERROR ALERTS ── --}}
        @if (session('feedback_status'))
            <div class="rounded-xl px-4 py-3 flex items-center gap-3 shadow-sm"
                 style="background:#a3e635;color:#416400;">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="font-label-md text-label-md font-bold">{{ session('feedback_status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl px-4 py-3 flex items-start gap-3 shadow-sm"
                 style="background:#ffdad6;color:#93000a;">
                <span class="material-symbols-outlined text-[20px] mt-0.5 flex-shrink-0">error</span>
                <ul class="flex flex-col gap-0.5">
                    @foreach ($errors->all() as $error)
                        <li class="font-body-sm text-body-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════
             SECTION 1 — TOP HERO BADGE / KARTU ANGGOTA
        ══════════════════════════════════════════════ --}}
        <div class="relative rounded-xl p-4 shadow-md overflow-hidden" style="background:#f0dbff;color:#2c0051;">
            {{-- Decorative circle --}}
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full pointer-events-none"
                 style="background:rgba(156,72,234,0.2);"></div>
            <div class="flex items-center justify-between gap-1 relative z-10">
                <div class="flex items-center gap-2">
                    <span class="inline-block px-2.5 py-1 rounded-lg shadow-sm font-label-sm text-label-sm uppercase font-bold"
                          style="background:#a3e635;color:#416400;transform:rotate(-2deg);">
                        KARTU ANGGOTA OSIS ✨
                    </span>
                    <span class="font-label-sm text-label-sm tracking-wide" style="opacity:0.80;">EDISI 2024/2025</span>
                </div>
                <span class="material-symbols-outlined text-2xl" style="color:#8127cf;font-variation-settings:'FILL' 1;">stars</span>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 2 — KARTU HEADER PROFIL SISWA
        ══════════════════════════════════════════════ --}}
        <div class="rounded-xl p-4 shadow-md flex flex-col gap-4 relative overflow-hidden"
             style="background:#ffffff;">
            {{-- Background glow --}}
            <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full pointer-events-none"
                 style="background:rgba(163,230,53,0.25);"></div>

            {{-- Avatar + Info row --}}
            <div class="flex items-start gap-4 relative z-10">
                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-xl overflow-hidden shadow-sm" style="background:#e2e7ff;">
                        <img class="w-full h-full object-cover"
                             src="{{ asset('images/stitch/profil_avatar.jpg') }}"
                             alt="Avatar Siswa"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=a3e635&color=416400&size=80&font-size=0.4&bold=true'"/>
                    </div>
                    {{-- AKTIF badge --}}
                    <span class="absolute -bottom-2 -left-1 px-1.5 py-0.5 rounded-lg shadow-sm font-label-sm text-[10px] uppercase flex items-center gap-0.5"
                          style="background:#a3e635;color:#416400;transform:rotate(2deg);">
                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#446900;"></span>
                        AKTIF ✨
                    </span>
                </div>

                {{-- Info details --}}
                <div class="flex flex-col min-w-0 flex-grow pt-0.5">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <h1 class="font-headline-md text-headline-md text-on-surface truncate">{{ $user->name }}</h1>
                        <span class="px-2 py-0.5 rounded-full font-label-sm text-[10px] uppercase flex items-center gap-1"
                              style="background:#e2e7ff;color:#424936;">
                            <span class="material-symbols-outlined text-[13px]" style="color:#446900;font-variation-settings:'FILL' 1;">verified</span>
                            NIS VALID
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm mt-0.5" style="color:#424936;">
                        Kelas {{ $user->kelas ?? 'XII MIPA 1' }} •
                        <span class="font-label-sm text-label-sm font-bold" style="color:#8127cf;">SMAN 1 TELADAN</span>
                    </p>
                    <p class="font-label-sm text-label-sm tracking-wider mt-1" style="color:#727a64;">
                        NIS: {{ $user->nis ?? '-' }} • ROLE: {{ strtoupper($user->role ?? 'SISWA') }}
                    </p>
                </div>
            </div>

            {{-- Kuota tracker --}}
            <div class="rounded-xl p-3 flex flex-col gap-1.5" style="background:#f2f3ff;">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]" style="color:#ae3115;">music_note</span>
                        <span class="font-label-md text-label-md text-on-surface">Kuota Request Hari Ini:</span>
                    </div>
                    @if ($sisaKuota <= 0)
                        <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm font-bold"
                              style="background:#ffdad6;color:#93000a;">
                            0/1 (Sudah Dipakai)
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm font-bold"
                              style="background:#a3e635;color:#416400;">
                            1/1 (Tersedia)
                        </span>
                    @endif
                </div>
                {{-- Progress bar --}}
                <div class="w-full h-2 rounded-full overflow-hidden" style="background:#dae2fd;">
                    @if ($sisaKuota <= 0)
                        <div class="h-full w-full rounded-full" style="background:#ae3115;"></div>
                    @else
                        <div class="h-full rounded-full" style="background:#a3e635;width:0%;"></div>
                    @endif
                </div>
                <div class="flex items-center justify-between text-[12px] pt-0.5" style="color:#424936;">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">history_toggle_off</span>
                        Reset otomatis istirahat esok
                    </span>
                    <span class="font-label-sm text-label-sm font-bold px-2 py-0.5 rounded"
                          style="background:#e2e7ff;color:#131b2e;">
                        15:00 WIB
                    </span>
                </div>
            </div>

            {{-- BR-06 Kebijakan notice --}}
            <div class="rounded-xl p-3 flex items-start gap-3" style="background:rgba(255,197,184,0.4);color:#a82d11;">
                <span class="material-symbols-outlined text-[20px] flex-shrink-0 mt-0.5" style="color:#ae3115;">security</span>
                <div class="flex flex-col min-w-0">
                    <span class="font-label-sm text-label-sm uppercase font-bold tracking-wide">Pemberitahuan Kebijakan BR-06</span>
                    <p class="font-body-sm text-body-sm mt-0.5 leading-snug" style="color:#424936;">
                        Akun siswa tersinkronisasi terpusat dengan Data Induk Tata Usaha & Pengurus OSIS. Penghapusan akun mandiri ditiadakan demi validitas antrian siaran radio.
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 3 — RINGKASAN AKTIVITAS (STATS GRID)
        ══════════════════════════════════════════════ --}}
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-1.5">
                    <span class="font-headline-sm text-headline-sm text-on-surface">Track Record Kamu</span>
                    <span class="px-2 py-0.5 rounded-full font-label-sm text-[10px] uppercase"
                          style="background:#a3e635;color:#416400;">On Air</span>
                </div>
                <span class="font-label-sm text-label-sm" style="color:#424936;">Semester Genap</span>
            </div>

            <div class="grid grid-cols-3 gap-2">
                {{-- Stat 1: Lagu Diminta --}}
                <div class="rounded-xl p-3 flex flex-col items-center text-center shadow-sm relative overflow-hidden"
                     style="background:#ffffff;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1"
                         style="background:#f0dbff;color:#2c0051;">
                        <span class="material-symbols-outlined text-[18px]">queue_music</span>
                    </div>
                    <span class="font-extrabold leading-none text-on-surface" style="font-size:22px;line-height:1;">
                        {{ $totalRequests }}
                    </span>
                    <span class="mt-1 leading-tight" style="font-size:11px;color:#424936;">Lagu Diminta</span>
                </div>

                {{-- Stat 2: Lolos Siaran --}}
                <div class="rounded-xl p-3 flex flex-col items-center text-center shadow-sm relative overflow-hidden"
                     style="background:#ffffff;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1"
                         style="background:#b2f746;color:#121f00;">
                        <span class="material-symbols-outlined text-[18px]"
                              style="font-variation-settings:'FILL' 1;">play_circle</span>
                    </div>
                    <span class="font-extrabold leading-none" style="font-size:22px;line-height:1;color:#446900;">
                        {{ $lagusLolos }}
                    </span>
                    <span class="mt-1 leading-tight" style="font-size:11px;color:#424936;">Lolos Siaran</span>
                </div>

                {{-- Stat 3: Api / Upvotes --}}
                <div class="rounded-xl p-3 flex flex-col items-center text-center shadow-sm relative overflow-hidden"
                     style="background:#ffffff;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1"
                         style="background:#ffdad2;color:#3d0600;">
                        <span class="material-symbols-outlined text-[18px]"
                              style="font-variation-settings:'FILL' 1;">local_fire_department</span>
                    </div>
                    <span class="font-extrabold leading-none" style="font-size:22px;line-height:1;color:#ae3115;">
                        {{ $totalUpvotes }}
                    </span>
                    <span class="mt-1 leading-tight" style="font-size:11px;color:#424936;">Api / Upvotes</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 4 — KOTAK ASPIRASI OSIS (FR-FDB-01)
        ══════════════════════════════════════════════ --}}
        <div class="rounded-xl p-4 shadow-md flex flex-col gap-4 relative overflow-hidden"
             style="background:#ffffff;">
            {{-- Header --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0"
                         style="background:#a3e635;color:#416400;">
                        <span class="material-symbols-outlined text-2xl">campaign</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="font-headline-sm text-headline-sm text-on-surface">Kotak Aspirasi OSIS</span>
                            <span class="px-2 py-0.5 rounded-lg font-label-sm text-[10px] uppercase font-bold shadow-sm"
                                  style="background:#f0dbff;color:#2c0051;">
                                FR-FDB-01 📬
                            </span>
                        </div>
                        <p class="font-body-sm text-body-sm mt-0.5 leading-snug" style="color:#424936;">
                            Punya ide tema lagu istirahat, keluhan audio speaker, atau pesan khusus ke DJ? Tumpahkan di sini!
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('profile.feedback.store') }}" id="aspirationForm">
                @csrf
                <input type="hidden" name="kategori" id="selectedKategori" value="Saran">

                {{-- Category pills --}}
                <div class="flex flex-col gap-1.5 mb-4">
                    <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider">Pilih Topik Aspirasi:</span>
                    <div class="flex items-center gap-1.5 flex-wrap" id="categoryPillGroup">
                        <button class="category-pill px-3 py-1.5 rounded-xl font-label-sm text-label-sm transition-all shadow-sm font-bold active-pill"
                                data-cat="Saran" data-label="💡 Saran Lagu/Acara" type="button">
                            💡 Saran Lagu/Acara
                        </button>
                        <button class="category-pill px-3 py-1.5 rounded-xl font-label-sm text-label-sm transition-all shadow-sm inactive-pill"
                                data-cat="Kritik" data-label="💬 Kritik Membangun" type="button">
                            💬 Kritik Membangun
                        </button>
                        <button class="category-pill px-3 py-1.5 rounded-xl font-label-sm text-label-sm transition-all shadow-sm inactive-pill"
                                data-cat="Laporan Masalah" data-label="⚠️ Masalah Speaker" type="button">
                            ⚠️ Masalah Speaker
                        </button>
                        <button class="category-pill px-3 py-1.5 rounded-xl font-label-sm text-label-sm transition-all shadow-sm inactive-pill"
                                data-cat="Permintaan Fitur" data-label="✨ Request Fitur App" type="button">
                            ✨ Request Fitur App
                        </button>
                    </div>
                </div>

                {{-- Textarea --}}
                <div class="flex flex-col gap-1 mb-4">
                    <div class="relative">
                        <textarea
                            class="w-full rounded-xl p-3 font-body-md text-body-md focus:outline-none transition-all resize-none"
                            style="background:#f2f3ff;color:#131b2e;"
                            id="aspirationTextarea"
                            name="pesan"
                            maxlength="300"
                            placeholder="Contoh: Min, speaker koridor lantai 2 agak kresek-kresek tolong dibenerin ya, sama playlist anime lo-fi pas hari Jumat seru tuh!"
                            rows="3">{{ old('pesan') }}</textarea>
                    </div>
                    <div class="flex items-center justify-between px-1">
                        <span class="font-label-sm text-[11px] flex items-center gap-1" style="color:#727a64;">
                            <span class="material-symbols-outlined text-[13px]">lock</span>
                            Identitas NIS terverifikasi aman
                        </span>
                        <span class="font-label-sm text-label-sm" id="charCounter" style="color:#424936;">0 / 300</span>
                    </div>
                </div>

                {{-- Submit button --}}
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-headline-sm text-headline-sm tracking-wide shadow-md hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2 font-bold"
                        style="background:#a3e635;color:#416400;"
                        id="btnSendAspiration">
                    <span class="material-symbols-outlined text-[20px]">send</span>
                    <span>KIRIM MASUKAN KE OSIS 🚀</span>
                </button>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 5 — RIWAYAT ASPIRASI
        ══════════════════════════════════════════════ --}}
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[20px]" style="color:#8127cf;">history_edu</span>
                    <span class="font-headline-sm text-headline-sm text-on-surface">Riwayat Aspirasi Kamu</span>
                </div>
                <span class="font-label-sm text-label-sm font-bold" style="color:#8127cf;">
                    {{ $feedbacks->count() }} Terkirim
                </span>
            </div>

            @forelse ($feedbacks as $feedback)
                @php
                    // Map kategori label to badge style
                    $katLabel = match($feedback->kategori) {
                        'Saran'            => 'SARAN ACARA',
                        'Kritik'           => 'KRITIK',
                        'Laporan Masalah'  => 'LAPORAN MASALAH',
                        'Permintaan Fitur' => 'REQUEST FITUR',
                        default            => strtoupper($feedback->kategori),
                    };

                    // Status badge logic
                    if ($feedback->balasan) {
                        $statusBg    = '#ffc5b8';
                        $statusColor = '#a82d11';
                        $statusIcon  = 'engineering';
                        $statusText  = 'DITINDAKLANJUTI 🔧';
                    } elseif ($feedback->is_read) {
                        $statusBg    = '#a3e635';
                        $statusColor = '#416400';
                        $statusIcon  = 'done_all';
                        $statusText  = 'DIBACA OSIS ✅';
                    } else {
                        $statusBg    = '#e2e7ff';
                        $statusColor = '#424936';
                        $statusIcon  = 'schedule';
                        $statusText  = 'MENUNGGU';
                    }
                @endphp

                <div class="rounded-xl p-4 shadow-sm flex flex-col gap-2" style="background:#ffffff;">
                    {{-- Top row: kategori + status --}}
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        @if ($feedback->balasan)
                            <span class="px-2 py-0.5 rounded-lg font-label-sm text-[10px] uppercase font-bold"
                                  style="background:#e2e7ff;color:#424936;">
                                {{ $katLabel }}
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-lg font-label-sm text-[10px] uppercase font-bold"
                                  style="background:#f0dbff;color:#2c0051;">
                                {{ $katLabel }}
                            </span>
                        @endif
                        <span class="px-2 py-0.5 rounded-full font-label-sm text-[10px] uppercase font-bold flex items-center gap-1"
                              style="background:{{ $statusBg }};color:{{ $statusColor }};">
                            <span class="material-symbols-outlined text-[13px]">{{ $statusIcon }}</span>
                            {{ $statusText }}
                        </span>
                    </div>

                    {{-- Message --}}
                    <p class="font-body-md text-body-md text-on-surface font-semibold leading-snug">
                        "{{ $feedback->pesan }}"
                    </p>

                    {{-- OSIS Reply block --}}
                    @if ($feedback->balasan)
                        <div class="rounded-lg p-3 mt-1 flex items-start gap-2 text-on-surface"
                             style="background:#e2e7ff;">
                            @if ($feedback->is_read)
                                <span class="material-symbols-outlined text-[18px] mt-0.5 flex-shrink-0"
                                      style="color:#446900;">mark_chat_read</span>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-label-sm text-label-sm font-bold"
                                          style="color:#446900;">Balasan DJ OSIS:</span>
                                    <p class="font-body-sm text-body-sm mt-0.5" style="color:#424936;">
                                        "{{ $feedback->balasan }}"
                                    </p>
                                </div>
                            @else
                                <span class="material-symbols-outlined text-[18px] mt-0.5 flex-shrink-0"
                                      style="color:#ae3115;">build_circle</span>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-label-sm text-label-sm font-bold"
                                          style="color:#ae3115;">Tim Audio OSIS:</span>
                                    <p class="font-body-sm text-body-sm mt-0.5" style="color:#424936;">
                                        "{{ $feedback->balasan }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Timestamp --}}
                    <span class="font-label-sm text-[11px] text-right" style="color:#727a64;">
                        {{ $feedback->created_at->diffForHumans() }} • {{ $feedback->created_at->format('H:i') }} WIB
                    </span>
                </div>
            @empty
                <div class="rounded-xl p-6 flex flex-col items-center text-center gap-2" style="background:#f2f3ff;">
                    <span class="material-symbols-outlined text-[40px]" style="color:#c2cab0;">inbox</span>
                    <span class="font-label-md text-label-md" style="color:#424936;">Belum ada aspirasi terkirim</span>
                    <p class="font-body-sm text-body-sm" style="color:#727a64;">Yuk sampaikan idemu ke OSIS lewat form di atas!</p>
                </div>
            @endforelse
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 6 — PENGATURAN & KEAMANAN
        ══════════════════════════════════════════════ --}}
        <div class="flex flex-col gap-3 pt-1">
            <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider px-1">Pengaturan & Keamanan</span>

            <div class="rounded-xl p-2 shadow-sm flex flex-col gap-1" style="background:#ffffff;">
                {{-- Ganti Kata Sandi --}}
                <button type="button" onclick="openPasswordModal()"
                   class="flex items-center justify-between p-3 rounded-lg transition-colors group w-full text-left"
                   onmouseover="this.style.background='#f2f3ff'" onmouseout="this.style.background=''">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors group-hover:bg-primary-container"
                             style="background:#e2e7ff;color:#424936;">
                            <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-headline-sm text-headline-sm text-on-surface">Ganti Kata Sandi</span>
                            <span class="font-body-sm text-body-sm" style="color:#424936;">Update sandi login portal OSIS kamu</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined" style="color:#727a64;">chevron_right</span>
                </button>

                {{-- Divider --}}
                <div class="h-px mx-3" style="background:#e2e7ff;"></div>

                {{-- Pedoman --}}
                <button type="button" onclick="openPedomanModal()"
                   class="flex items-center justify-between p-3 rounded-lg transition-colors group w-full text-left"
                   onmouseover="this.style.background='#f2f3ff'" onmouseout="this.style.background=''">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors"
                             style="background:#e2e7ff;color:#424936;">
                            <span class="material-symbols-outlined text-[20px]">menu_book</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-headline-sm text-headline-sm text-on-surface">Pedoman & Etika Dedikasi</span>
                            <span class="font-body-sm text-body-sm" style="color:#424936;">Aturan titip salam & anti-bullying</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined" style="color:#727a64;">chevron_right</span>
                </button>

                {{-- Divider --}}
                <div class="h-px mx-3" style="background:#e2e7ff;"></div>

                {{-- Keluar --}}
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="submit"
                            class="flex items-center justify-between p-3 rounded-lg transition-colors w-full text-left group"
                            onmouseover="this.style.background='rgba(255,218,214,0.3)'" onmouseout="this.style.background=''">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors"
                                 style="background:rgba(255,218,214,0.5);color:#ba1a1a;">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-headline-sm text-headline-sm" style="color:#ba1a1a;">Keluar dari Akun</span>
                                <span class="font-body-sm text-body-sm" style="color:#727a64;">Keluar dari sesi perangkat ini</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined" style="color:rgba(186,26,26,0.6);">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SECTION 7 — FOOTER SIGNATURE
        ══════════════════════════════════════════════ --}}
        <div class="text-center py-3">
            <p class="font-label-sm text-[11px] uppercase tracking-widest" style="color:#727a64;">
                SELADA RADIO • SEKSI KREATIF & IT OSIS
            </p>
            <p class="font-body-sm text-[11px] mt-0.5" style="color:#c2cab0;">Versi WebApp 2.4.0 (Build Smansa)</p>
        </div>

    </div>{{-- /gap flex flex-col --}}
</main>

{{-- ===================== MODAL GANTI KATA SANDI ===================== --}}
<div id="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden" style="backdrop-filter:blur(6px);">
    <div class="w-full max-w-md rounded-2xl p-6 shadow-2xl relative" style="background:#ffffff;border:1px solid #eaedff;">
        <button type="button" onclick="closePasswordModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#f0dbff;color:#2c0051;">
                <span class="material-symbols-outlined text-[22px]">lock_reset</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Ubah Kata Sandi</h3>
                <p class="font-body-sm text-body-sm" style="color:#727a64;">Perbarui sandi akun portal SELADA kamu</p>
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-label-sm text-label-sm uppercase mb-1" style="color:#424936;">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-xl px-3 py-2.5 font-body-md text-body-md border transition-all focus:outline-none"
                       style="background:#f2f3ff;border-color:#dae2fd;color:#131b2e;"
                       placeholder="••••••••">
                @if ($errors->updatePassword->get('current_password'))
                    <span class="text-xs text-red-500 mt-1 block">{{ $errors->updatePassword->first('current_password') }}</span>
                @endif
            </div>

            <div>
                <label class="block font-label-sm text-label-sm uppercase mb-1" style="color:#424936;">Kata Sandi Baru</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl px-3 py-2.5 font-body-md text-body-md border transition-all focus:outline-none"
                       style="background:#f2f3ff;border-color:#dae2fd;color:#131b2e;"
                       placeholder="Minimal 8 karakter">
                @if ($errors->updatePassword->get('password'))
                    <span class="text-xs text-red-500 mt-1 block">{{ $errors->updatePassword->first('password') }}</span>
                @endif
            </div>

            <div>
                <label class="block font-label-sm text-label-sm uppercase mb-1" style="color:#424936;">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-xl px-3 py-2.5 font-body-md text-body-md border transition-all focus:outline-none"
                       style="background:#f2f3ff;border-color:#dae2fd;color:#131b2e;"
                       placeholder="Ulangi kata sandi baru">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="closePasswordModal()"
                        class="w-1/2 py-2.5 px-4 rounded-xl font-label-md text-label-md transition-colors text-center"
                        style="background:#e2e7ff;color:#424936;">
                    Batal
                </button>
                <button type="submit"
                        class="w-1/2 py-2.5 px-4 rounded-xl font-label-md text-label-md font-bold shadow-md hover:opacity-90 transition-all text-center"
                        style="background:#a3e635;color:#416400;">
                    Simpan Sandi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL PEDOMAN & ETIKA ===================== --}}
<div id="pedomanModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden" style="backdrop-filter:blur(6px);">
    <div class="w-full max-w-md rounded-2xl p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto" style="background:#ffffff;border:1px solid #eaedff;">
        <button type="button" onclick="closePedomanModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#ffdad2;color:#3d0600;">
                <span class="material-symbols-outlined text-[22px]">menu_book</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Pedoman & Etika Dedikasi</h3>
                <p class="font-body-sm text-body-sm" style="color:#727a64;">Aturan siaran radio & titip salam OSIS</p>
            </div>
        </div>

        <div class="space-y-3 font-body-sm text-body-sm" style="color:#424936;">
            <div class="p-3 rounded-xl" style="background:#f2f3ff;">
                <div class="font-bold flex items-center gap-1.5 mb-1" style="color:#131b2e;">
                    <span class="material-symbols-outlined text-[18px]" style="color:#446900;">check_circle</span>
                    1. Hak Cipta & Konten Lagu
                </div>
                <p>Hanya request lagu yang sopan, tidak mengandung kata-kata kasar/eksplisit, ujaran kebencian, atau SARA.</p>
            </div>

            <div class="p-3 rounded-xl" style="background:#f2f3ff;">
                <div class="font-bold flex items-center gap-1.5 mb-1" style="color:#131b2e;">
                    <span class="material-symbols-outlined text-[18px]" style="color:#8127cf;">favorite</span>
                    2. Titip Salam & Pesan Dedikasi
                </div>
                <p>Dilarang menyebarkan hoaks, rumor pribadi yang merugikan, atau sindiran negatif. Utamakan semangat positif dan persahabatan antar siswa!</p>
            </div>

            <div class="p-3 rounded-xl" style="background:#f2f3ff;">
                <div class="font-bold flex items-center gap-1.5 mb-1" style="color:#131b2e;">
                    <span class="material-symbols-outlined text-[18px]" style="color:#ae3115;">shield</span>
                    3. Kebijakan Anti-Bullying
                </div>
                <p>Setiap salam yang dibaca DJ diverifikasi dan tercatat ke NIS siswa. Pelanggaran serius akan diteruskan ke Tim BK Sekolah.</p>
            </div>
        </div>

        <button type="button" onclick="closePedomanModal()"
                class="w-full mt-5 py-2.5 px-4 rounded-xl font-label-md text-label-md font-bold shadow-md hover:opacity-90 transition-all text-center"
                style="background:#a3e635;color:#416400;">
            Saya Mengerti 👍
        </button>
    </div>
</div>

{{-- ===================== SCRIPTS ===================== --}}
<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}
function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}
function openPedomanModal() {
    document.getElementById('pedomanModal').classList.remove('hidden');
}
function closePedomanModal() {
    document.getElementById('pedomanModal').classList.add('hidden');
}

@if ($errors->updatePassword->any())
document.addEventListener('DOMContentLoaded', function() {
    openPasswordModal();
});
@endif

(function() {
    // Category pill selector
    const pills = document.querySelectorAll('.category-pill');
    const selectedKategori = document.getElementById('selectedKategori');

    const activePillStyle   = { background: '#a3e635', color: '#416400' };
    const inactivePillStyle = { background: '#e2e7ff', color: '#424936' };

    function applyPillStyle(pill, isActive) {
        if (isActive) {
            pill.style.background = activePillStyle.background;
            pill.style.color      = activePillStyle.color;
            pill.style.fontWeight = '700';
        } else {
            pill.style.background = inactivePillStyle.background;
            pill.style.color      = inactivePillStyle.color;
            pill.style.fontWeight = '500';
        }
    }

    // Init styles
    pills.forEach(pill => {
        const isActive = pill.classList.contains('active-pill');
        applyPillStyle(pill, isActive);
        pill.classList.remove('active-pill', 'inactive-pill');

        pill.addEventListener('click', function() {
            pills.forEach(p => applyPillStyle(p, false));
            applyPillStyle(pill, true);
            if (selectedKategori) {
                selectedKategori.value = pill.dataset.cat;
            }
        });
    });

    // Character counter
    const textarea   = document.getElementById('aspirationTextarea');
    const charCounter = document.getElementById('charCounter');

    if (textarea && charCounter) {
        // Init counter with old() value
        charCounter.textContent = textarea.value.length + ' / 300';

        textarea.addEventListener('input', function() {
            const len = textarea.value.length;
            charCounter.textContent = len + ' / 300';
            if (len >= 280) {
                charCounter.style.color  = '#ba1a1a';
                charCounter.style.fontWeight = '700';
            } else {
                charCounter.style.color  = '#424936';
                charCounter.style.fontWeight = '500';
            }
        });
    }

    // Submit micro-interaction
    const form    = document.getElementById('aspirationForm');
    const btnSend = document.getElementById('btnSendAspiration');

    if (form && btnSend && textarea) {
        form.addEventListener('submit', function(e) {
            if (!textarea.value.trim()) {
                e.preventDefault();
                textarea.focus();
                textarea.style.background = 'rgba(255,218,214,0.4)';
                setTimeout(() => { textarea.style.background = '#f2f3ff'; }, 1000);
                return;
            }
            const originalHTML = btnSend.innerHTML;
            btnSend.disabled   = true;
            btnSend.innerHTML  = `
                <span class="material-symbols-outlined text-[20px]" style="animation:spin 1s linear infinite;">progress_activity</span>
                <span>MENGIRIM KE OSIS...</span>
            `;
        });
    }
})();
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.font-headline-sm  { font-family: 'Space Grotesk', sans-serif; font-size: 18px; line-height: 24px; font-weight: 700; }
.text-headline-sm  { font-size: 18px; line-height: 24px; }
.font-headline-md  { font-family: 'Space Grotesk', sans-serif; font-size: 22px; line-height: 28px; font-weight: 700; }
.text-headline-md  { font-size: 22px; line-height: 28px; }
.font-headline-lg-mobile { font-family: 'Space Grotesk', sans-serif; font-size: 22px; line-height: 28px; font-weight: 700; }
.text-headline-lg-mobile { font-size: 22px; line-height: 28px; }
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
