<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📊 Laporan & Rekapitulasi Siaran
                </h2>
                <p class="text-xs text-gray-500 mt-1">Laporan riwayat request dan pemutaran lagu sekolah</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank"
                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition flex items-center gap-1.5">
                    <span>🖨</span>
                    <span>Cetak Laporan</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg transition">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu Ringkasan Statistik --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Request</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalRequest }}</p>
                    <p class="text-[11px] text-gray-400 mt-1">Periode terpilih</p>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Berhasil Diputar</p>
                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $totalSelesai }}</p>
                    <p class="text-[11px] text-green-600 mt-1">Status selesai</p>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Ditolak / Gagal</p>
                    <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $totalDitolak }}</p>
                    <p class="text-[11px] text-red-500 mt-1">Tidak memenuhi syarat</p>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Durasi Mengudara</p>
                    <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $totalDurasiMenit }} <span class="text-base font-normal text-gray-500">menit</span></p>
                    <p class="text-[11px] text-indigo-500 mt-1">Total waktu putar</p>
                </div>
            </div>

            {{-- Form Filter Periode, Status, dan Pencarian --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
                    <div>
                        <label for="start_date" class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                               class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                               class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status Lagu</label>
                        <select id="status" name="status" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai Diputar</option>
                            <option value="diputar" {{ $status === 'diputar' ? 'selected' : '' }}>Sedang Diputar</option>
                            <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui (Antrian)</option>
                            <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu Moderasi</option>
                            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="gagal" {{ $status === 'gagal' ? 'selected' : '' }}>Gagal Diputar</option>
                        </select>
                    </div>

                    <div>
                        <label for="q" class="block text-xs font-medium text-gray-700 mb-1">Pencarian</label>
                        <input type="text" id="q" name="q" value="{{ $search }}" placeholder="Judul, siswa, NIS, kelas..."
                               class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-lg transition" title="Reset filter">
                            ↺
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- Tabel Data Laporan (3 Kolom) --}}
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-sm">Daftar Rekapitulasi Request Lagu</h3>
                        <span class="text-xs text-gray-400">Total: {{ $songs->total() }} data</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50/75 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3 w-10">No</th>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Lagu & Channel</th>
                                    <th class="px-4 py-3">Pemohon</th>
                                    <th class="px-4 py-3">Durasi</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($songs as $index => $song)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 text-gray-400 font-mono">
                                            {{ $songs->firstItem() + $index }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                            <p class="font-medium text-gray-700">{{ $song->created_at->format('d/m/Y') }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $song->created_at->format('H:i') }} WIB</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2.5">
                                                @if ($song->thumbnail)
                                                    <img src="{{ $song->thumbnail }}" alt="" class="w-10 h-7 object-cover rounded flex-shrink-0">
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-gray-800 truncate max-w-xs">{{ $song->judul }}</p>
                                                    <p class="text-[10px] text-gray-400 truncate">{{ $song->channel ?: '—' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <p class="font-medium text-gray-800">{{ $song->user?->name ?: '—' }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $song->user?->nis }} · {{ $song->user?->kelas }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 font-mono whitespace-nowrap">
                                            {{ $song->durasi > 0 ? $song->durasiFormatted() : '—' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                @if ($song->status === 'selesai') bg-green-100 text-green-700
                                                @elseif ($song->status === 'diputar') bg-emerald-100 text-emerald-800
                                                @elseif ($song->status === 'disetujui') bg-blue-100 text-blue-700
                                                @elseif ($song->status === 'menunggu') bg-yellow-100 text-yellow-700
                                                @elseif ($song->status === 'ditolak') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-600 @endif">
                                                {{ $song->statusLabel() }}
                                            </span>
                                            @if ($song->alasan_ditolak)
                                                <p class="text-[10px] text-gray-400 italic max-w-xs truncate mt-0.5" title="{{ $song->alasan_ditolak }}">
                                                    Ket: {{ $song->alasan_ditolak }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                            Tidak ada riwayat siaran lagu pada filter tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($songs->hasPages())
                        <div class="p-4 border-t border-gray-100">
                            {{ $songs->links() }}
                        </div>
                    @endif
                </div>

                {{-- Kolom Samping: Siswa Paling Aktif (1 Kolom) --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h4 class="font-bold text-gray-800 text-sm mb-1">🏆 Siswa Teraktif</h4>
                        <p class="text-[11px] text-gray-400 mb-4">Paling banyak request pada periode ini</p>

                        <div class="divide-y divide-gray-100">
                            @forelse ($topSiswa as $idx => $s)
                                <div class="py-2.5 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-5 h-5 rounded-full bg-gray-100 text-gray-600 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $s->name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $s->kelas }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 flex-shrink-0">
                                        {{ $s->songs_count }} lagu
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-3 text-center">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

