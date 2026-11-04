<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SELADA') }} — Autentikasi & Login Portal Radio OSIS</title>

    {{-- Google Fonts: Space Grotesk & Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols Outlined --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
        }
        ::-webkit-scrollbar { display: none; }

        /* Stitch Material Symbols utility */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined', sans-serif;
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            vertical-align: middle;
            user-select: none;
        }

        /* Sound wave keyframe delays */
        @keyframes soundWave {
            0%, 100% { height: 6px; }
            50% { height: 26px; }
        }
        .animate-wave-1 { animation: soundWave 0.8s ease-in-out infinite; }
        .animate-wave-2 { animation: soundWave 1.1s ease-in-out 0.15s infinite; }
        .animate-wave-3 { animation: soundWave 0.9s ease-in-out 0.3s infinite; }
        .animate-wave-4 { animation: soundWave 1.2s ease-in-out 0.08s infinite; }
        .animate-wave-5 { animation: soundWave 0.7s ease-in-out 0.22s infinite; }
    </style>
</head>
<body class="bg-background font-body-md text-on-surface antialiased min-h-screen flex flex-col justify-center items-center relative overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container">

    <main class="w-full">
        <div class="flex flex-col w-full items-center justify-center py-6 px-4 md:px-8 relative overflow-hidden">

            {{-- Dynamic Ambient Backing Elements --}}
            <div class="absolute -top-16 -left-16 w-80 h-80 rounded-full bg-primary-container/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 rounded-full bg-secondary-container/15 blur-3xl pointer-events-none"></div>

            {{-- Marquee Ticker Top Strap --}}
            <div class="w-full max-w-4xl mb-6 bg-surface-container-lowest rounded-xl shadow-md p-2 overflow-hidden flex items-center gap-3 border-2 border-on-surface">
                <div class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full font-label-sm text-label-sm uppercase flex items-center gap-1.5 shadow-sm shrink-0 border border-on-surface font-bold">
                    <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                    <span>107.8 FM LIVE</span>
                </div>
                <div class="flex items-center gap-6 text-on-surface-variant font-label-md text-label-md overflow-x-hidden whitespace-nowrap">
                    <span class="flex items-center gap-1.5 text-tertiary font-bold">
                        <span class="material-symbols-outlined text-base">sensors</span>
                        STATUS SERVER: ON-AIR KANTONG FREKUENSI
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5 text-on-surface font-semibold">
                        <span class="material-symbols-outlined text-base">security</span>
                        TIDAK TERBUKA UNTUK UMUM (HANYA SISWA & GURU SMA TELADAN)
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5 text-secondary font-bold">
                        <span class="material-symbols-outlined text-base">graphic_eq</span>
                        REKOR REQUEST HARI INI: 142 LAGU
                    </span>
                </div>
            </div>

            {{-- Main Card Container --}}
            <div class="w-full max-w-2xl bg-surface-container-lowest rounded-xl shadow-[4px_4px_0px_#131b2e] border-2 border-on-surface p-6 sm:p-10 relative">

                {{-- Sticker Elements (Top Corner Decors) --}}
                <div class="absolute -top-5 -left-3 sm:-left-5 bg-tertiary text-on-tertiary px-3.5 py-1.5 rounded-full font-label-sm text-label-sm shadow-md uppercase tracking-wider transform -rotate-6 select-none flex items-center gap-1 z-10 border-2 border-on-surface font-bold">
                    <span class="material-symbols-outlined text-sm">radio</span>
                    <span>SELADA ON-AIR 🔥</span>
                </div>
                <div class="absolute -top-4 -right-2 sm:-right-4 bg-primary-container text-on-primary-container px-3.5 py-1.5 rounded-full font-label-sm text-label-sm shadow-md uppercase transform rotate-3 select-none flex items-center gap-1 z-10 border-2 border-on-surface font-bold">
                    <span class="material-symbols-outlined text-sm">bolt</span>
                    <span>V.2.4 Y2K ED.</span>
                </div>

                {{-- Header Block --}}
                <div class="flex flex-col items-center text-center mt-2 mb-8">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        {{-- Cassette & Wave SVG Icon Badge --}}
                        <div class="w-14 h-14 bg-surface-container rounded-xl flex items-center justify-center text-secondary shadow-sm border-2 border-on-surface">
                            <svg class="w-9 h-9" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <rect fill="currentColor" fill-opacity="0.15" height="28" rx="6" width="40" x="4" y="10"></rect>
                                <rect fill="currentColor" height="20" rx="3" width="32" x="8" y="14"></rect>
                                <circle cx="16" cy="24" fill="#faf8ff" r="4.5"></circle>
                                <circle cx="32" cy="24" fill="#faf8ff" r="4.5"></circle>
                                <path d="M16 27H32" stroke="#faf8ff" stroke-linecap="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        {{-- Sound Wave Animation Indicator --}}
                        <div class="flex items-end gap-1 h-8 bg-surface-container-high px-2.5 py-1.5 rounded-lg border-2 border-on-surface">
                            <div class="w-1 bg-primary rounded-full animate-wave-1"></div>
                            <div class="w-1 bg-secondary rounded-full animate-wave-2"></div>
                            <div class="w-1 bg-tertiary rounded-full animate-wave-3"></div>
                            <div class="w-1 bg-primary rounded-full animate-wave-4"></div>
                            <div class="w-1 bg-secondary rounded-full animate-wave-5"></div>
                        </div>
                    </div>

                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-on-surface uppercase tracking-tight">
                        SELADA
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mt-2 font-medium">
                        Sistem Layanan Dedikasi & Antrian Lagu Radio OSIS SMA Teladan FM 107.8. Masukkan kredensial resmi sekolah untuk akses transmisi.
                    </p>

                    {{-- Badge Warnings Pill --}}
                    <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-high text-on-surface rounded-full font-label-sm text-label-sm border border-on-surface font-semibold">
                            <span class="material-symbols-outlined text-sm text-primary">broadcast_on_home</span>
                            <span>Studio Seksi 8 Teladan</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-tertiary-container text-on-tertiary-container rounded-full font-label-sm text-label-sm border border-on-surface font-semibold">
                            <span class="material-symbols-outlined text-sm">lock_clock</span>
                            <span>Rate Limit: Max 5x Gagal / 15 Menit</span>
                        </span>
                    </div>
                </div>

                {{-- Status Session Notice --}}
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-primary-container/20 text-on-primary-container border-2 border-on-surface font-bold text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Error Alert Banner --}}
                @if (isset($errors) && $errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border-2 border-on-surface font-bold text-sm flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-error text-xl shrink-0 mt-0.5">error</span>
                        <div class="flex flex-col gap-0.5">
                            @foreach ($errors->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Interactive Login Form --}}
                <form class="space-y-6" id="portal-login-form" method="POST" action="{{ route('login') }}" onsubmit="handleLoginSubmit(event)">
                    @csrf

                    {{-- Role Switcher Segmented Cards --}}
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface uppercase tracking-wider mb-2 font-bold">
                            PILIH TIPE AKSES ANDA:
                        </label>
                        <div class="grid grid-cols-2 gap-3 p-1.5 bg-surface-container rounded-xl border-2 border-on-surface">
                            <label class="cursor-pointer">
                                <input checked class="peer sr-only" name="auth_role" onchange="switchRoleMode('siswa')" type="radio" value="siswa">
                                <div class="w-full py-2.5 px-3 rounded-lg text-center flex items-center justify-center gap-2 transition-all font-label-md text-label-md text-on-surface-variant bg-transparent peer-checked:bg-surface-container-lowest peer-checked:text-on-surface peer-checked:shadow-sm peer-checked:border peer-checked:border-on-surface font-bold">
                                    <span class="material-symbols-outlined text-lg">school</span>
                                    <span>Siswa / Siswi</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input class="peer sr-only" name="auth_role" onchange="switchRoleMode('operator')" type="radio" value="operator">
                                <div class="w-full py-2.5 px-3 rounded-lg text-center flex items-center justify-center gap-2 transition-all font-label-md text-label-md text-on-surface-variant bg-transparent peer-checked:bg-secondary peer-checked:text-on-secondary peer-checked:shadow-sm peer-checked:border peer-checked:border-on-surface font-bold">
                                    <span class="material-symbols-outlined text-lg">headset_mic</span>
                                    <span>Operator OSIS / Guru</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Identity Input (NIS / NIP) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-bold" for="identity-input" id="identity-label">
                                Nomor Induk Siswa (NIS)
                            </label>
                            <span class="font-label-sm text-label-sm px-2 py-0.5 rounded bg-surface-container text-on-surface-variant border border-on-surface/40 font-bold" id="role-tag-badge">
                                Format 8 Digit
                            </span>
                        </div>
                        <div class="relative flex items-center">
                            <div class="absolute left-3.5 text-on-surface-variant flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-xl">badge</span>
                            </div>
                            <input class="w-full bg-surface-container-low text-on-surface placeholder:text-on-surface-variant/60 font-body-md text-body-md pl-11 pr-4 py-3 rounded-xl focus:bg-surface-container-lowest focus:outline-none transition-all border-2 border-on-surface font-mono"
                                   id="identity-input" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 22091401 (NIS Siswa)" required autofocus type="text">
                        </div>
                    </div>

                    {{-- Password Input with Peek Button --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-bold" for="password-input">
                                Kata Sandi Akun
                            </label>
                            <a class="font-label-sm text-label-sm text-secondary hover:underline font-bold" href="#hubungi-osis">
                                Butuh Bantuan?
                            </a>
                        </div>
                        <div class="relative flex items-center">
                            <div class="absolute left-3.5 text-on-surface-variant flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-xl">key</span>
                            </div>
                            <input class="w-full bg-surface-container-low text-on-surface placeholder:text-on-surface-variant/60 font-body-md text-body-md pl-11 pr-12 py-3 rounded-xl focus:bg-surface-container-lowest focus:outline-none transition-all border-2 border-on-surface"
                                   id="password-input" name="password" placeholder="Ketik kata sandi Anda..." required autocomplete="current-password" type="password">
                            <button class="absolute right-3 p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors flex items-center justify-center" onclick="togglePasswordVisibility()" title="Lihat / Sembunyikan Sandi" type="button">
                                <span class="material-symbols-outlined text-xl" id="password-toggle-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox & Session Policy --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input class="w-4 h-4 rounded text-primary focus:ring-0 bg-surface-container-low cursor-pointer accent-primary border border-on-surface" id="remember-session" name="remember" type="checkbox">
                            <span class="font-body-sm text-body-sm text-on-surface font-medium">Ingat Sesi di Perangkat Ini</span>
                        </label>
                        <div class="flex items-center gap-1.5 text-on-surface-variant font-label-sm text-label-sm font-semibold">
                            <span class="material-symbols-outlined text-sm">wifi_tethering</span>
                            <span>LAN Siswa SMA 107.8</span>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <div class="pt-2">
                        <button class="w-full py-4 px-6 rounded-xl bg-primary-container text-on-primary-container font-display font-extrabold text-lg uppercase tracking-wide border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] hover:shadow-[2px_2px_0px_#131b2e] hover:translate-x-[2px] hover:translate-y-[2px] active:scale-[0.99] transition-all flex items-center justify-center gap-3 cursor-pointer group" id="submit-cta-btn" type="submit">
                            <span>MASUK KE RADIO OSIS</span>
                            <span class="material-symbols-outlined text-2xl group-hover:rotate-12 transition-transform">bolt</span>
                        </button>
                    </div>

                    {{-- Live Inline Alert/Security Notice (BR-06 & FR-AUTH-02) --}}
                    <div class="bg-surface-container p-3.5 rounded-xl flex items-start gap-3 border border-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-xl shrink-0 mt-0.5">report</span>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">
                            <strong class="font-label-sm text-label-sm text-on-surface block uppercase font-bold">Protokol Autentikasi FR-AUTH-02</strong>
                            Percobaan gagal maksimal 5x sebelum akun terkunci sementara 15 menit demi keamanan stasiun siaran.
                        </div>
                    </div>
                </form>

                {{-- Quick Access Demo Accounts Card Deck --}}
                <div class="mt-8 pt-6 border-t-2 border-on-surface/20 bg-surface-container-low rounded-xl p-4 sm:p-5 border-2 border-on-surface shadow-stitch-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-lg">quick_reference</span>
                            <span class="font-label-md text-label-md text-on-surface uppercase font-bold">Kredensial Demo Cepat (Testing)</span>
                        </div>
                        <span class="font-label-sm text-label-sm bg-secondary-fixed text-on-secondary-fixed px-2.5 py-0.5 rounded-full border border-on-surface font-bold">
                            Siap Klik
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        {{-- Siswa Demo Card --}}
                        <button class="text-left p-3 rounded-lg bg-surface-container-lowest hover:bg-surface-bright transition-all border border-on-surface shadow-stitch-sm flex flex-col justify-between group cursor-pointer" onclick="fillCredentials('22091401', 'Siswa@2024', 'siswa')" type="button">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-label-sm text-label-sm text-primary uppercase font-bold">Siswa</span>
                                <span class="material-symbols-outlined text-xs text-on-surface-variant group-hover:text-primary transition-colors">login</span>
                            </div>
                            <div class="font-label-sm text-label-sm text-on-surface font-mono font-bold">NIS: 22091401</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant truncate font-mono">Pass: Siswa@2024</div>
                        </button>

                        {{-- DJ Operator Demo Card --}}
                        <button class="text-left p-3 rounded-lg bg-surface-container-lowest hover:bg-surface-bright transition-all border border-on-surface shadow-stitch-sm flex flex-col justify-between group cursor-pointer" onclick="fillCredentials('21081322', 'OSIS@Admin', 'operator')" type="button">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-label-sm text-label-sm text-secondary uppercase font-bold">DJ Operator</span>
                                <span class="material-symbols-outlined text-xs text-on-surface-variant group-hover:text-secondary transition-colors">login</span>
                            </div>
                            <div class="font-label-sm text-label-sm text-on-surface font-mono font-bold">NIS: 21081322</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant truncate font-mono">Pass: OSIS@Admin</div>
                        </button>

                        {{-- Pembina Demo Card --}}
                        <button class="text-left p-3 rounded-lg bg-surface-container-lowest hover:bg-surface-bright transition-all border border-on-surface shadow-stitch-sm flex flex-col justify-between group cursor-pointer" onclick="fillCredentials('197205141998022001', 'Guru@Teladan24', 'operator')" type="button">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-label-sm text-label-sm text-tertiary uppercase font-bold">Pembina</span>
                                <span class="material-symbols-outlined text-xs text-on-surface-variant group-hover:text-tertiary transition-colors">login</span>
                            </div>
                            <div class="font-label-sm text-label-sm text-on-surface truncate font-mono font-bold">NIP: 197205141...</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Seksi 8 OSIS</div>
                        </button>
                    </div>
                </div>

                {{-- Student Council Audio Room Footer Contact --}}
                <div class="mt-6 text-center" id="hubungi-osis">
                    <div class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-surface-container rounded-full text-on-surface-variant font-body-sm text-body-sm border border-on-surface/40">
                        <span class="material-symbols-outlined text-base text-secondary shrink-0">support_agent</span>
                        <span>Lupa password? Hubungi <strong>Pengurus OSIS Seksi 8</strong> (Teknologi & Apresiasi Seni) di Ruang Audio Lantai 2.</span>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Micro Interaction Script for Form & Feedback --}}
    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('password-toggle-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        function switchRoleMode(role) {
            const idLabel = document.getElementById('identity-label');
            const idInput = document.getElementById('identity-input');
            const tagBadge = document.getElementById('role-tag-badge');

            if (role === 'operator') {
                idLabel.textContent = 'Nomor Induk Siswa (NIS) atau NIP Guru';
                idInput.placeholder = 'Contoh: 21081322 atau 197205141998022001';
                tagBadge.textContent = 'Akses Konsol OSIS';
                tagBadge.className = 'font-label-sm text-label-sm px-2 py-0.5 rounded bg-secondary-fixed text-on-secondary-fixed border border-on-surface font-bold';
            } else {
                idLabel.textContent = 'Nomor Induk Siswa (NIS)';
                idInput.placeholder = 'Contoh: 22091401 (NIS Siswa)';
                tagBadge.textContent = 'Format 8 Digit';
                tagBadge.className = 'font-label-sm text-label-sm px-2 py-0.5 rounded bg-surface-container text-on-surface-variant border border-on-surface/40 font-bold';
            }
        }

        function fillCredentials(id, pass, role) {
            const idInput = document.getElementById('identity-input');
            const passInput = document.getElementById('password-input');
            const radios = document.getElementsByName('auth_role');

            idInput.value = id;
            passInput.value = pass;

            for (let r of radios) {
                if (r.value === role) {
                    r.checked = true;
                    switchRoleMode(role);
                }
            }

            // Flash visual feedback
            idInput.classList.add('bg-primary-container/20');
            setTimeout(() => idInput.classList.remove('bg-primary-container/20'), 500);
        }

        function handleLoginSubmit(event) {
            const submitBtn = document.getElementById('submit-cta-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="material-symbols-outlined animate-spin text-2xl">sync</span>
                <span>MEMVERIFIKASI FREKUENSI...</span>
            `;
            // Submit form to Laravel backend
            return true;
        }
    </script>
</body>
</html>
