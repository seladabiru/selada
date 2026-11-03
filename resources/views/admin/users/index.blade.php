<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    👥 Manajemen Pengguna
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola akun Siswa & Staf Admin OSIS</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.bulkCreate') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition flex items-center gap-1.5">
                    <span>📥</span>
                    <span>Import Massal (Bulk)</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    + Tambah Satuan
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg transition">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter & Search --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-center justify-between">
                    <div class="flex flex-wrap gap-3 items-center flex-1">
                        <div class="w-full sm:w-64">
                            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, NIS, kelas..."
                                class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-36">
                            <select name="role" class="w-full text-xs rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="semua" {{ $role === 'semua' ? 'selected' : '' }}>Semua Peran</option>
                                <option value="siswa" {{ $role === 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                            Cari
                        </button>
                    </div>
                    <span class="text-xs text-gray-400">Total: {{ $users->total() }} akun</span>
                </form>
            </div>

            {{-- Tabel Pengguna --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 w-10">No</th>
                                <th class="px-5 py-3">Nama Lengkap</th>
                                <th class="px-5 py-3">NIS</th>
                                <th class="px-5 py-3">Kelas</th>
                                <th class="px-5 py-3">Peran</th>
                                <th class="px-5 py-3 text-center">Total Request</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $idx => $user)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-5 py-3 text-gray-400 font-mono">
                                        {{ $users->firstItem() + $idx }}
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-gray-800">
                                        {{ $user->name }}
                                        @if ($user->id === auth()->id())
                                            <span class="ms-1 px-1.5 py-0.5 text-[9px] bg-indigo-100 text-indigo-700 rounded font-normal">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 font-mono text-gray-600">
                                        {{ $user->nis }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">
                                        {{ $user->kelas ?: '—' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center font-bold text-gray-700">
                                        {{ $user->songs_count }}
                                    </td>
                                    <td class="px-5 py-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                Edit
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                        Tidak ada pengguna yang cocok dengan kriteria pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

