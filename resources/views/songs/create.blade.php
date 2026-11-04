<x-app-layout>
    {{--
        Form Request Lagu & Titip Salam — SELADA Radio
        Screen ID: 2280da35941f4e75a24d52f92f6b6560
        Project: Vibrant Youth Web Design (14090167906384824964)
    --}}

    <div class="py-5 sm:py-8 pb-32">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- 1. STICKER RIBBON TOP BANNER --}}
            <div class="flex items-center justify-between gap-2 overflow-x-auto py-1 hide-scrollbar">
                <div class="bg-primary-container text-on-primary-container font-display font-bold text-xs px-3.5 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-[2px_2px_0px_#131b2e] border-2 border-on-surface flex-shrink-0 -rotate-2">
                    <span class="material-symbols-outlined text-[16px]">bolt</span>
                    <span>DROP YOUR TRACK</span>
                </div>
                <div class="bg-secondary-container text-on-secondary-container font-display font-bold text-xs px-3.5 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-[2px_2px_0px_#131b2e] border-2 border-on-surface flex-shrink-0 rotate-1">
                    <span class="material-symbols-outlined text-[16px] ms-filled">favorite</span>
                    <span>ANTI-GALAU</span>
                </div>
                <div class="bg-tertiary-container text-on-tertiary-container font-display font-bold text-xs px-3.5 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-[2px_2px_0px_#131b2e] border-2 border-on-surface flex-shrink-0 -rotate-1">
                    <span class="material-symbols-outlined text-[16px]">verified_user</span>
                    <span>NO SPAM ACTIVE</span>
                </div>
            </div>

            {{-- Error Flash Notification --}}
            @if (session('error'))
                <div class="p-4 bg-[#ffdad6] border-2 border-on-surface text-[#93000a] rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-2 shadow-[3px_3px_0px_#131b2e]">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- 2. STATUS KUOTA HARIAN CARD --}}
            <div class="bg-white p-4 sm:p-5 rounded-2xl border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col gap-3 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-[#b2f746]/20 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-display font-bold text-xl border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex-shrink-0">
                            🎟️
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-display font-bold text-base sm:text-lg text-on-surface leading-tight">Status Kuota Harian</h3>
                            <span class="font-sans font-bold text-xs sm:text-sm text-primary">
                                @if (isset($sisaKuota) && $sisaKuota > 0)
                                    Tersisa {{ $sisaKuota }} Request Lagu Hari Ini!
                                @elseif (isset($sisaKuota) && $sisaKuota == 0)
                                    Kuota request kamu sudah habis hari ini!
                                @else
                                    Tersisa 1 Request Lagu Hari Ini!
                                @endif
                            </span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed font-display font-bold text-[11px] sm:text-xs border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex items-center gap-1.5 flex-shrink-0">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        {{ $requestHariIni ?? 1 }}/{{ $batasPerHari ?? 2 }} DIGUNAKAN
                    </span>
                </div>

                <div class="bg-surface-container p-2.5 px-3 rounded-xl flex items-center gap-2 text-on-surface-variant border border-on-surface/10">
                    <span class="material-symbols-outlined text-[18px] text-tertiary flex-shrink-0">info</span>
                    <p class="font-sans text-xs text-on-surface-variant leading-tight">
                        Maks. durasi {{ intdiv($maksDurasi ?? 600, 60) }} menit • Dilarang lirik kasar/SARA • Cek auto blacklist aktif
                    </p>
                </div>
            </div>

            {{-- MAIN REQUEST FORM --}}
            <form method="POST" action="{{ route('songs.store') }}" id="form-request" class="space-y-4">
                @csrf

                {{-- 3. YOUTUBE URL SECTION CARD --}}
                <div class="bg-white p-4 sm:p-5 rounded-2xl border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <label class="font-display font-bold text-sm sm:text-base text-on-surface flex items-center gap-1.5" for="youtube_url">
                            <span>🔗 Link Lagu YouTube / Shorts / YT Music</span>
                        </label>
                        <span class="font-display text-xs text-primary bg-primary-container px-2.5 py-0.5 rounded-full font-bold border border-on-surface/10">
                            Auto Detect
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[22px] pointer-events-none">play_circle</span>
                            <input class="w-full pl-11 pr-24 py-3 bg-surface-container rounded-xl font-sans text-sm text-on-surface placeholder:text-outline border-2 border-transparent focus:border-on-surface focus:bg-white transition-all outline-none"
                                   id="youtube_url"
                                   name="youtube_url"
                                   type="text"
                                   placeholder="Paste link video YouTube disini..."
                                   value="{{ old('youtube_url') }}"
                                   autocomplete="off"
                                   required />

                            <div class="absolute right-2 flex items-center gap-1.5">
                                <button class="w-7 h-7 flex items-center justify-center rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer"
                                        id="btn-clear"
                                        title="Hapus tautan"
                                        type="button">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                                <button class="px-2.5 py-1.5 rounded-lg bg-secondary text-white font-display text-xs font-bold flex items-center gap-1 shadow-sm active:scale-95 transition-transform cursor-pointer"
                                        id="btn-paste"
                                        type="button">
                                    <span class="material-symbols-outlined text-[14px]">content_paste</span>
                                    <span>Paste</span>
                                </button>
                            </div>
                        </div>

                        <p class="font-sans text-xs text-outline px-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">lightbulb</span>
                            Mendukung format youtube.com, youtu.be, dan music.youtube.com
                        </p>
                        <x-input-error :messages="$errors->get('youtube_url')" class="mt-1" />
                    </div>

                    {{-- Live oEmbed Preview Card --}}
                    <div class="bg-surface-container-low rounded-xl p-3 border border-outline-variant/60 flex flex-col gap-2 mt-1 hidden" id="preview-box">
                        <div class="flex items-center justify-between pb-1">
                            <span class="font-display text-xs uppercase text-on-surface-variant flex items-center gap-1.5 tracking-wider font-bold">
                                <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span> Live Preview Terdeteksi
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-primary-container text-on-primary-container font-display text-xs font-bold flex items-center gap-1 border border-on-surface/10">
                                <span class="material-symbols-outlined text-[14px] ms-filled">check_circle</span> Siap Siar
                            </span>
                        </div>
                        <div class="flex gap-3 items-center bg-white p-2.5 rounded-xl border border-on-surface/10 shadow-xs">
                            <div class="relative w-24 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface-dim border border-on-surface/10">
                                <img id="preview-thumb" class="w-full h-full object-cover" src="{{ asset('images/stitch/form_request_asset_2.png') }}" alt="Preview cover">
                                <span id="preview-dur" class="absolute bottom-1 right-1 bg-inverse-surface/90 text-inverse-on-surface font-mono text-[10px] px-1 rounded">
                                    05:16
                                </span>
                            </div>
                            <div class="flex flex-col min-w-0 flex-grow pr-1">
                                <h4 id="preview-title" class="font-display font-bold text-sm text-on-surface truncate leading-tight">
                                    Nadin Amizah - Bertaut
                                </h4>
                                <div class="flex items-center gap-1 text-on-surface-variant mt-0.5">
                                    <span class="material-symbols-outlined text-[14px] text-secondary">mic</span>
                                    <span id="preview-channel" class="font-sans text-xs truncate">Nadin Amizah</span>
                                </div>
                                <div class="flex items-center gap-1 mt-1 text-primary font-display text-xs font-bold">
                                    <span class="material-symbols-outlined text-[14px] ms-filled">verified</span>
                                    <span class="truncate">Embed Allowed • Durasi Aman</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. TITIP SALAM & PESAN DEDIKASI CARD --}}
                <div class="bg-white p-4 sm:p-5 rounded-2xl border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <label class="font-display font-bold text-sm sm:text-base text-on-surface flex items-center gap-1.5" for="pesan">
                            <span>💌 Titip Salam & Dedikasi</span>
                        </label>
                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-display text-xs font-bold border border-on-surface/10">
                            DJ Read On Air
                        </span>
                    </div>

                    <div class="relative">
                        <textarea class="w-full p-3.5 bg-surface-container rounded-xl font-sans text-sm text-on-surface placeholder:text-outline border-2 border-transparent focus:border-on-surface focus:bg-white transition-all resize-none leading-relaxed outline-none"
                                  id="pesan"
                                  name="pesan"
                                  maxlength="255"
                                  placeholder="Tulis pesan manis, semangat ujian, atau salam buat gebetan / guru..."
                                  rows="4">{{ old('pesan') }}</textarea>

                        <div class="flex items-center justify-between mt-1.5 px-1">
                            <span class="font-sans text-xs text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-primary">record_voice_over</span>
                                Akan dibacakan saat jam istirahat!
                            </span>
                            <span class="font-sans text-xs text-on-surface font-bold bg-surface-container px-2.5 py-0.5 rounded-full border border-on-surface/5" id="char-counter">
                                0 / 255 Karakter
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('pesan')" class="mt-1" />
                    </div>

                    {{-- Quick Tags for Dedication --}}
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #SemangatUjian
                        </button>
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #SalamBuatGuru
                        </button>
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #UntukGebetan
                        </button>
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #XI-RPL-2
                        </button>
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #TetepSlay
                        </button>
                        <button class="tag-chip px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-display text-xs font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all border border-on-surface/5 cursor-pointer" type="button">
                            #TerimaKasihOSIS
                        </button>
                    </div>
                </div>

                {{-- 5. VERIFIKASI SISTEM CEPAT CHECKLIST --}}
                <div class="bg-surface-container p-4 rounded-2xl flex flex-col gap-2 border-2 border-on-surface/10 shadow-sm">
                    <div class="flex items-center justify-between pb-1">
                        <span class="font-display font-bold text-sm text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[20px] text-primary">policy</span>
                            Verifikasi Sistem Cepat
                        </span>
                        <span class="font-display text-xs text-primary font-bold bg-primary-container px-2.5 py-0.5 rounded-full border border-on-surface/10">
                            3/3 Lolos
                        </span>
                    </div>
                    <div class="grid grid-cols-1 gap-2 pt-0.5">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-white text-on-surface font-sans text-xs shadow-xs border border-on-surface/5">
                            <span class="material-symbols-outlined text-primary text-[18px] ms-filled">check_circle</span>
                            <span>Bebas kata terlarang & sensor SARA otomatis</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-white text-on-surface font-sans text-xs shadow-xs border border-on-surface/5">
                            <span class="material-symbols-outlined text-primary text-[18px] ms-filled">check_circle</span>
                            <span>Belum ada di antrian siar hari ini (No duplicate)</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-white text-on-surface font-sans text-xs shadow-xs border border-on-surface/5">
                            <span class="material-symbols-outlined text-primary text-[18px] ms-filled">check_circle</span>
                            <span>Sesuai slot jam istirahat sekolah</span>
                        </div>
                    </div>
                </div>

                {{-- 6. ACTION BUTTONS --}}
                <div class="flex flex-col gap-3 pt-2">
                    <button class="w-full py-4 px-6 bg-primary-container hover:bg-primary-fixed active:translate-y-0.5 text-on-primary-container font-display font-extrabold text-sm sm:text-base rounded-2xl border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex items-center justify-center gap-2 cursor-pointer transition-all"
                            id="submit-request-btn"
                            type="submit">
                        <span class="material-symbols-outlined text-[24px]">rocket_launch</span>
                        <span>KIRIM REQUEST LAGU SEKARANG!</span>
                    </button>

                    <a href="{{ route('songs.index') }}"
                       class="w-full py-3.5 px-6 bg-surface-container-high hover:bg-surface-container-highest active:translate-y-0.5 text-on-surface font-display font-bold text-xs sm:text-sm rounded-2xl border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] flex items-center justify-center gap-1.5 transition-all text-center">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        <span>Batal & Kembali ke Antrian</span>
                    </a>
                </div>
            </form>

        </div>
    </div>

    {{-- INTERACTIVE JAVASCRIPT --}}
    <script>
    (function () {
        const input       = document.getElementById('youtube_url');
        const clearBtn    = document.getElementById('btn-clear');
        const pasteBtn    = document.getElementById('btn-paste');
        const box         = document.getElementById('preview-box');
        const thumb       = document.getElementById('preview-thumb');
        const title       = document.getElementById('preview-title');
        const channel     = document.getElementById('preview-channel');
        const durBadge    = document.getElementById('preview-dur');
        const textarea    = document.getElementById('pesan');
        const charCounter = document.getElementById('char-counter');
        const submitBtn   = document.getElementById('submit-request-btn');
        const form        = document.getElementById('form-request');

        // Update real-time char counter
        if (textarea && charCounter) {
            const updateCount = () => {
                charCounter.textContent = `${textarea.value.length} / 255 Karakter`;
            };
            textarea.addEventListener('input', updateCount);
            updateCount();
        }

        // Clear YouTube input
        if (clearBtn && input) {
            clearBtn.addEventListener('click', () => {
                input.value = '';
                input.focus();
                if (box) box.classList.add('hidden');
            });
        }

        // Paste from clipboard
        if (pasteBtn && input) {
            pasteBtn.addEventListener('click', async () => {
                try {
                    if (navigator.clipboard && navigator.clipboard.readText) {
                        const text = await navigator.clipboard.readText();
                        if (text) {
                            input.value = text;
                            input.dispatchEvent(new Event('input'));
                        }
                    }
                } catch (e) {
                    input.focus();
                }
            });
        }

        // Quick Tag Chips Click
        const tagChips = document.querySelectorAll('.tag-chip');
        tagChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const tagText = ' ' + chip.textContent.trim();
                if (textarea && textarea.value.length + tagText.length <= 255) {
                    textarea.value += tagText;
                    textarea.dispatchEvent(new Event('input'));
                }
            });
        });

        // YouTube URL Parser & oEmbed Live Preview
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

        if (input) {
            input.addEventListener('input', function () {
                clearTimeout(timer);
                const val = this.value.trim();
                if (!val) {
                    if (box) box.classList.add('hidden');
                    return;
                }

                timer = setTimeout(function () {
                    const id = extractId(val);
                    if (id) {
                        thumb.src = `https://img.youtube.com/vi/${id}/mqdefault.jpg`;
                        title.textContent = 'Mencari informasi lagu...';
                        channel.textContent = '';
                        durBadge.textContent = 'YouTube';
                        box.classList.remove('hidden');

                        fetch(`https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=${id}&format=json`)
                            .then(r => r.ok ? r.json() : null)
                            .then(data => {
                                if (data) {
                                    title.textContent = data.title;
                                    channel.textContent = data.author_name;
                                } else {
                                    title.textContent = 'Video YouTube Terdeteksi';
                                    channel.textContent = 'ID: ' + id;
                                }
                            })
                            .catch(() => {
                                title.textContent = 'Video YouTube Terdeteksi';
                                channel.textContent = 'ID: ' + id;
                            });
                    } else {
                        if (box) box.classList.add('hidden');
                    }
                }, 400);
            });

            if (input.value) {
                input.dispatchEvent(new Event('input'));
            }
        }

        // Submit button loading state
        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.innerHTML = '<span class="material-symbols-outlined text-[24px] animate-spin">refresh</span><span>MEMPROSES KE DJ BOOTH...</span>';
                submitBtn.classList.add('opacity-80', 'pointer-events-none');
            });
        }
    })();
    </script>
</x-app-layout>
