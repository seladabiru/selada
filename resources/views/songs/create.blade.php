<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-900 leading-tight flex items-center gap-2">
                <span>🎵</span> Request Lagu
            </h2>
            <a href="{{ route('songs.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm text-gray-500 hover:text-indigo-600 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-2xs transition">
                ← Kembali ke Antrian
            </a>
        </div>
    </x-slot>

    <div class="py-5 sm:py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- Alert error --}}
            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs sm:text-sm flex items-center gap-2 shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-xs rounded-2xl p-5 sm:p-7 border border-gray-100">
                <div class="mb-6">
                    <h3 class="font-bold text-base sm:text-lg text-gray-900">Form Pengajuan Lagu</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Masukkan tautan lagu dari YouTube atau YouTube Music. Lagu akan diputar pada jam istirahat sekolah setelah disetujui OSIS.
                    </p>
                </div>

                <form method="POST" action="{{ route('songs.store') }}" id="form-request" class="space-y-5">
                    @csrf

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <x-input-label for="youtube_url" value="Link atau ID YouTube" />
                            <button type="button"
                                    id="btn-paste"
                                    class="text-[11px] text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-1">
                                📋 Tempel Tautan
                            </button>
                        </div>
                        <x-text-input
                            id="youtube_url"
                            name="youtube_url"
                            type="text"
                            class="block w-full text-xs sm:text-sm rounded-xl"
                            placeholder="https://www.youtube.com/watch?v=..."
                            value="{{ old('youtube_url') }}"
                            autofocus
                            autocomplete="off"
                            required
                        />
                        <x-input-error :messages="$errors->get('youtube_url')" class="mt-1.5" />
                        <p class="mt-1 text-[11px] text-gray-400">
                            Mendukung tautan YouTube, YouTube Music, dan Shorts.
                        </p>
                    </div>

                    {{-- Live Preview Thumbnail --}}
                    <div id="preview-box" class="hidden p-3.5 bg-gray-50/90 border border-gray-200 rounded-xl flex gap-3 items-center transition">
                        <img id="preview-thumb" src="" alt="thumbnail" class="w-20 h-14 sm:w-24 sm:h-14 object-cover rounded-lg shadow-2xs flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <p id="preview-title" class="text-xs sm:text-sm font-bold text-gray-900 line-clamp-1"></p>
                            <p id="preview-channel" class="text-[11px] text-gray-500 mt-0.5 truncate"></p>
                            <span class="inline-block text-[10px] text-emerald-600 font-semibold mt-1">✓ Preview Terdeteksi</span>
                        </div>
                    </div>

                    {{-- Pesan / Dedikasi / Salam (Opsional) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <x-input-label for="pesan" value="Pesan Dedikasi / Salam Siswa (Opsional)" />
                            <span id="char-count" class="text-[11px] text-gray-400">0/255</span>
                        </div>
                        <textarea
                            id="pesan"
                            name="pesan"
                            rows="3"
                            maxlength="255"
                            placeholder="Contoh: Titip salam buat teman-teman kelas XII yang lagi ujian, semangat ya! 🎧"
                            class="block w-full rounded-xl border-gray-300 shadow-2xs focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm"
                        >{{ old('pesan') }}</textarea>
                        <p class="mt-1 text-[11px] text-gray-400">
                            Pesan kamu akan dibacakan oleh penyiar OSIS saat lagu diputar.
                        </p>
                        <x-input-error :messages="$errors->get('pesan')" class="mt-1.5" />
                    </div>

                    {{-- Submit Box --}}
                    <div class="pt-2 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                        <p class="text-[11px] text-gray-500 text-center sm:text-left">
                            Batas: <strong>{{ $batasPerHari ?? 1 }} lagu/hari</strong> · Maks. <strong>{{ intdiv($maksDurasi ?? 600, 60) }} menit</strong>
                        </p>
                        <button type="submit"
                                id="btn-submit"
                                class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                            <span>🚀</span> Kirim Request Lagu
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tips Card --}}
            <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-xs text-indigo-900 space-y-1.5">
                <p class="font-bold flex items-center gap-1.5">
                    <span>💡</span> Ketentuan Siaran Radio Sekolah:
                </p>
                <ul class="list-disc list-inside space-y-1 text-gray-600 text-[11px]">
                    <li>Pastikan lagu tidak mengandung kata-kata kasar, sara, atau eksplisit.</li>
                    <li>Satu siswa berhak mengajukan 1 request per hari agar semua teman kebagian giliran.</li>
                    <li>Lagu akan masuk daftar antrian yang bisa di-vote oleh seluruh siswa sekolah.</li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Script Interaktif: Preview, Paste, Char Counter --}}
    <script>
    (function () {
        const input    = document.getElementById('youtube_url');
        const box      = document.getElementById('preview-box');
        const thumb    = document.getElementById('preview-thumb');
        const title    = document.getElementById('preview-title');
        const channel  = document.getElementById('preview-channel');
        const pesan    = document.getElementById('pesan');
        const charCnt  = document.getElementById('char-count');
        const pasteBtn = document.getElementById('btn-paste');

        // Tombol Paste dari clipboard
        if (pasteBtn && navigator.clipboard && navigator.clipboard.readText) {
            pasteBtn.addEventListener('click', async () => {
                try {
                    const text = await navigator.clipboard.readText();
                    if (text) {
                        input.value = text;
                        input.dispatchEvent(new Event('input'));
                    }
                } catch (e) {
                    input.focus();
                }
            });
        } else if (pasteBtn) {
            pasteBtn.style.display = 'none';
        }

        // Counter karakter pesan
        if (pesan && charCnt) {
            pesan.addEventListener('input', () => {
                charCnt.textContent = `${pesan.value.length}/255`;
            });
        }

        let timer;
        function extractId(str) {
            const trimmed = str.trim();
            if (/^[a-zA-Z0-9_\-]{11}$/.test(trimmed)) return trimmed;

            try {
                const url = new URL(trimmed);
                const host = url.hostname.replace(/^www\./, '');

                if (host === 'youtu.be') {
                    return url.pathname.slice(1).split('?')[0];
                }
                if (['youtube.com', 'music.youtube.com', 'm.youtube.com'].includes(host)) {
                    if (url.pathname.startsWith('/watch')) return url.searchParams.get('v');
                    const m = url.pathname.match(/^\/(embed|shorts|v)\/([a-zA-Z0-9_\-]{11})/);
                    if (m) return m[2];
                }
            } catch (_) {}
            return null;
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            const val = this.value.trim();
            if (!val) { box.classList.add('hidden'); return; }

            timer = setTimeout(function () {
                const id = extractId(val);
                if (id) {
                    thumb.src = `https://img.youtube.com/vi/${id}/mqdefault.jpg`;
                    title.textContent   = 'Mencari informasi lagu...';
                    channel.textContent = '';
                    box.classList.remove('hidden');

                    fetch(`https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=${id}&format=json`)
                        .then(r => r.ok ? r.json() : null)
                        .then(data => {
                            if (data) {
                                title.textContent   = data.title;
                                channel.textContent = data.author_name;
                            } else {
                                title.textContent   = 'Video YouTube Terdeteksi';
                                channel.textContent = 'ID: ' + id;
                            }
                        })
                        .catch(() => {
                            title.textContent   = 'Video YouTube Terdeteksi';
                            channel.textContent = 'ID: ' + id;
                        });
                } else {
                    box.classList.add('hidden');
                }
            }, 400);
        });

        // Trigger jika ada nilai awal dari old input
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    })();
    </script>
</x-app-layout>
