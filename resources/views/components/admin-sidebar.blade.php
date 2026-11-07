@props(['active' => ''])

<aside class="w-72 bg-[#f2f3ff] flex flex-col justify-between border-r-2 border-[#131b2e] shadow-[4px_0px_0px_#131b2e] overflow-y-auto hide-scrollbar flex-shrink-0 z-50">

    <div class="flex flex-col">
        {{-- Logo Header --}}
        <div class="h-20 flex items-center px-4 border-b-2 border-[#131b2e] bg-[#eaedff]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 w-full hover:opacity-90 transition-opacity">
                <img src="{{ asset('images/stitch/users_logo.png') }}"
                     onerror="this.src='{{ asset('images/stitch/selada_radio_logo.png') }}'; this.onerror=function(){this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';};"
                     alt="SELADA Radio Logo" class="h-8 w-auto object-contain">
                <div id="logo-fallback" class="hidden size-8 rounded-full bg-[#a3e635] border-2 border-[#131b2e] items-center justify-center shadow-[2px_2px_0px_#131b2e]">
                    <span class="material-symbols-outlined text-lg text-[#416400]">radio</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-lg tracking-tight uppercase leading-none font-display">SELADA</span>
                    <span class="text-[11px] font-bold text-[#424936] tracking-widest uppercase font-mono">BROADCAST BOOTH</span>
                </div>
            </a>
        </div>

        {{-- ON-AIR / OFF-AIR Band --}}
        @if($isOnAir ?? false)
        <div class="px-4 py-2 bg-[#ae3115] text-white flex items-center justify-between border-b-2 border-[#131b2e]">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#ffdad6] animate-ping"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-white">LIVE ON-AIR 🔴</span>
            </div>
            <span class="text-[11px] font-bold bg-[#3d0600] text-[#ffdad2] px-2 py-0.5 rounded font-mono">20FM</span>
        </div>
        @else
        <div class="px-4 py-2 bg-[#424936] text-white flex items-center justify-between border-b-2 border-[#131b2e]">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#c2cab0]"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-white">OFF AIR</span>
            </div>
            <span class="text-[11px] font-bold bg-[#131b2e] text-[#c2cab0] px-2 py-0.5 rounded font-mono">20FM</span>
        </div>
        @endif

        {{-- Navigation Items --}}
        <nav class="flex flex-col gap-1.5 p-4">
            {{-- 0. Ringkasan Studio / Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'dashboard' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">dashboard</span>
                <span>Ringkasan Studio</span>
            </a>

            {{-- 1. DJ Player / Studio --}}
            <a href="{{ route('admin.player.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'player' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">graphic_eq</span>
                <span>DJ Player / Studio</span>
            </a>

            {{-- 2. Moderasi Lagu --}}
            <a href="{{ route('admin.songs.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'songs' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">playlist_add_check</span>
                <span>Moderasi Lagu</span>
            </a>

            {{-- 3. Jadwal Istirahat --}}
            <a href="{{ route('admin.schedules.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'schedules' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">schedule</span>
                <span>Jadwal Istirahat</span>
            </a>

            {{-- 4. Kotak Aspirasi --}}
            <a href="{{ route('admin.feedbacks.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'feedbacks' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">forum</span>
                <span>Kotak Aspirasi</span>
            </a>

            {{-- 5. Laporan Siaran --}}
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'reports' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">bar_chart</span>
                <span>Laporan Siaran</span>
            </a>

            {{-- 6. Manajemen Pengguna --}}
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'users' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">group</span>
                <span>Manajemen Pengguna</span>
            </a>

            {{-- 7. Pengaturan Sistem --}}
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ $active === 'settings' ? 'bg-[#a3e635] text-[#131b2e] shadow-[3px_3px_0px_#131b2e] border-2 border-[#131b2e] font-black' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e] font-bold' }} text-sm transition-all">
                <span class="material-symbols-outlined text-xl">settings</span>
                <span>Pengaturan Sistem</span>
            </a>
        </nav>
    </div>

    {{-- Bottom Status Box --}}
    <div class="p-4 border-t-2 border-[#131b2e] bg-[#dae2fd] flex flex-col gap-2">
        <div class="flex items-center justify-between p-2 bg-[#eaedff] rounded border border-[#131b2e]">
            <div class="flex flex-col">
                <span class="text-[10px] text-[#424936] uppercase font-bold tracking-wider">Mode Approval</span>
                <span class="text-xs text-[#131b2e] font-black">Auto-Antrian</span>
            </div>
            <span class="material-symbols-outlined text-[#446900] text-2xl cursor-pointer">toggle_on</span>
        </div>
        <div class="flex items-center justify-between text-[#424936] text-[11px] font-mono">
            <span>Console v2.4 Y2K</span>
            <span class="font-bold text-[#8127cf]">OSIS SMKN 20</span>
        </div>
    </div>
</aside>
