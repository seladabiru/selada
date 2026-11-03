<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">🕐 Jadwal Siaran</h2>
            <a href="{{ route('admin.schedules.create') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                + Tambah Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow rounded-xl overflow-hidden">
                @if ($schedules->isEmpty())
                    <div class="p-10 text-center text-gray-400 text-sm">
                        Belum ada jadwal. Tambahkan jadwal jam istirahat.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($schedules as $s)
                            <div class="flex items-center gap-4 px-5 py-4">
                                {{-- Status indicator --}}
                                <div class="w-2 h-10 rounded-full flex-shrink-0
                                    {{ $s->is_active ? 'bg-green-400' : 'bg-gray-200' }}">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-800">{{ $s->nama_sesi }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ substr($s->jam_mulai, 0, 5) }} – {{ substr($s->jam_selesai, 0, 5) }}
                                        · {{ implode(', ', $s->hari_aktif) }}
                                    </p>
                                </div>

                                @if ($s->isNowActive())
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">
                                        🔴 Sekarang
                                    </span>
                                @endif

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    {{-- Toggle aktif --}}
                                    <form method="POST" action="{{ route('admin.schedules.toggle', $s) }}">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs px-3 py-1.5 rounded-lg transition
                                            {{ $s->is_active
                                                ? 'bg-gray-100 hover:bg-gray-200 text-gray-600'
                                                : 'bg-green-100 hover:bg-green-200 text-green-700' }}">
                                            {{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.schedules.edit', $s) }}"
                                       class="text-xs px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}"
                                          onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-xs px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
