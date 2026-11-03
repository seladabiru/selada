<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <span>💬</span> Kotak Kritik & Saran Siswa
                </h2>
                <p class="text-sm text-gray-500 mt-1">Aspirasi, masukan, ide acara, dan kendala dari siswa untuk Radio OSIS</p>
            </div>
            <div class="flex items-center gap-2">
                @if ($unreadCount > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        {{ $unreadCount }} Belum Dibaca
                    </span>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Filter Bar --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <form method="GET" action="{{ route('admin.feedbacks.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <select name="kategori" class="text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        <option value="Saran" {{ request('kategori') == 'Saran' ? 'selected' : '' }}>💡 Saran</option>
                        <option value="Kritik" {{ request('kategori') == 'Kritik' ? 'selected' : '' }}>📝 Kritik</option>
                        <option value="Laporan Masalah" {{ request('kategori') == 'Laporan Masalah' ? 'selected' : '' }}>⚠️ Kendala Teknis</option>
                        <option value="Permintaan Fitur" {{ request('kategori') == 'Permintaan Fitur' ? 'selected' : '' }}>✨ Usulan Fitur</option>
                    </select>

                    <select name="status" class="text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>

                    <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                        Filter
                    </button>
                    @if (request()->hasAny(['kategori', 'status']))
                        <a href="{{ route('admin.feedbacks.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="text-xs text-gray-500">
                    Total: <strong class="text-gray-800">{{ $feedbacks->total() }}</strong> masukan
                </div>
            </div>

            {{-- List Feedback --}}
            @if ($feedbacks->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                    <div class="text-4xl mb-3">📬</div>
                    <h3 class="text-base font-bold text-gray-800">Belum Ada Kritik & Saran</h3>
                    <p class="text-xs text-gray-500 mt-1">Masukan dari siswa akan tampil di sini saat mereka mengirimkan feedback dari halaman profil.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($feedbacks as $fb)
                        <div class="bg-white rounded-2xl p-5 border {{ $fb->is_read ? 'border-gray-100 opacity-90' : 'border-indigo-200 bg-indigo-50/20' }} shadow-sm transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3 mb-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold
                                        @if($fb->kategori === 'Kritik') bg-rose-100 text-rose-700
                                        @elseif($fb->kategori === 'Saran') bg-indigo-100 text-indigo-700
                                        @elseif($fb->kategori === 'Laporan Masalah') bg-amber-100 text-amber-700
                                        @else bg-purple-100 text-purple-700 @endif">
                                        {{ $fb->kategori }}
                                    </span>

                                    @if (!$fb->is_read)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-400 text-white animate-pulse">
                                            BARU
                                        </span>
                                    @endif

                                    <span class="text-xs font-semibold text-gray-800">{{ $fb->user?->name ?? 'Anonim' }}</span>
                                    <span class="text-xs text-gray-400">({{ $fb->user?->kelas ?? 'Siswa' }} - NIS: {{ $fb->user?->nis }})</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-gray-400">{{ $fb->created_at->isoFormat('D MMMM Y, HH:mm') }}</span>

                                    <form method="POST" action="{{ route('admin.feedbacks.toggle', $fb) }}">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded-lg border {{ $fb->is_read ? 'border-gray-200 text-gray-600 hover:bg-gray-50' : 'border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium' }}">
                                            {{ $fb->is_read ? 'Tandai Belum Dibaca' : '✓ Tandai Selesai' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.feedbacks.destroy', $fb) }}" onsubmit="return confirm('Hapus kritik/saran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 p-1">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $fb->pesan }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $feedbacks->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

