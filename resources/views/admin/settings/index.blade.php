<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">⚙ Pengaturan</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-xl p-6">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf

                    {{-- Batas request per hari --}}
                    <div class="mb-5">
                        <x-input-label for="batas_request_per_hari" value="Batas Request per Siswa per Hari" />
                        <div class="mt-1 flex items-center gap-2">
                            <x-text-input
                                id="batas_request_per_hari"
                                name="batas_request_per_hari"
                                type="number" min="1" max="10"
                                class="w-24"
                                value="{{ old('batas_request_per_hari', $settings->get('batas_request_per_hari')?->value ?? 1) }}"
                            />
                            <span class="text-sm text-gray-500">lagu / hari</span>
                        </div>
                        <x-input-error :messages="$errors->get('batas_request_per_hari')" class="mt-1" />
                    </div>

                    {{-- Durasi maksimal --}}
                    <div class="mb-5">
                        <x-input-label for="maks_durasi_detik" value="Durasi Maksimal Lagu" />
                        <div class="mt-1 flex items-center gap-2">
                            <x-text-input
                                id="maks_durasi_detik"
                                name="maks_durasi_detik"
                                type="number" min="60" max="3600" step="60"
                                class="w-28"
                                value="{{ old('maks_durasi_detik', $settings->get('maks_durasi_detik')?->value ?? 600) }}"
                            />
                            <span class="text-sm text-gray-500">
                                detik
                                (= {{ intdiv(old('maks_durasi_detik', $settings->get('maks_durasi_detik')?->value ?? 600), 60) }} menit)
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('maks_durasi_detik')" class="mt-1" />
                    </div>

                    {{-- Blacklist kata --}}
                    <div class="mb-5">
                        <x-input-label for="blacklist_kata" value="Blacklist Kata (pisahkan dengan koma)" />
                        <textarea
                            id="blacklist_kata"
                            name="blacklist_kata"
                            rows="3"
                            placeholder="contoh: judulterlarang, namaartis, dll"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >{{ old('blacklist_kata', $settings->get('blacklist_kata')?->value ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">
                            Lagu yang judulnya mengandung kata ini akan otomatis ditolak saat request.
                        </p>
                        <x-input-error :messages="$errors->get('blacklist_kata')" class="mt-1" />
                    </div>

                    {{-- Moderasi otomatis --}}
                    <div class="mb-6">
                        <div class="flex items-start gap-3">
                            <input type="hidden" name="moderasi_otomatis" value="0">
                            <input type="checkbox"
                                id="moderasi_otomatis"
                                name="moderasi_otomatis"
                                value="1"
                                {{ ($settings->get('moderasi_otomatis')?->value ?? '0') === '1' ? 'checked' : '' }}
                                class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <label for="moderasi_otomatis" class="text-sm font-medium text-gray-700">
                                    Moderasi Otomatis
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Jika diaktifkan, semua request lagu langsung disetujui tanpa perlu moderasi manual.
                                    Tidak disarankan kecuali saat darurat.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Simpan Pengaturan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
