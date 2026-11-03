<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 flex items-center gap-2">
            <span>💬</span> {{ __('Kritik & Saran Radio Sekolah') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Punya ide lagu, jadwal siaran baru, atau kritik membangun untuk penyiaran OSIS? Suara kamu sangat berharga!') }}
        </p>
    </header>

    @if (session('feedback_status'))
        <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('feedback_status') }}</span>
        </div>
    @endif

    <form method="post" action="{{ route('profile.feedback.store') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="kategori" :value="__('Kategori Masukan')" />
            <select id="kategori" name="kategori" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="Saran" {{ old('kategori') == 'Saran' ? 'selected' : '' }}>💡 Saran & Ide Acara</option>
                <option value="Kritik" {{ old('kategori') == 'Kritik' ? 'selected' : '' }}>📝 Kritik Membangun</option>
                <option value="Laporan Masalah" {{ old('kategori') == 'Laporan Masalah' ? 'selected' : '' }}>⚠️ Kendala Teknis / Pemutar</option>
                <option value="Permintaan Fitur" {{ old('kategori') == 'Permintaan Fitur' ? 'selected' : '' }}>✨ Usulan Fitur Aplikasi</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
        </div>

        <div>
            <x-input-label for="pesan" :value="__('Pesan Kamu')" />
            <textarea
                id="pesan"
                name="pesan"
                rows="4"
                required
                maxlength="1000"
                placeholder="Tuliskan masukan kamu di sini secara sopan dan jelas..."
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            >{{ old('pesan') }}</textarea>
            <p class="mt-1 text-xs text-gray-400">Maksimal 1000 karakter.</p>
            <x-input-error class="mt-2" :messages="$errors->get('pesan')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Kirim Masukan') }}
            </x-primary-button>
        </div>
    </form>

    @if (isset($feedbacks) && $feedbacks->isNotEmpty())
        <div class="mt-8 pt-6 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Riwayat Masukan Terakhir Kamu:</h3>
            <div class="space-y-3">
                @foreach ($feedbacks as $item)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-600">
                        <div class="flex items-center justify-between font-medium text-gray-800 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700">
                                {{ $item->kategori }}
                            </span>
                            <span class="text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700 mt-1 whitespace-pre-line">{{ $item->pesan }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>

