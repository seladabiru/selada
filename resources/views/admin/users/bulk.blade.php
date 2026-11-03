<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📥 Tambah Pengguna Massal (Bulk Create / Import)
                </h2>
                <p class="text-xs text-gray-500 mt-1">Tambah banyak data siswa sekaligus via file CSV atau tempel teks</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.template') }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition flex items-center gap-1.5">
                    <span>📄</span>
                    <span>Download Template CSV</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-300 text-red-700 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="POST" action="{{ route('admin.users.bulkStore') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Pengaturan Awal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100">
                        <div>
                            <x-input-label for="role" value="Peran Akun yang Dibuat" />
                            <select id="role" name="role" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="siswa" selected>Siswa</option>
                                <option value="admin">Admin / Operator OSIS</option>
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Biasanya gunakan peran 'Siswa'.</p>
                        </div>

                        <div>
                            <x-input-label for="default_password" value="Default Password (Jika tidak diisi per baris)" />
                            <x-text-input id="default_password" name="default_password" type="text" class="mt-1 block w-full" value="password" required />
                            <p class="text-[11px] text-gray-400 mt-1">Default password bagi siswa yang tidak menyertakan password khusus.</p>
                        </div>
                    </div>

                    {{-- Opsi 1: Upload File CSV --}}
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2 mb-2">
                            <span>📁 Opsi 1: Unggah File CSV</span>
                            <span class="text-[10px] text-indigo-600 font-normal bg-indigo-50 px-2 py-0.5 rounded-full">Format .csv atau .txt</span>
                        </h4>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-indigo-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-xs text-gray-600 justify-center">
                                    <label for="csv_file" class="relative cursor-pointer bg-white rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Pilih file dari komputer</span>
                                        <input id="csv_file" name="csv_file" type="file" accept=".csv,.txt" class="sr-only">
                                    </label>
                                </div>
                                <p id="file-chosen" class="text-xs text-gray-500">Maksimal 2MB (.csv)</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-4 text-xs font-semibold uppercase tracking-wider text-gray-400">ATAU</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    {{-- Opsi 2: Salin-Tempel Teks Manual --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-bold text-gray-800 text-sm">
                                📋 Opsi 2: Tempel Data Teks (Copy-Paste)
                            </h4>
                            <span class="text-xs text-gray-400">1 baris = 1 siswa</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">
                            Format per baris: <code class="bg-gray-100 text-gray-700 px-1 py-0.5 rounded font-mono">NIS, Nama Lengkap, Kelas, Password(opsional)</code>
                        </p>
                        <textarea
                            id="raw_text"
                            name="raw_text"
                            rows="7"
                            placeholder="Contoh:&#10;14144011, Andi Pratama, XII RPL 1, password123&#10;14144012, Bella Safitri, XII RPL 1&#10;14144013, Candra Wijaya, XII RPL 2"
                            class="block w-full rounded-xl border-gray-300 font-mono text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('raw_text') }}</textarea>
                    </div>

                    {{-- Panduan & Petunjuk --}}
                    <div class="p-4 bg-gray-50 rounded-xl text-xs text-gray-600 space-y-1">
                        <p class="font-semibold text-gray-700">💡 Informasi Penting:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-500">
                            <li>Pemisah antar kolom bisa menggunakan tanda koma (<code class="bg-white px-1">,</code>), titik-koma (<code class="bg-white px-1">;</code>), atau tab Excel.</li>
                            <li>Jika baris pertama berisi kata <em>nis / nama / username</em>, baris tersebut otomatis dilewati sebagai baris header.</li>
                            <li>Jika NIS sudah ada di database, data tersebut otomatis dilewati tanpa menghentikan import data lainnya.</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Batal</a>
                        <x-primary-button>
                            Mulai Import Data Siswa
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        const fileInput = document.getElementById('csv_file');
        const fileLabel = document.getElementById('file-chosen');
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                fileLabel.textContent = 'File terpilih: ' + this.files[0].name;
                fileLabel.className = 'text-xs text-green-600 font-semibold';
            }
        });
    </script>
</x-app-layout>

