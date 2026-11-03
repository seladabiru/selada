<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✏ Edit Pengguna: {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="nis" value="NIS (Nomor Induk Siswa)" />
                        <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" value="{{ old('nis', $user->nis) }}" required />
                        <x-input-error :messages="$errors->get('nis')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="kelas" value="Kelas" />
                        <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full" value="{{ old('kelas', $user->kelas) }}" />
                        <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Peran / Hak Akses" />
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Operator OSIS)</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password Baru (Kosongkan jika tidak ingin mengubah)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        <p class="text-[11px] text-gray-400 mt-1">Isi hanya jika ingin mereset password siswa.</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Batal</a>
                        <x-primary-button>Perbarui Akun</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

