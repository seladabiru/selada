<nav x-data="{ 
        open: false, 
        currentHash: (window.location.hash || '#antrian')
     }"
     @hashchange.window="currentHash = (window.location.hash || '#antrian')"
     class="sticky top-0 z-40 backdrop-blur-xl bg-[#faf8ff]/90 border-b border-[#131b2e]/5 shadow-[0px_1px_8px_0px_rgba(0,0,0,0.04)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <!-- Figma Header Brand (SELADA Radio Logo + ON AIR) -->
                <a href="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('songs.index') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/selada-radio-logo.png') }}" alt="SELADA Radio Logo" class="w-8 h-8 rounded-full shadow-xs object-contain transition-transform group-hover:scale-105">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-display font-bold text-base sm:text-lg text-[#131b2e] tracking-tight">SELADA Radio</span>
                            <span data-on-air-indicator="on" data-display="flex" class="bg-[#ffc5b8] text-[#a82d11] font-display font-bold text-[10px] tracking-wider uppercase px-1.5 py-0.5 rounded-full flex items-center gap-1 {{ ($isOnAir ?? false) ? '' : 'hidden' }}">
                                <span class="size-1.5 rounded-full bg-[#ae3115] animate-ping"></span>
                                ON AIR
                            </span>
                            <span data-on-air-indicator="off" data-display="flex" class="bg-[#e2e7ff] text-[#424936] font-display font-bold text-[10px] tracking-wider uppercase px-1.5 py-0.5 rounded-full flex items-center gap-1 {{ ($isOnAir ?? false) ? 'hidden' : '' }}">
                                <span class="size-1.5 rounded-full bg-[#727a64]"></span>
                                OFF AIR
                            </span>
                        </div>
                        <span class="font-display font-bold text-[11px] tracking-wider text-[#424936]">Antrian Siaran</span>
                    </div>
                </a>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden sm:flex items-center space-x-2 ms-6">
                    @if (!auth()->user()?->isAdmin())
                        {{-- 📻 Antrian --}}
                        <a href="{{ route('songs.index') }}"
                           @click="if (window.setActiveTab && window.location.pathname.startsWith('/songs')) { window.setActiveTab('antrian'); }"
                           :class="{{ request()->routeIs('songs.index') ? 'true' : 'false' }} && currentHash !== '#my_songs' 
                               ? 'bg-[#a3e635] text-[#416400] shadow-xs' 
                               : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]'"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition flex items-center gap-1 cursor-pointer">
                            <span>📻 Antrian</span>
                        </a>

                        {{-- 📋 Request Saya --}}
                        <a href="{{ route('songs.index') }}#my_songs"
                           @click="if (window.setActiveTab && window.location.pathname.startsWith('/songs')) { window.setActiveTab('my_songs'); }"
                           :class="{{ request()->routeIs('songs.index') ? 'true' : 'false' }} && currentHash === '#my_songs' 
                               ? 'bg-[#a3e635] text-[#416400] shadow-xs' 
                               : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]'"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition flex items-center gap-1 cursor-pointer">
                            <span>📋 Request Saya</span>
                        </a>

                        {{-- 🔥 Top Hits --}}
                        <a href="{{ route('songs.top-hits') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition flex items-center gap-1 {{ request()->routeIs('songs.top-hits') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            <span>🔥 Top Hits</span>
                        </a>

                        {{-- ⚡ Request Lagu --}}
                        <a href="{{ route('songs.create') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition flex items-center gap-1 {{ request()->routeIs('songs.create') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            <span>⚡ Request Lagu</span>
                        </a>
                    @else
                        {{-- Admin Links --}}
                        <a href="{{ route('songs.index') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition {{ request()->routeIs('songs.index') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            📻 Antrian Publik
                        </a>
                        <a href="{{ route('songs.top-hits') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition {{ request()->routeIs('songs.top-hits') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            🔥 Top Hits
                        </a>
                        <a href="{{ route('admin.player.index') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition {{ request()->routeIs('admin.player.*') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            📻 Pemutar Siaran
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.songs.*') || request()->routeIs('admin.schedules.*') || request()->routeIs('admin.settings.*') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            ⚙ Admin Panel
                        </a>
                        <a href="{{ route('admin.feedbacks.index') }}"
                           class="px-3.5 py-1.5 rounded-full font-display font-bold text-xs tracking-wide transition {{ request()->routeIs('admin.feedbacks.*') ? 'bg-[#a3e635] text-[#416400] shadow-xs' : 'text-[#424936] hover:bg-[#e2e7ff] hover:text-[#131b2e]' }}">
                            💬 Kritik & Saran
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings / Profile Avatar (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 p-1.5 rounded-full hover:bg-white/80 transition focus:outline-none cursor-pointer">
                            @php $initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', Auth::user()?->name ?? 'U')))); $initials = substr($initials, 0, 2); @endphp
                            <div class="w-8 h-8 rounded-full border border-white shadow-xs bg-[#a3e635] text-[#416400] flex items-center justify-center font-bold text-xs select-none">{{ $initials }}</div>
                            <span class="font-sans font-semibold text-xs text-[#131b2e]">{{ Auth::user()?->name ?? 'User' }}</span>
                            <svg class="fill-current h-4 w-4 text-[#727a64]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-sans text-xs">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        @if (auth()->user()?->isAdmin())
                            <x-dropdown-link :href="route('admin.dashboard')" class="font-sans text-xs">
                                {{ __('Dashboard Admin') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="font-sans text-xs text-rose-600">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Avatar Trigger -->
            <div class="flex items-center gap-2 sm:hidden">
                <a href="{{ route('profile.edit') }}" class="p-1 rounded-full active:scale-95 transition" aria-label="Profil Siswa">
                    @php $initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', Auth::user()?->name ?? 'U')))); $initials = substr($initials, 0, 2); @endphp
                    <div class="w-8 h-8 rounded-full border border-white shadow-xs bg-[#a3e635] text-[#416400] flex items-center justify-center font-bold text-xs select-none">{{ $initials }}</div>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Figma Bottom Navigation Bar for Mobile -->
<div x-data="{ currentHash: (window.location.hash || '#antrian') }"
     @hashchange.window="currentHash = (window.location.hash || '#antrian')"
     class="fixed bottom-0 left-0 right-0 z-40 h-[72px] bg-[#faf8ff]/95 backdrop-blur-xl border-t border-[#131b2e]/5 shadow-[0px_-2px_12px_0px_rgba(0,0,0,0.05)] sm:hidden flex items-center justify-around px-2 select-none">
    
    <!-- 1. Antrian -->
    @php
        $isOnSongs = request()->routeIs('songs.index');
    @endphp
    <a href="{{ route('songs.index') }}#antrian"
       @click="if (window.setActiveTab && window.location.pathname.startsWith('/songs')) { window.setActiveTab('antrian'); }"
       class="flex flex-col items-center justify-center gap-1 w-12 py-1 text-center transition">
        <template x-if="{{ $isOnSongs ? 'true' : 'false' }} && currentHash !== '#my_songs'">
            <img src="{{ asset('images/figma/icon-queue.svg') }}" class="w-4 h-4 scale-105" alt="Antrian">
        </template>
        <template x-if="!({{ $isOnSongs ? 'true' : 'false' }} && currentHash !== '#my_songs')">
            <img src="{{ asset('images/figma/icon-queue-inactive.svg') }}" class="w-4 h-4 opacity-60" alt="Antrian">
        </template>
        <span class="font-display font-bold text-[10px] tracking-wider transition"
              :class="{{ $isOnSongs ? 'true' : 'false' }} && currentHash !== '#my_songs' ? 'text-[#446900]' : 'text-[#424936]'">
            Antrian
        </span>
    </a>

    <!-- 2. Request Saya -->
    <a href="{{ route('songs.index') }}#my_songs"
       @click="if (window.setActiveTab && window.location.pathname.startsWith('/songs')) { window.setActiveTab('my_songs'); }"
       class="flex flex-col items-center justify-center gap-1 w-14 py-1 text-center transition">
        <template x-if="{{ $isOnSongs ? 'true' : 'false' }} && currentHash === '#my_songs'">
            <img src="{{ asset('images/figma/icon-request-active.svg') }}" class="w-4 h-4 scale-105" alt="Request Saya">
        </template>
        <template x-if="!({{ $isOnSongs ? 'true' : 'false' }} && currentHash === '#my_songs')">
            <img src="{{ asset('images/figma/icon-request.svg') }}" class="w-4 h-4 opacity-60" alt="Request Saya">
        </template>
        <span class="font-display font-bold text-[10px] tracking-wider transition whitespace-nowrap"
              :class="{{ $isOnSongs ? 'true' : 'false' }} && currentHash === '#my_songs' ? 'text-[#446900]' : 'text-[#424936]'">
            Request Saya
        </span>
    </a>

    <!-- 3. Center Floating Action: Request 🎵 -->
    @if (!auth()->user()?->isAdmin())
        <a href="{{ route('songs.create') }}" 
           class="bg-[#a3e635] shadow-[0px_4px_6px_rgba(68,105,0,0.25)] flex items-center gap-1.5 px-3.5 py-2 rounded-full font-display font-bold text-xs text-[#416400] -mt-5 hover:scale-105 active:scale-95 transition {{ request()->routeIs('songs.create') ? 'ring-2 ring-[#446900]' : '' }}">
            <img src="{{ asset('images/figma/icon-music.svg') }}" class="w-3.5 h-3.5" alt="">
            <span>Request 🎵</span>
        </a>
    @else
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-[#a3e635] shadow-[0px_4px_6px_rgba(68,105,0,0.25)] flex items-center gap-1.5 px-3.5 py-2 rounded-full font-display font-bold text-xs text-[#416400] -mt-5 hover:scale-105 active:scale-95 transition">
            <span>⚙ Admin</span>
        </a>
    @endif

    <!-- 4. Top Hits -->
    @php
        $isOnTopHits = request()->routeIs('songs.top-hits');
    @endphp
    <a href="{{ route('songs.top-hits') }}" class="flex flex-col items-center justify-center gap-1 w-12 py-1 text-center transition">
        @if ($isOnTopHits)
            <img src="{{ asset('images/figma/icon-tophits-active.svg') }}" class="w-4 h-4 scale-105" alt="Top Hits">
            <span class="font-display font-bold text-[10px] tracking-wider text-[#446900]">
                Top Hits
            </span>
        @else
            <img src="{{ asset('images/figma/icon-tophits.svg') }}" class="w-4 h-4 opacity-60" alt="Top Hits">
            <span class="font-display font-bold text-[10px] tracking-wider text-[#424936]">
                Top Hits
            </span>
        @endif
    </a>

    <!-- 5. Profil -->
    @php
        $isOnProfile = request()->routeIs('profile.*');
    @endphp
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-1 w-12 py-1 text-center transition">
        @if ($isOnProfile)
            <img src="{{ asset('images/figma/icon-profile-nav-active.svg') }}" class="w-4 h-4 scale-105" alt="Profil">
            <span class="font-display font-bold text-[10px] tracking-wider text-[#446900]">
                Profil
            </span>
        @else
            <img src="{{ asset('images/figma/icon-profile-nav.svg') }}" class="w-4 h-4 opacity-60" alt="Profil">
            <span class="font-display font-bold text-[10px] tracking-wider text-[#424936]">
                Profil
            </span>
        @endif
    </a>
</div>
