<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/selada-radio-logo.png') }}">

    <title>{{ config('app.name', 'SELADA') }} — Studio Admin</title>

    {{-- Google Fonts: Space Grotesk + Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Material Symbols Outlined (Stitch icon set) --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

                    <!-- Scripts: Force Login on New Tab -->
        <script>
            (function() {
                const tabIdKey = 'selada_tab_id';

                // Get atau create tab ID untuk tab ini
                let myTabId = sessionStorage.getItem(tabIdKey);
                const isNewTab = !myTabId;

                if (isNewTab) {
                    // Tab baru - generate ID
                    myTabId = 'tab_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                    sessionStorage.setItem(tabIdKey, myTabId);
                }

                @auth
                // Hanya logout jika ini tab baru DAN bukan dari link internal
                if (isNewTab) {
                    const hasOpener = window.opener !== null && !window.opener.closed;
                    const hasReferrer = document.referrer && document.referrer.includes(window.location.host);

                    // Jika tidak ada opener dan tidak ada referrer internal = paste URL manual
                    if (!hasOpener && !hasReferrer) {
                        // AUTO LOGOUT
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', performLogout);
                        } else {
                            performLogout();
                        }

                        function performLogout() {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("logout") }}';
                            form.style.display = 'none';

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            document.body.appendChild(form);
                            form.submit();
                        }

                        throw new Error('Manual tab opening - logout required');
                    }
                }
                @endauth
            })();
        </script>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Stitch Design Token CSS --}}
    <style>
        /* ───── MD3 Color Tokens ───── */
        :root {
            --color-primary:                 #446900;
            --color-on-primary:              #ffffff;
            --color-primary-container:       #a3e635;
            --color-on-primary-container:    #416400;
            --color-primary-fixed:           #b2f746;
            --color-secondary:               #8127cf;
            --color-on-secondary:            #ffffff;
            --color-secondary-container:     #9c48ea;
            --color-on-secondary-container:  #fffbff;
            --color-secondary-fixed:         #f0dbff;
            --color-on-secondary-fixed:      #2c0051;
            --color-tertiary:                #ae3115;
            --color-on-tertiary:             #ffffff;
            --color-tertiary-container:      #ffc5b8;
            --color-on-tertiary-container:   #a82d11;
            --color-tertiary-fixed-dim:      #ffb4a3;
            --color-error:                   #ba1a1a;
            --color-on-error:                #ffffff;
            --color-error-container:         #ffdad6;
            --color-on-error-container:      #93000a;
            --color-surface:                 #faf8ff;
            --color-surface-dim:             #d2d9f4;
            --color-surface-bright:          #faf8ff;
            --color-surface-container-lowest: #ffffff;
            --color-surface-container-low:   #f2f3ff;
            --color-surface-container:       #eaedff;
            --color-surface-container-high:  #e2e7ff;
            --color-surface-container-highest: #dae2fd;
            --color-on-surface:              #131b2e;
            --color-on-surface-variant:      #424936;
            --color-outline:                 #727a64;
            --color-outline-variant:         #c2cab0;
            --color-inverse-surface:         #283044;
            --color-inverse-on-surface:      #eef0ff;
        }

        /* ───── Material Symbols ───── */
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
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            user-select: none;
        }
        .ms-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .ms-sm { font-size: 18px; }
        .ms-xs { font-size: 14px; }
        .ms-lg { font-size: 28px; }

        /* ───── Stitch Typography Scale ───── */
        .ts-label-sm   { font-family: 'Space Grotesk', sans-serif; font-size: 11px; line-height: 14px; letter-spacing: 0.08em; font-weight: 700; }
        .ts-label-md   { font-family: 'Space Grotesk', sans-serif; font-size: 12px; line-height: 16px; letter-spacing: 0.06em; font-weight: 700; }
        .ts-label-lg   { font-family: 'Space Grotesk', sans-serif; font-size: 14px; line-height: 18px; letter-spacing: 0.04em; font-weight: 700; }
        .ts-body-sm    { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; line-height: 18px; font-weight: 500; }
        .ts-body-md    { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; line-height: 22px; font-weight: 500; }
        .ts-body-lg    { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 18px; line-height: 28px; font-weight: 500; }
        .ts-headline-sm{ font-family: 'Space Grotesk', sans-serif; font-size: 18px; line-height: 24px; font-weight: 700; }
        .ts-headline-md{ font-family: 'Space Grotesk', sans-serif; font-size: 22px; line-height: 28px; font-weight: 700; }
        .ts-headline-lg{ font-family: 'Space Grotesk', sans-serif; font-size: 30px; line-height: 38px; letter-spacing: -0.01em; font-weight: 700; }

        /* ───── Stitch Semantic Color Utilities ───── */
        /* backgrounds */
        .stitch-bg-surface                  { background-color: var(--color-surface); }
        .stitch-bg-surface-lowest           { background-color: var(--color-surface-container-lowest); }
        .stitch-bg-surface-low              { background-color: var(--color-surface-container-low); }
        .stitch-bg-surface-container        { background-color: var(--color-surface-container); }
        .stitch-bg-surface-high             { background-color: var(--color-surface-container-high); }
        .stitch-bg-surface-highest          { background-color: var(--color-surface-container-highest); }
        .stitch-bg-surface-bright           { background-color: var(--color-surface-bright); }
        .stitch-bg-primary-container        { background-color: var(--color-primary-container); }
        .stitch-bg-primary-fixed            { background-color: var(--color-primary-fixed); }
        .stitch-bg-secondary                { background-color: var(--color-secondary); }
        .stitch-bg-secondary-container      { background-color: var(--color-secondary-container); }
        .stitch-bg-secondary-fixed          { background-color: var(--color-secondary-fixed); }
        .stitch-bg-tertiary                 { background-color: var(--color-tertiary); }
        .stitch-bg-tertiary-container       { background-color: var(--color-tertiary-container); }
        .stitch-bg-error-container          { background-color: var(--color-error-container); }
        .stitch-bg-inverse-surface          { background-color: var(--color-inverse-surface); }
        .stitch-bg-on-secondary-fixed       { background-color: var(--color-on-secondary-fixed); }
        /* text */
        .stitch-text-on-surface             { color: var(--color-on-surface); }
        .stitch-text-on-surface-variant     { color: var(--color-on-surface-variant); }
        .stitch-text-on-primary-container   { color: var(--color-on-primary-container); }
        .stitch-text-on-secondary-container { color: var(--color-on-secondary-container); }
        .stitch-text-on-secondary-fixed     { color: var(--color-on-secondary-fixed); }
        .stitch-text-on-tertiary            { color: var(--color-on-tertiary); }
        .stitch-text-on-tertiary-container  { color: var(--color-on-tertiary-container); }
        .stitch-text-on-error-container     { color: var(--color-on-error-container); }
        .stitch-text-inverse-on-surface     { color: var(--color-inverse-on-surface); }
        .stitch-text-primary                { color: var(--color-primary); }
        .stitch-text-primary-fixed          { color: var(--color-primary-fixed); }
        .stitch-text-secondary              { color: var(--color-secondary); }
        .stitch-text-tertiary               { color: var(--color-tertiary); }
        .stitch-text-outline                { color: var(--color-outline); }
        .stitch-text-surface-high           { color: var(--color-surface-container-high); }
        .stitch-text-tertiary-fixed-dim     { color: var(--color-tertiary-fixed-dim); }
        /* borders */
        .stitch-border-on-surface           { border-color: var(--color-on-surface); }
        .stitch-border-outline-variant      { border-color: var(--color-outline-variant); }
        .stitch-border-surface-container    { border-color: var(--color-surface-container); }

        /* ───── Neo-Brutalist Shadows ───── */
        .shadow-stitch     { box-shadow: 4px 4px 0px var(--color-on-surface); }
        .shadow-stitch-sm  { box-shadow: 2px 2px 0px var(--color-on-surface); }
        .shadow-stitch-md  { box-shadow: 3px 3px 0px var(--color-on-surface); }
        .shadow-stitch-lg  { box-shadow: 4px 4px 0px var(--color-on-surface); }
        .shadow-stitch-r   { box-shadow: 4px 0px 0px var(--color-on-surface); }
        .shadow-stitch-top { box-shadow: 0px 3px 0px var(--color-on-surface); }

        /* ───── Sidebar ───── */
        .stitch-sidebar { width: 18rem; min-width: 18rem; flex-shrink: 0; }

        /* ───── Nav Items ───── */
        .nav-active {
            background-color: var(--color-primary-container);
            color: var(--color-on-primary-container);
            box-shadow: 3px 3px 0px var(--color-on-surface);
            border: 2px solid var(--color-on-surface);
            font-weight: 700;
        }
        .nav-inactive {
            color: var(--color-on-surface-variant);
            border: 2px solid transparent;
        }
        .nav-inactive:hover {
            background-color: var(--color-surface-container-high);
            color: var(--color-on-surface);
        }

        /* ───── Scrubber ───── */
        .scrubber-track { background: rgba(255, 255, 255, 0.25); }
        .scrubber-fill  { background-color: var(--color-primary-container); transition: width 1s linear; }

        /* ───── Live Dot Animation ───── */
        @keyframes live-ping {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.5); opacity: 0.6; }
        }
        .animate-live-ping { animation: live-ping 1.3s ease-in-out infinite; }

        /* ───── Equalizer & Waveform bars ───── */
        .waveform-bar {
            will-change: height;
            border-radius: 2px 2px 0 0;
            box-shadow: 0 0 8px rgba(163, 230, 53, 0.35);
        }
        @keyframes eq1 { 0%,100%{height:8px}  50%{height:28px} }
        @keyframes eq2 { 0%,100%{height:20px} 50%{height:10px} }
        @keyframes eq3 { 0%,100%{height:12px} 50%{height:30px} }
        @keyframes eq4 { 0%,100%{height:18px} 50%{height:8px}  }
        @keyframes eq5 { 0%,100%{height:6px}  50%{height:24px} }
        .eq-bar-1 { animation: eq1 0.9s ease-in-out infinite; }
        .eq-bar-2 { animation: eq2 1.1s ease-in-out infinite; }
        .eq-bar-3 { animation: eq3 0.8s ease-in-out infinite; }
        .eq-bar-4 { animation: eq4 1.0s ease-in-out infinite; }
        .eq-bar-5 { animation: eq5 1.2s ease-in-out infinite; }

        /* ───── Page Reset for full-screen dashboard ───── */
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        body { background-color: var(--color-surface); }

        /* ───── Volume Slider Accent ───── */
        input[type="range"]#volume-slider { accent-color: var(--color-secondary); }

        /* ───── Scrollbar hide ───── */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body>
    {{ $slot }}
    @include('components.on-air-status-poll')
</body>
</html>
