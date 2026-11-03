<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">⚙ Dashboard Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Kartu statistik --}}
            @php
                $menunggu  = \App\Models\Song::where('status','menunggu')->count();
                $antrian   = \App\Models\Song::whereIn('status',['disetujui','diputar'])->count();
                $hariIni   = \App\Models\Song::whereDate('created_at', today())->count();
                $jadwalAktif = \App\Models\Schedule::where('is_active', true)->count();
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.songs.index') }}" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center hover:bg-yellow-100 transition">
                    <p class="text-3xl font-bold text-yellow-600">{{ $menunggu }}</p>
                    <p class="text-xs text-yellow-700 mt-1">Menunggu Moderasi</p>
                </a>
                <a href="{{ route('admin.songs.index') }}" class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center hover:bg-blue-100 transition">
                    <p class="text-3xl font-bold text-blue-600">{{ $antrian }}</p>
                    <p class="text-xs text-blue-700 mt-1">Dalam Antrian</p>
                </a>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-3xl font-bold text-gray-600">{{ $hariIni }}</p>
                    <p class="text-xs text-gray-500 mt-1">Request Hari Ini</p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="bg-green-50 border border-green-200 rounded-xl p-4 text-center hover:bg-green-100 transition">
                    <p class="text-3xl font-bold text-green-600">{{ $jadwalAktif }}</p>
                    <p class="text-xs text-green-700 mt-1">Jadwal Aktif</p>
                </a>
            </div>

            {{-- Hero Banner Pemutar Siaran Musik --}}
            <div class="mb-8 p-6 bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 rounded-2xl shadow-lg text-white flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white mb-2">
                        <span>📻</span> Siaran Bel Radio Sekolah
                    </div>
                    <h3 class="text-2xl font-bold">Pemutar Siaran Musik</h3>
                    <p class="text-indigo-100 text-sm max-w-xl">
                        Buka player siaran untuk memutar lagu antrian siswa secara berurutan dengan pemutar YouTube otomatis.
                    </p>
                </div>
                <a href="{{ route('admin.player.index') }}"
                   class="px-6 py-3 bg-white hover:bg-indigo-50 text-indigo-700 font-bold text-sm rounded-xl shadow transition transform hover:-translate-y-0.5 flex items-center gap-2 flex-shrink-0">
                    <span>▶</span>
                    <span>Buka Pemutar Siaran</span>
                </a>
            </div>

            {{-- Menu akses cepat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.player.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition border-2 border-indigo-100">
                    <span class="text-2xl">📻</span>
                    <div>
                        <p class="font-semibold text-gray-800">Pemutar Musik</p>
                        <p class="text-xs text-gray-400">Siaran live YouTube</p>
                    </div>
                </a>
                <a href="{{ route('admin.songs.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <span class="text-2xl">🎵</span>
                    <div>
                        <p class="font-semibold text-gray-800">Moderasi Lagu</p>
                        <p class="text-xs text-gray-400">Setujui atau tolak request</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <span class="text-2xl">📊</span>
                    <div>
                        <p class="font-semibold text-gray-800">Laporan & Rekap</p>
                        <p class="text-xs text-gray-400">Cetak & statistik siaran</p>
                    </div>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <span class="text-2xl">👥</span>
                    <div>
                        <p class="font-semibold text-gray-800">Data Siswa / Pengguna</p>
                        <p class="text-xs text-gray-400">Kelola akun & NIS</p>
                    </div>
                </a>
                <a href="{{ route('admin.schedules.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <span class="text-2xl">🕐</span>
                    <div>
                        <p class="font-semibold text-gray-800">Jadwal Siaran</p>
                        <p class="text-xs text-gray-400">Atur jam istirahat</p>
                    </div>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <span class="text-2xl">⚙</span>
                    <div>
                        <p class="font-semibold text-gray-800">Pengaturan</p>
                        <p class="text-xs text-gray-400">Batas request, blacklist</p>
                    </div>
                </a>
                <a href="{{ route('admin.feedbacks.index') }}"
                   class="flex items-center gap-3 p-4 bg-white shadow rounded-xl hover:shadow-md transition border border-indigo-100">
                    <span class="text-2xl">💬</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800">Kritik & Saran</p>
                            @php
                                $unreadFb = \App\Models\Feedback::where('is_read', false)->count();
                            @endphp
                            @if ($unreadFb > 0)
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                    {{ $unreadFb }} baru
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">Aspirasi & pesan siswa</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
