<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ Tambah Pengguna Baru
            </h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="nis" value="NIS (Nomor Induk Siswa / Username)" />
                        <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" value="{{ old('nis') }}" required />
                        <x-input-error :messages="$errors->get('nis')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="kelas" value="Kelas (misal: XII RPL 1)" />
                        <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full" value="{{ old('kelas') }}" />
                        <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Peran / Hak Akses" />
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Operator OSIS)</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password (Minimal 6 karakter)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Batal</a>
                        <x-primary-button>Simpan Akun</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

