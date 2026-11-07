<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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


        <!-- Dynamic head injections (Material Symbols, etc.) -->
        @stack('head')
    </head>
    <body class="font-sans antialiased text-[#131b2e] bg-[#faf8ff] selection:bg-[#a3e635] selection:text-[#416400]">
        <div class="min-h-screen bg-[#faf8ff]">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur-md border-b border-[#131b2e]/5">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

