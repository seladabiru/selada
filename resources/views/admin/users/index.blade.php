<x-admin-layout>
{{--
    Manajemen Pengguna & Bulk Import — SELADA Broadcast Booth
    Design source: Stitch (00ebfe6ec9df49819dd3508aa2355154)
    Project: Vibrant Youth Web Design (14090167906384824964)
--}}

<div class="flex h-screen stitch-bg-surface overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin-sidebar active="users" />

    {{-- ══════════════════════════════════════════════
         MAIN VIEWPORT
    ══════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- TOP HEADER BAR --}}
        <header class="h-20 border-b-2 stitch-border-on-surface stitch-bg-surface px-6 flex items-center justify-between gap-4 z-40 shrink-0">
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full stitch-bg-secondary-fixed stitch-text-on-secondary-fixed border-2 stitch-border-on-surface shadow-stitch-sm">
                    <span class="material-symbols-outlined ms-sm text-secondary">alarm</span>
                    <span class="ts-label-md font-bold">Istirahat 1: Sisa 18 Menit</span>
                </div>
                <div class="hidden xl:flex items-center gap-2 px-3 py-1 stitch-bg-surface-container border border-on-surface rounded">
                    <span class="material-symbols-outlined ms-sm text-primary">mic</span>
                    <span class="ts-label-sm uppercase font-bold stitch-text-on-surface">MIC 01 ACTIVE</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Operator profile chip --}}
                <div class="flex items-center gap-3 px-3 py-1.5 rounded-lg stitch-bg-surface-high border-2 stitch-border-on-surface shadow-stitch-sm">
                    <div class="flex flex-col text-right">
                        <span class="ts-label-md stitch-text-on-surface uppercase font-bold">{{ Auth::user()->name ?? 'Bima Pratama' }}</span>
                        <span class="ts-label-sm stitch-text-secondary font-bold">DJ OSIS AUDIO</span>
                    </div>
                    <img src="{{ asset('images/stitch/users_profile.jpg') }}"
                         onerror="this.src='{{ asset('images/stitch/profil_avatar.jpg') }}'"
                         alt="Profile" class="w-8 h-8 rounded-full object-cover border border-on-surface">
                </div>

                {{-- Logout button --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar dari Panel Admin"
                            class="p-2 rounded-lg stitch-bg-surface-container hover:bg-[#ffdad6] stitch-text-on-surface hover:text-[#ba1a1a] border-2 stitch-border-on-surface shadow-stitch-sm transition-all active:scale-95">
                        <span class="material-symbols-outlined ms-sm">power_settings_new</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- SCROLLABLE CONTENT BODY --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 stitch-bg-surface flex flex-col gap-6">

            {{-- Flash Alert Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl border-2 stitch-border-on-surface bg-[#b2f746]/20 stitch-text-on-primary-container shadow-stitch-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined ms-filled text-primary">check_circle</span>
                        <span class="ts-body-md font-bold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface material-symbols-outlined text-sm">close</button>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl border-2 stitch-border-on-surface bg-error-container text-on-error-container shadow-stitch-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined ms-filled text-error">error</span>
                        <span class="ts-body-md font-bold">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface material-symbols-outlined text-sm">close</button>
                </div>
            @endif

            {{-- ── 1. BREADCRUMB & HEADER SECTION ── --}}
            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 lg:p-6 rounded-xl border-2 stitch-border-on-surface shadow-stitch stitch-bg-surface-low">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-1.5 text-on-surface-variant ts-label-sm uppercase tracking-wider">
                        <span>Admin Console</span>
                        <span class="material-symbols-outlined text-xs">chevron_right</span>
                        <span>Pengaturan Pengguna</span>
                        <span class="material-symbols-outlined text-xs">chevron_right</span>
                        <span class="stitch-text-primary font-bold">Manajemen Akun (FR-USR)</span>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="font-display font-extrabold text-2xl lg:text-3xl text-on-surface tracking-tight">Manajemen Pengguna & Data Siswa</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-primary-container text-on-primary-container ts-label-sm shadow-sm border border-on-surface">
                            TA 2024/2025 GANJIL
                        </span>
                    </div>
                    <p class="text-on-surface-variant ts-body-md max-w-3xl mt-1">
                        Kelola basis otentikasi portal radio sekolah, hak siar kru OSIS, kuota request per NIS, dan sinkronisasi data Dapodik siswa secara massal.
                    </p>
                </div>

                {{-- Quick Action Header Buttons --}}
                <div class="flex items-center gap-2 flex-wrap shrink-0">
                    <a href="{{ route('admin.users.template') }}"
                       class="flex items-center gap-1.5 px-4 py-2.5 bg-surface-container-high text-on-surface ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-surface-container-highest transition-all">
                        <span class="material-symbols-outlined ms-sm">download</span>
                        <span>Template CSV</span>
                    </a>
                    <button type="button" onclick="scrollToBulkImport()"
                            class="flex items-center gap-1.5 px-4 py-2.5 bg-secondary-fixed text-on-secondary-fixed ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-[#ddb7ff] transition-all">
                        <span class="material-symbols-outlined ms-sm">upload_file</span>
                        <span>📥 Bulk Import Siswa</span>
                    </button>
                    <button type="button" onclick="openNewUserModal()"
                            class="flex items-center gap-1.5 px-4 py-2.5 bg-primary-container text-on-primary-container ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-primary-fixed transition-all font-bold">
                        <span class="material-symbols-outlined ms-sm">person_add</span>
                        <span>+ Tambah Akun Baru</span>
                    </button>
                </div>
            </section>

            {{-- ── 2. KPI METRICS STRIP ── --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Akun --}}
                <div class="bg-surface-container-lowest p-5 rounded-xl border-2 stitch-border-on-surface shadow-stitch-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="ts-label-sm uppercase text-on-surface-variant tracking-wider">Total Pengguna</span>
                        <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center border border-on-surface">
                            <span class="material-symbols-outlined text-lg text-on-surface">group</span>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3">
                        <span class="font-display font-extrabold text-3xl text-on-surface">{{ $totalPengguna ?? $users->total() }}</span>
                        <div class="flex items-center gap-1 text-primary ts-label-sm font-bold mt-1">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            <span>+24 Siswa Baru (Bulan ini)</span>
                        </div>
                    </div>
                </div>

                {{-- Siswa Aktif --}}
                <div class="bg-surface-container-lowest p-5 rounded-xl border-2 stitch-border-on-surface shadow-stitch-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="ts-label-sm uppercase text-on-surface-variant tracking-wider">Siswa Aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-primary-fixed text-on-primary-fixed flex items-center justify-center border border-on-surface">
                            <span class="material-symbols-outlined text-lg">school</span>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3">
                        <span class="font-display font-extrabold text-3xl text-on-surface">{{ $siswaAktif ?? 0 }}</span>
                        <div class="flex items-center gap-1 text-on-surface-variant ts-label-sm mt-1">
                            <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                            <span>Bisa Request Lagu (97%)</span>
                        </div>
                    </div>
                </div>

                {{-- Operator OSIS --}}
                <div class="bg-surface-container-lowest p-5 rounded-xl border-2 stitch-border-on-surface shadow-stitch-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="ts-label-sm uppercase text-on-surface-variant tracking-wider">Operator OSIS</span>
                        <div class="w-8 h-8 rounded-lg bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center border border-on-surface">
                            <span class="material-symbols-outlined text-lg">graphic_eq</span>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3">
                        <span class="font-display font-extrabold text-3xl text-on-surface">{{ $operatorOsis ?? 0 }}</span>
                        <div class="flex items-center gap-1 text-secondary ts-label-sm font-bold mt-1">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            <span>Hak Akses Studio Audio</span>
                        </div>
                    </div>
                </div>

                {{-- Guru Pembina --}}
                <div class="bg-surface-container-lowest p-5 rounded-xl border-2 stitch-border-on-surface shadow-stitch-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <span class="ts-label-sm uppercase text-on-surface-variant tracking-wider">Guru Pembina</span>
                        <div class="w-8 h-8 rounded-lg bg-tertiary-container text-on-tertiary-container flex items-center justify-center border border-on-surface">
                            <span class="material-symbols-outlined text-lg">verified_user</span>
                        </div>
                    </div>
                    <div class="flex flex-col mt-3">
                        <span class="font-display font-extrabold text-3xl text-on-surface">{{ $guruPembina ?? 4 }}</span>
                        <div class="flex items-center gap-1 text-tertiary ts-label-sm font-bold mt-1">
                            <span class="material-symbols-outlined text-sm">shield</span>
                            <span>Supervisi & Persetujuan</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 3. DEDICATED INLINE SECTION: BULK DATA IMPORTER & PARSER (FR-USR-02 to FR-USR-04) ── --}}
            <section id="bulkImportSection" class="bg-surface-container-low rounded-xl p-5 lg:p-6 border-2 stitch-border-on-surface shadow-stitch flex flex-col gap-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-2 border-b-2 stitch-border-on-surface/20">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-primary-container text-on-primary-container rounded-lg border-2 border-on-surface shadow-stitch-sm">
                            <span class="material-symbols-outlined text-2xl">dynamic_feed</span>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-xl text-on-surface">Pusat Impor Siswa Cepat (Bulk Ingest)</h2>
                            <p class="ts-body-sm text-on-surface-variant">
                                FR-USR-02 s/d FR-USR-04: Eksekusi sinkronisasi buku induk kelas atau tempel data Excel langsung ke queue.
                            </p>
                        </div>
                    </div>

                    {{-- Tab Switcher --}}
                    <div class="inline-flex p-1 bg-surface-container rounded-lg border-2 border-on-surface">
                        <button type="button" class="px-4 py-1.5 rounded-lg ts-label-md bg-surface-container-lowest text-on-surface shadow-stitch-sm border border-on-surface transition-all flex items-center gap-1.5 font-bold" id="tabBtnFile" onclick="switchImportTab('file')">
                            <span class="material-symbols-outlined text-base">upload_file</span>
                            <span>Mode 1: CSV / XLSX File</span>
                        </button>
                        <button type="button" class="px-4 py-1.5 rounded-lg ts-label-md text-on-surface-variant hover:text-on-surface transition-all flex items-center gap-1.5" id="tabBtnPaste" onclick="switchImportTab('paste')">
                            <span class="material-symbols-outlined text-base">content_paste_go</span>
                            <span>Mode 2: Copy-Paste Spreadsheet</span>
                        </button>
                    </div>
                </div>

                {{-- Deduplication Smart Banner (FR-USR-04) --}}
                <div class="p-3 bg-surface-container-highest rounded-lg flex items-center gap-3 text-on-surface border border-on-surface">
                    <span class="material-symbols-outlined text-secondary text-2xl shrink-0">auto_fix_high</span>
                    <div class="flex flex-col md:flex-row md:items-center justify-between w-full ts-body-sm gap-2">
                        <span><strong>Smart Deduplikasi Otomatis (FR-USR-04):</strong> Sistem mendeteksi & mengabaikan NIS duplikat tanpa menghentikan pemrosesan baris lainnya. Password default: <code>Siswa@2024</code>.</span>
                        <span class="ts-label-sm bg-surface-container px-2.5 py-1 rounded text-on-surface-variant uppercase shrink-0 border border-on-surface/40">Auto-Sanitize Active</span>
                    </div>
                </div>

                {{-- Mode 1: Drag & Drop CSV Dropzone --}}
                <div class="flex flex-col gap-4" id="importPanelFile">
                    <form action="{{ route('admin.users.bulkStore') }}" method="POST" enctype="multipart/form-data" id="formBulkUpload">
                        @csrf
                        <input type="hidden" name="role" value="siswa">
                        <input type="hidden" name="default_password" value="Siswa@2024">

                        <div class="bg-surface-container-lowest p-8 rounded-xl text-center flex flex-col items-center justify-center gap-3 border-2 border-dashed stitch-border-on-surface hover:bg-surface-bright transition-all cursor-pointer"
                             onclick="document.getElementById('csvFileInput').click()">
                            <div class="w-14 h-14 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center border-2 border-on-surface shadow-stitch-sm">
                                <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <p class="font-display font-bold text-lg text-on-surface">Tarik & lepas file data siswa (.csv, .xlsx) ke sini</p>
                                <p class="ts-body-sm text-on-surface-variant mt-0.5">Maksimal ukuran file 15 MB. Format kolom wajib: <code>nis, nama_lengkap, kelas, jurusan</code></p>
                            </div>
                            <div class="flex items-center gap-3 pt-2" onclick="event.stopPropagation()">
                                <label class="px-4 py-2 bg-on-surface text-inverse-on-surface ts-label-md rounded-lg cursor-pointer hover:bg-inverse-surface border-2 border-on-surface shadow-stitch-sm transition-all flex items-center gap-1.5">
                                    <span class="material-symbols-outlined ms-sm">folder_open</span>
                                    <span>Pilih File dari Komputer</span>
                                    <input type="file" id="csvFileInput" name="csv_file" accept=".csv, .txt, .xlsx" class="hidden" onchange="handleFileSelected(this)">
                                </label>
                                <a href="{{ route('admin.users.template') }}"
                                   class="px-4 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface shadow-stitch-sm transition-all flex items-center gap-1.5">
                                    <span class="material-symbols-outlined ms-sm">download</span>
                                    <span>Unduh Contoh Format (.csv)</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Mode 2: Paste Raw Tabular / TSV Data --}}
                <div class="hidden flex-col gap-4" id="importPanelPaste">
                    <form action="{{ route('admin.users.bulkStore') }}" method="POST" id="formBulkPaste">
                        @csrf
                        <input type="hidden" name="role" value="siswa">
                        <input type="hidden" name="default_password" value="Siswa@2024">

                        <div class="bg-surface-container-lowest p-5 rounded-xl flex flex-col gap-3 border-2 stitch-border-on-surface">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <label class="ts-label-md text-on-surface flex items-center gap-2">
                                    <span>Tempel Baris dari Excel / Spreadsheet (Tab-Separated):</span>
                                    <span class="text-on-surface-variant ts-body-sm font-normal">(Kolom: NIS | Nama Lengkap | Kelas | Jurusan)</span>
                                </label>
                                <button type="button" class="ts-label-sm text-secondary font-bold hover:underline flex items-center gap-1" onclick="fillExamplePasteData()">
                                    <span class="material-symbols-outlined text-sm">content_paste</span>
                                    <span>Isi Contoh Sampel (4 Siswa)</span>
                                </button>
                            </div>
                            <textarea class="w-full p-3 bg-surface-container-low rounded-lg font-mono ts-body-sm text-on-surface focus:outline-none focus:bg-surface-container-lowest resize-y border-2 border-on-surface"
                                      id="pasteSpreadsheetArea" name="raw_text" placeholder="230101	Aditya Pratama	X	RPL 1
230102	Bella Novita	X	DKV 2
230103	Cantika Sari	X	MIPA 4" rows="4"></textarea>
                            <div class="flex justify-between items-center pt-1 flex-wrap gap-2">
                                <span class="ts-body-sm text-on-surface-variant">Tips: Blok langsung tabel Excel dari kolom NIS sampai Jurusan lalu tekan <code>Ctrl + V</code>.</span>
                                <button type="button" class="px-4 py-2 bg-on-surface text-inverse-on-surface ts-label-md rounded-lg hover:bg-inverse-surface border-2 border-on-surface shadow-stitch-sm transition-all flex items-center gap-1.5 font-bold" onclick="parsePasteContent()">
                                    <span class="material-symbols-outlined text-base">fact_check</span>
                                    <span>Uji & Tampilkan Preview Parsing</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Parsing Preview Table (Collapsible preview FR-USR-03) --}}
                <div class="hidden bg-surface-container-lowest rounded-xl p-5 flex flex-col gap-3 border-2 stitch-border-on-surface shadow-stitch-sm" id="importPreviewBox">
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-on-surface/20">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">table_rows</span>
                            <span class="font-display font-bold text-base text-on-surface" id="previewTitleText">Preview Parsing Batch</span>
                        </div>
                        <div class="flex items-center gap-2" id="previewBadges">
                            <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed ts-label-sm border border-on-surface font-bold" id="badgeValidCount">3 Valid Baru</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-tertiary-container text-on-tertiary-container ts-label-sm border border-on-surface font-bold" id="badgeDuplicateCount">1 Duplikat NIS</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg bg-surface-container-low border border-on-surface">
                        <table class="w-full text-left ts-body-sm">
                            <thead class="bg-surface-container ts-label-sm text-on-surface-variant uppercase border-b border-on-surface">
                                <tr>
                                    <th class="p-3">NIS</th>
                                    <th class="p-3">Nama Lengkap</th>
                                    <th class="p-3">Kelas</th>
                                    <th class="p-3">Jurusan</th>
                                    <th class="p-3">Status Validasi</th>
                                    <th class="p-3 text-right">Aksi Baris</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container text-on-surface" id="previewTableBody">
                                {{-- Dynamically populated via JS --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="flex items-center justify-between pt-2 flex-wrap gap-2">
                        <span class="ts-body-sm text-on-surface-variant" id="previewSummaryText">Total 3 data baru akan digenerate akun dan kredensial login default siswa.</span>
                        <div class="flex gap-2">
                            <button type="button" class="px-4 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface transition-all" onclick="dismissPreviewBox()">
                                Batal
                            </button>
                            <button type="button" class="px-4 py-2 bg-primary-container text-on-primary-container ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-primary-fixed transition-all flex items-center gap-1.5 font-bold" onclick="commitBulkImport()">
                                <span class="material-symbols-outlined text-base">cloud_sync</span>
                                <span id="commitButtonText">Konfirmasi & Simpan Akun Siswa</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 4. FILTER & SEARCH TOOLBAR (FR-USR-01) ── --}}
            <section class="bg-surface-container-lowest rounded-xl p-4 lg:p-5 border-2 stitch-border-on-surface shadow-stitch-sm flex flex-col gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    {{-- Search Input --}}
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                        <input type="text" name="q" id="searchInput" value="{{ $search ?? '' }}"
                               placeholder="Cari NIS, nama siswa, email, atau username..."
                               onkeyup="handleUserFilterClient()"
                               class="w-full pl-10 pr-4 py-2 bg-surface-container-low rounded-lg ts-body-md text-on-surface placeholder:text-on-surface-variant/70 border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                    </div>

                    {{-- Filter Controls Group --}}
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Filter Tingkat / Jurusan --}}
                        <select name="kelas" id="filterClass" onchange="document.getElementById('filterForm').submit()"
                                class="bg-surface-container-low text-on-surface ts-label-md px-3 py-2 rounded-lg border-2 border-on-surface focus:outline-none cursor-pointer">
                            <option value="">Semua Tingkat & Jurusan</option>
                            <option value="X" {{ ($class ?? '') === 'X' ? 'selected' : '' }}>Kelas X (Semua)</option>
                            <option value="XI" {{ ($class ?? '') === 'XI' ? 'selected' : '' }}>Kelas XI (Semua)</option>
                            <option value="XII" {{ ($class ?? '') === 'XII' ? 'selected' : '' }}>Kelas XII (Semua)</option>
                            <option value="RPL" {{ ($class ?? '') === 'RPL' ? 'selected' : '' }}>Jurusan RPL</option>
                            <option value="MIPA" {{ ($class ?? '') === 'MIPA' ? 'selected' : '' }}>Jurusan MIPA</option>
                            <option value="DKV" {{ ($class ?? '') === 'DKV' ? 'selected' : '' }}>Jurusan DKV</option>
                            <option value="TBSM" {{ ($class ?? '') === 'TBSM' ? 'selected' : '' }}>Jurusan TBSM</option>
                        </select>

                        {{-- Filter Role --}}
                        <select name="role" id="filterRole" onchange="document.getElementById('filterForm').submit()"
                                class="bg-surface-container-low text-on-surface ts-label-md px-3 py-2 rounded-lg border-2 border-on-surface focus:outline-none cursor-pointer">
                            <option value="semua" {{ ($role ?? '') === 'semua' ? 'selected' : '' }}>Semua Peran (Role)</option>
                            <option value="siswa" {{ ($role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa Reguler</option>
                            <option value="admin" {{ ($role ?? '') === 'admin' ? 'selected' : '' }}>Operator / DJ OSIS</option>
                            <option value="pembina" {{ ($role ?? '') === 'pembina' ? 'selected' : '' }}>Guru Pembina OSIS</option>
                        </select>

                        {{-- Filter Status (Client Side) --}}
                        <select id="filterStatus" onchange="handleUserFilterClient()"
                                class="bg-surface-container-low text-on-surface ts-label-md px-3 py-2 rounded-lg border-2 border-on-surface focus:outline-none cursor-pointer">
                            <option value="">Semua Status Akun</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Terkunci">Terkunci / Rate Limited</option>
                            <option value="Reset">Perlu Ganti Password</option>
                        </select>

                        {{-- Reset Button --}}
                        <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high border-2 border-on-surface shadow-stitch-sm transition-all" title="Reset Semua Filter">
                            <span class="material-symbols-outlined text-lg">restart_alt</span>
                        </a>

                        {{-- Ekspor Data Button --}}
                        <a href="{{ route('admin.users.export') }}" class="flex items-center gap-1.5 px-3 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface shadow-stitch-sm transition-all">
                            <span class="material-symbols-outlined text-base">file_download</span>
                            <span>Ekspor Data</span>
                        </a>
                    </div>
                </form>

                {{-- Active Multi-Selection Bar (Hidden until rows checked) --}}
                <div class="hidden p-3 bg-secondary-fixed text-on-secondary-fixed rounded-lg items-center justify-between border-2 border-on-surface shadow-stitch-sm" id="bulkActionBar">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">checklist</span>
                        <span class="ts-label-md font-bold" id="selectedCountText">0 Pengguna Dipilih</span>
                    </div>
                    <form method="POST" action="{{ route('admin.users.bulkAction') }}" id="multiSelectionActionForm" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionType" value="">
                        <input type="hidden" name="selected_users" id="bulkSelectedUsersInput" value="">

                        <button type="button" class="px-3 py-1 bg-surface-container-lowest text-on-surface ts-label-sm rounded border border-on-surface shadow-stitch-sm hover:bg-surface-bright transition-all font-bold" onclick="triggerBulkAction('role_siswa')">
                            Ubah Role Siswa
                        </button>
                        <button type="button" class="px-3 py-1 bg-surface-container-lowest text-on-surface ts-label-sm rounded border border-on-surface shadow-stitch-sm hover:bg-surface-bright transition-all font-bold" onclick="triggerBulkAction('reset_password')">
                            Reset Password Massal
                        </button>
                        <button type="button" class="px-3 py-1 bg-tertiary-container text-on-tertiary-container ts-label-sm rounded border border-on-surface shadow-stitch-sm hover:opacity-90 transition-all font-bold" onclick="triggerBulkAction('delete')">
                            Nonaktifkan Akun
                        </button>
                    </form>
                </div>
            </section>

            {{-- ── 5. PRIMARY USER DIRECTORY TABLE (FR-USR-01) ── --}}
            <section class="bg-surface-container-lowest rounded-xl border-2 stitch-border-on-surface shadow-stitch overflow-hidden flex flex-col">
                <div class="overflow-x-auto">
                    <table class="w-full text-left ts-body-md" id="usersTable">
                        <thead class="bg-surface-container ts-label-sm text-on-surface-variant uppercase tracking-wider border-b-2 stitch-border-on-surface">
                            <tr>
                                <th class="p-3 w-12 text-center">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAllUsers(this)"
                                           class="w-4 h-4 rounded accent-primary-container cursor-pointer border-2 border-on-surface">
                                </th>
                                <th class="p-3">Pengguna & NIS/NIP</th>
                                <th class="p-3">Kelas / Satuan</th>
                                <th class="p-3">Peran & Hak Akses</th>
                                <th class="p-3">Aktivitas Siaran</th>
                                <th class="p-3">Status & Akses</th>
                                <th class="p-3 text-right">Opsi Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-surface-container text-on-surface" id="userTableBody">
                            @forelse ($users as $user)
                                @php
                                    $isMe = ($user->id === auth()->id());
                                    $isAdmin = ($user->role === 'admin');
                                    $isPembina = in_array($user->role, ['pembina', 'guru']);
                                    $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                                @endphp
                                <tr class="hover:bg-surface-container-low transition-colors {{ $isMe ? 'bg-primary-container/10' : '' }}"
                                    data-nis="{{ $user->nis }}"
                                    data-name="{{ $user->name }}"
                                    data-kelas="{{ $user->kelas }}"
                                    data-role="{{ $user->role }}"
                                    data-status="{{ $isMe ? 'Aktif' : 'Aktif' }}">
                                    {{-- Checkbox --}}
                                    <td class="p-3 text-center">
                                        @if ($isMe)
                                            <input type="checkbox" disabled class="w-4 h-4 rounded opacity-40 cursor-not-allowed">
                                        @else
                                            <input type="checkbox" name="userRowCheck" value="{{ $user->nis }}" onchange="handleRowSelect()"
                                                   class="w-4 h-4 rounded accent-primary-container cursor-pointer border border-on-surface">
                                        @endif
                                    </td>

                                    {{-- Pengguna & NIS/NIP --}}
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full {{ $isAdmin ? 'bg-primary-container text-on-primary-container' : ($isPembina ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-secondary-fixed text-on-secondary-fixed') }} font-display font-bold text-sm flex items-center justify-center border-2 border-on-surface shadow-stitch-sm shrink-0">
                                                {{ $initials ?: 'US' }}
                                            </div>
                                            <div class="flex flex-col">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-display font-bold text-sm text-on-surface">{{ $user->name }}</span>
                                                    @if ($isMe)
                                                        <span class="bg-primary-container text-on-primary-container ts-label-sm px-1.5 py-0.5 rounded font-bold uppercase border border-on-surface">AKUN ANDA</span>
                                                    @endif
                                                </div>
                                                <span class="ts-body-sm text-on-surface-variant font-mono">NIS: {{ $user->nis }} • {{ $user->email ?? strtolower($user->nis).'@siswa.sch.id' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kelas / Satuan --}}
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface ts-label-sm font-bold border border-on-surface">
                                            {{ $user->kelas ?: ($isAdmin ? 'Studio OSIS' : 'Umum') }}
                                        </span>
                                    </td>

                                    {{-- Peran & Hak Akses --}}
                                    <td class="p-3">
                                        @if ($isAdmin)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed ts-label-sm font-bold border border-on-surface">
                                                <span class="material-symbols-outlined text-sm">graphic_eq</span>
                                                <span>DJ / Operator OSIS</span>
                                            </span>
                                        @elseif ($isPembina)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-tertiary-container text-on-tertiary-container ts-label-sm font-bold border border-on-surface">
                                                <span class="material-symbols-outlined text-sm">verified_user</span>
                                                <span>Guru Pembina OSIS</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface ts-label-sm font-bold border border-on-surface">
                                                <span class="material-symbols-outlined text-sm">school</span>
                                                <span>Siswa Reguler</span>
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aktivitas Siaran --}}
                                    <td class="p-3">
                                        <div class="flex flex-col ts-body-sm">
                                            @if ($isAdmin)
                                                <span class="font-bold text-on-surface">34 Sesi Siar</span>
                                                <span class="text-on-surface-variant ts-label-sm">{{ $user->songs_count ?? 0 }} Lagu Dimainkan</span>
                                            @else
                                                <span class="font-bold text-on-surface">{{ $user->songs_count ?? 0 }} Lagu Dimainkan</span>
                                                <span class="text-on-surface-variant ts-label-sm font-mono">Kuota 2/jam istirahat</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Status & Akses --}}
                                    <td class="p-3">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-2.5 h-2.5 rounded-full {{ $isMe ? 'bg-primary-container animate-ping' : 'bg-primary-container' }}"></span>
                                                <span class="ts-label-md text-primary font-bold">{{ $isMe ? 'Sedang Aktif (On-Air)' : 'Aktif' }}</span>
                                            </div>
                                            <span class="text-on-surface-variant ts-body-sm text-xs">
                                                {{ $isMe ? 'Login: Sesi Aktif Saat Ini' : 'Terdaftar di Database' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Opsi Tindakan --}}
                                    <td class="p-3 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" onclick="openEditUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->nis }}', '{{ addslashes($user->kelas ?? '') }}', '{{ $user->role }}')"
                                                    class="p-2 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high border border-on-surface shadow-stitch-sm transition-all" title="Edit Profil">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </button>
                                            <button type="button" onclick="openResetPasswordModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->nis }}')"
                                                    class="p-2 rounded-lg bg-surface-container text-on-surface hover:bg-surface-container-high border border-on-surface shadow-stitch-sm transition-all" title="Ubah Sandi">
                                                <span class="material-symbols-outlined text-base">key</span>
                                            </button>
                                            @if ($isMe)
                                                <span class="p-2 text-outline-variant cursor-not-allowed" title="Proteksi: Akun Anda tidak dapat dihapus">
                                                    <span class="material-symbols-outlined text-base">lock</span>
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data akun {{ $user->name }} ({{ $user->nis }})?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-lg bg-surface-container hover:bg-error-container text-on-surface hover:text-on-error-container border border-on-surface shadow-stitch-sm transition-all" title="Hapus Akun">
                                                        <span class="material-symbols-outlined text-base text-error">delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-on-surface-variant ts-body-md">
                                        Tidak ada akun pengguna yang cocok dengan kriteria filter saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Table Pagination & Footer --}}
                <div class="p-4 bg-surface-container-low flex flex-col sm:flex-row items-center justify-between gap-3 border-t-2 stitch-border-on-surface">
                    <span class="ts-body-sm text-on-surface-variant font-medium">
                        Menampilkan {{ $users->firstItem() ?? 1 }} sampai {{ $users->lastItem() ?? $users->count() }} dari {{ $users->total() }} total data akun siswa & staf
                    </span>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </section>

            {{-- ── 6. EDUCATIONAL / SECURITY GUIDELINES CALLOUT CARD ── --}}
            <section class="p-5 bg-surface-container-highest rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-2 stitch-border-on-surface shadow-stitch-sm">
                <div class="flex items-start gap-3">
                    <div class="p-2.5 rounded-lg bg-primary-container text-on-primary-container border-2 border-on-surface shadow-stitch-sm shrink-0">
                        <span class="material-symbols-outlined text-2xl">verified_user</span>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="font-display font-bold text-base text-on-surface">Pedoman Keamanan & Privasi Data Siswa SELADA</h4>
                        <p class="ts-body-sm text-on-surface-variant max-w-4xl mt-0.5">
                            Setiap siswa diautentikasi menggunakan Nomor Induk Siswa (NIS). Siswa hanya berhak melakukan request maksimal 2 lagu per jam istirahat untuk menjaga keberagaman pemutaran lagu di area sekolah.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" class="px-4 py-2 bg-surface-container-lowest text-on-surface ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-surface-bright transition-all font-bold" onclick="showToast('Membuka Dokumen SOP Siaran & Privasi Siswa SMAN 1 SELADA Radio')">
                        Lihat Panduan SOP
                    </button>
                </div>
            </section>

        </main>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL: TAMBAH USER BARU (MANUAL SINGLE CREATION)
══════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-on-surface/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" id="modalNewUser">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-xl border-2 stitch-border-on-surface shadow-2xl flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-4 bg-surface-container flex items-center justify-between border-b-2 stitch-border-on-surface">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">person_add</span>
                <span class="font-display font-bold text-base text-on-surface">Tambah Akun Pengguna Baru</span>
            </div>
            <button type="button" class="text-on-surface-variant hover:text-on-surface material-symbols-outlined text-xl" onclick="closeNewUserModal()">close</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" id="formNewUser">
            @csrf
            {{-- Modal Body --}}
            <div class="p-6 flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Nomor Induk Siswa (NIS) / NIP *</label>
                    <input type="text" name="nis" id="newNisInput" required placeholder="Contoh: 24098112"
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Nama Lengkap Siswa / Staf *</label>
                    <input type="text" name="name" id="newNameInput" required placeholder="Contoh: Anisa Maharani"
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="ts-label-sm text-on-surface uppercase font-bold">Tingkat Kelas</label>
                        <select name="class_level" id="newClassLevelInput" class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none cursor-pointer">
                            <option value="X">Kelas X</option>
                            <option value="XI">Kelas XI</option>
                            <option value="XII">Kelas XII</option>
                            <option value="Staff">Guru / Staf TU</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="ts-label-sm text-on-surface uppercase font-bold">Jurusan / Rombel</label>
                        <input type="text" name="class_major" id="newMajorInput" placeholder="MIPA 1 / RPL 2 / DKV"
                               class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Peran / Role di Radio *</label>
                    <select name="role" id="newRoleInput" class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none cursor-pointer">
                        <option value="siswa">Siswa Reguler (Akses Request & Shoutout)</option>
                        <option value="admin">Operator / DJ Radio OSIS (Akses Playback)</option>
                        <option value="pembina">Pembina OSIS / Pengawas</option>
                    </select>
                </div>

                <div class="p-3 bg-surface-container rounded-lg flex items-center gap-2 text-on-surface-variant ts-body-sm border border-on-surface">
                    <span class="material-symbols-outlined text-base shrink-0 text-primary">info</span>
                    <span>Password default awal adalah <code>Siswa@2024</code>. Pengguna wajib menggantinya saat login pertama.</span>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 bg-surface-container-low flex items-center justify-end gap-2 border-t-2 stitch-border-on-surface">
                <button type="button" class="px-4 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface transition-all" onclick="closeNewUserModal()">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-primary-container text-on-primary-container ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-primary-fixed transition-all font-bold">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL: EDIT AKUN PENGGUNA
══════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-on-surface/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" id="modalEditUser">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-xl border-2 stitch-border-on-surface shadow-2xl flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-4 bg-surface-container flex items-center justify-between border-b-2 stitch-border-on-surface">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-xl">edit</span>
                <span class="font-display font-bold text-base text-on-surface">Edit Data Pengguna</span>
            </div>
            <button type="button" class="text-on-surface-variant hover:text-on-surface material-symbols-outlined text-xl" onclick="closeEditUserModal()">close</button>
        </div>

        <form method="POST" id="formEditUser" action="">
            @csrf
            @method('PUT')
            {{-- Modal Body --}}
            <div class="p-6 flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Nomor Induk Siswa (NIS) *</label>
                    <input type="text" name="nis" id="editNisInput" required
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container font-mono">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Nama Lengkap *</label>
                    <input type="text" name="name" id="editNameInput" required
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Kelas / Satuan</label>
                    <input type="text" name="kelas" id="editClassInput" placeholder="Contoh: XII RPL 1"
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Peran (Role) *</label>
                    <select name="role" id="editRoleInput" class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none cursor-pointer">
                        <option value="siswa">Siswa Reguler</option>
                        <option value="admin">Operator / DJ Radio OSIS</option>
                        <option value="pembina">Guru Pembina OSIS</option>
                    </select>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 bg-surface-container-low flex items-center justify-end gap-2 border-t-2 stitch-border-on-surface">
                <button type="button" class="px-4 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface transition-all" onclick="closeEditUserModal()">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-secondary-fixed text-on-secondary-fixed ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-[#ddb7ff] transition-all font-bold">
                    Perbarui Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL: RESET PASSWORD PENGGUNA
══════════════════════════════════════════════ --}}
<div class="fixed inset-0 bg-on-surface/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" id="modalResetPassword">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border-2 stitch-border-on-surface shadow-2xl flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-4 bg-surface-container flex items-center justify-between border-b-2 stitch-border-on-surface">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary text-xl">key</span>
                <span class="font-display font-bold text-base text-on-surface">Reset Kata Sandi</span>
            </div>
            <button type="button" class="text-on-surface-variant hover:text-on-surface material-symbols-outlined text-xl" onclick="closeResetPasswordModal()">close</button>
        </div>

        <form method="POST" id="formResetPassword" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="nis" id="resetPassNis">
            <input type="hidden" name="name" id="resetPassName">
            <input type="hidden" name="kelas" id="resetPassKelas">
            <input type="hidden" name="role" id="resetPassRole">

            <div class="p-6 flex flex-col gap-4">
                <p class="ts-body-md text-on-surface">
                    Setel kata sandi baru untuk pengguna: <strong id="resetPassUserLabel">Bima Pratama</strong>
                </p>

                <div class="flex flex-col gap-1">
                    <label class="ts-label-sm text-on-surface uppercase font-bold">Kata Sandi Baru (Min. 6 Karakter)</label>
                    <input type="password" name="password" id="newPasswordInput" value="Siswa@2024" required
                           class="p-2.5 bg-surface-container-low rounded-lg ts-body-md text-on-surface border-2 border-on-surface focus:outline-none focus:bg-surface-container">
                    <span class="text-xs text-on-surface-variant mt-1">Default yang disarankan: <code>Siswa@2024</code></span>
                </div>
            </div>

            <div class="p-4 bg-surface-container-low flex items-center justify-end gap-2 border-t-2 stitch-border-on-surface">
                <button type="button" class="px-4 py-2 bg-surface-container text-on-surface ts-label-md rounded-lg hover:bg-surface-container-high border-2 border-on-surface transition-all" onclick="closeResetPasswordModal()">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-primary-container text-on-primary-container ts-label-md rounded-lg border-2 border-on-surface shadow-stitch-sm hover:bg-primary-fixed transition-all font-bold">
                    Simpan Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     INTERACTIVE FEEDBACK TOAST CONTAINER
══════════════════════════════════════════════ --}}
<div class="fixed bottom-6 right-6 z-50 hidden px-4 py-3 rounded-xl border-2 stitch-border-on-surface shadow-2xl items-center gap-3 transition-all duration-300" id="toastNotification">
    <span class="material-symbols-outlined text-xl" id="toastIcon">check_circle</span>
    <span class="ts-body-md font-bold" id="toastMessage">Operasi berhasil dijalankan!</span>
</div>

{{-- ══════════════════════════════════════════════
     VANILLA JAVASCRIPT MICRO-INTERACTIONS
══════════════════════════════════════════════ --}}
<script>
    // Tab Switcher between File and Paste modes
    function switchImportTab(mode) {
        const filePanel = document.getElementById('importPanelFile');
        const pastePanel = document.getElementById('importPanelPaste');
        const tabBtnFile = document.getElementById('tabBtnFile');
        const tabBtnPaste = document.getElementById('tabBtnPaste');

        if (mode === 'file') {
            filePanel.classList.remove('hidden');
            filePanel.classList.add('flex');
            pastePanel.classList.add('hidden');
            pastePanel.classList.remove('flex');

            tabBtnFile.classList.add('bg-surface-container-lowest', 'text-on-surface', 'shadow-stitch-sm', 'border', 'border-on-surface', 'font-bold');
            tabBtnFile.classList.remove('text-on-surface-variant');

            tabBtnPaste.classList.remove('bg-surface-container-lowest', 'text-on-surface', 'shadow-stitch-sm', 'border', 'border-on-surface', 'font-bold');
            tabBtnPaste.classList.add('text-on-surface-variant');
        } else {
            pastePanel.classList.remove('hidden');
            pastePanel.classList.add('flex');
            filePanel.classList.add('hidden');
            filePanel.classList.remove('flex');

            tabBtnPaste.classList.add('bg-surface-container-lowest', 'text-on-surface', 'shadow-stitch-sm', 'border', 'border-on-surface', 'font-bold');
            tabBtnPaste.classList.remove('text-on-surface-variant');

            tabBtnFile.classList.remove('bg-surface-container-lowest', 'text-on-surface', 'shadow-stitch-sm', 'border', 'border-on-surface', 'font-bold');
            tabBtnFile.classList.add('text-on-surface-variant');
        }
    }

    // Smooth scroll to bulk import section
    function scrollToBulkImport() {
        const target = document.getElementById('bulkImportSection');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            showToast("Pusat Bulk Import siap digunakan.");
        }
    }

    // Pre-fill sample spreadsheet data
    function fillExamplePasteData() {
        const textarea = document.getElementById('pasteSpreadsheetArea');
        textarea.value = "24019901\tAlifia Rahmadani\tX\tRPL 1\n24019902\tBayu Wicaksono\tX\tDKV 1\n20241088\tSalsaabila Putri\tXII\tMIPA 1\n24019903\tCitra Kirana Ramadhani\tX\tTBSM 2";
        showToast("Contoh data 4 siswa berhasil diisi.");
    }

    // Parsing preview state
    let parsedRowsData = [];

    function parsePasteContent() {
        const area = document.getElementById('pasteSpreadsheetArea');
        const raw = area.value.trim();
        if (!raw) {
            showToast("Harap tempel data spreadsheet terlebih dahulu!", "warning");
            return;
        }

        const lines = raw.split(/\r?\n/);
        const existingNisElements = document.querySelectorAll('#userTableBody tr[data-nis]');
        const existingNisSet = new Set(Array.from(existingNisElements).map(el => el.getAttribute('data-nis')));

        parsedRowsData = [];
        let duplicateCount = 0;

        lines.forEach((line, index) => {
            const clean = line.trim();
            if (!clean) return;
            const delimiter = clean.includes('\t') ? '\t' : (clean.includes(';') ? ';' : ',');
            const parts = clean.split(delimiter).map(p => p.trim().replace(/^["']|["']$/g, ''));
            const nis = parts[0] || '';
            const nama = parts[1] || '';
            const kelas = parts[2] || '';
            const jurusan = parts[3] || '';

            if (!nis || !nama) return;

            const isDuplicate = existingNisSet.has(nis);
            if (isDuplicate) duplicateCount++;

            parsedRowsData.push({
                nis,
                nama,
                kelas,
                jurusan,
                isDuplicate
            });
        });

        renderPreviewTable(duplicateCount);
        showToast("Parsing selesai: " + (parsedRowsData.length - duplicateCount) + " baris valid, " + duplicateCount + " duplikat dilewati.");
    }

    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            showToast("File '" + file.name + "' terpilih.");

            const reader = new FileReader();
            reader.onload = function(e) {
                const text = e.target.result;
                const textarea = document.getElementById('pasteSpreadsheetArea');
                textarea.value = text;
                parsePasteContent();
            };
            reader.readAsText(file);
        }
    }

    function renderPreviewTable(duplicateCount) {
        const previewBox = document.getElementById('importPreviewBox');
        const tbody = document.getElementById('previewTableBody');
        const badgeValid = document.getElementById('badgeValidCount');
        const badgeDup = document.getElementById('badgeDuplicateCount');
        const summaryText = document.getElementById('previewSummaryText');
        const commitBtn = document.getElementById('commitButtonText');

        tbody.innerHTML = '';
        const validCount = parsedRowsData.filter(r => !r.isDuplicate).length;

        badgeValid.innerText = validCount + " Valid Baru";
        badgeDup.innerText = duplicateCount + " Duplikat NIS";
        summaryText.innerText = "Total " + validCount + " data baru akan digenerate akun dan kredensial login default siswa.";
        commitBtn.innerText = "Konfirmasi & Simpan " + validCount + " Akun Siswa";

        parsedRowsData.forEach((row, i) => {
            const tr = document.createElement('tr');
            if (row.isDuplicate) {
                tr.className = "bg-tertiary-container/20";
                tr.innerHTML = `
                    <td class="p-3 font-mono font-bold text-tertiary">${row.nis}</td>
                    <td class="p-3 text-on-surface">${row.nama} <span class="text-xs text-tertiary font-bold">(Duplikat)</span></td>
                    <td class="p-3">${row.kelas}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded bg-surface-container ts-label-sm font-bold">${row.jurusan}</span></td>
                    <td class="p-3"><span class="inline-flex items-center gap-1 text-tertiary font-bold text-xs"><span class="material-symbols-outlined text-sm">warning</span> Dilewati (Sudah Ada)</span></td>
                    <td class="p-3 text-right"><span class="ts-label-sm text-on-surface-variant font-bold">Otomatis Skip</span></td>
                `;
            } else {
                tr.className = "hover:bg-surface-container-highest/50";
                tr.innerHTML = `
                    <td class="p-3 font-mono font-bold">${row.nis}</td>
                    <td class="p-3 font-medium">${row.nama}</td>
                    <td class="p-3">${row.kelas}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded bg-surface-container ts-label-sm font-bold">${row.jurusan}</span></td>
                    <td class="p-3"><span class="inline-flex items-center gap-1 text-primary font-bold text-xs"><span class="material-symbols-outlined text-sm">check_circle</span> Siap Diimpor</span></td>
                    <td class="p-3 text-right"><button type="button" onclick="removePreviewRow(${i})" class="text-error hover:text-error-container material-symbols-outlined text-lg">delete</button></td>
                `;
            }
            tbody.appendChild(tr);
        });

        previewBox.classList.remove('hidden');
        previewBox.scrollIntoView({ behavior: 'smooth' });
    }

    function removePreviewRow(index) {
        parsedRowsData.splice(index, 1);
        const dupCount = parsedRowsData.filter(r => r.isDuplicate).length;
        renderPreviewTable(dupCount);
    }

    function dismissPreviewBox() {
        document.getElementById('importPreviewBox').classList.add('hidden');
    }

    function commitBulkImport() {
        // Re-construct clean raw text and submit the paste form
        const validRows = parsedRowsData.filter(r => !r.isDuplicate);
        if (validRows.length === 0) {
            showToast("Tidak ada data valid untuk disimpan!", "error");
            return;
        }

        const lines = validRows.map(r => `${r.nis}\t${r.nama}\t${r.kelas}\t${r.jurusan}`).join("\n");
        document.getElementById('pasteSpreadsheetArea').value = lines;
        document.getElementById('formBulkPaste').submit();
    }

    // Table Multi-select & Bulk Action Handling
    function toggleSelectAllUsers(master) {
        const checkboxes = document.querySelectorAll('input[name="userRowCheck"]');
        checkboxes.forEach(cb => {
            cb.checked = master.checked;
        });
        handleRowSelect();
    }

    function handleRowSelect() {
        const checked = document.querySelectorAll('input[name="userRowCheck"]:checked');
        const bulkBar = document.getElementById('bulkActionBar');
        const countText = document.getElementById('selectedCountText');
        const bulkInput = document.getElementById('bulkSelectedUsersInput');

        if (checked.length > 0) {
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
            countText.innerText = checked.length + " Pengguna Dipilih";

            const selectedValues = Array.from(checked).map(cb => cb.value);
            bulkInput.value = selectedValues.join(',');
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
            const master = document.getElementById('selectAllCheckbox');
            if (master) master.checked = false;
            bulkInput.value = '';
        }
    }

    function triggerBulkAction(actionType) {
        const checked = document.querySelectorAll('input[name="userRowCheck"]:checked');
        if (checked.length === 0) {
            showToast("Pilih minimal satu pengguna terlebih dahulu!", "warning");
            return;
        }

        let confirmMsg = "";
        if (actionType === 'role_siswa') {
            confirmMsg = `Ubah peran ${checked.length} pengguna terpilih menjadi Siswa Reguler?`;
        } else if (actionType === 'reset_password') {
            confirmMsg = `Reset kata sandi ${checked.length} pengguna terpilih menjadi Siswa@2024?`;
        } else if (actionType === 'delete') {
            confirmMsg = `Apakah Anda yakin ingin menonaktifkan / menghapus ${checked.length} akun pengguna terpilih?`;
        }

        if (confirm(confirmMsg)) {
            document.getElementById('bulkActionType').value = actionType;
            document.getElementById('multiSelectionActionForm').submit();
        }
    }

    // Client-side quick filter
    function handleUserFilterClient() {
        const searchVal = document.getElementById('searchInput').value.toLowerCase();
        const statusVal = document.getElementById('filterStatus').value.toLowerCase();

        const rows = document.querySelectorAll('#userTableBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

            const matchesSearch = !searchVal || text.includes(searchVal);
            const matchesStatus = !statusVal || rowStatus.includes(statusVal);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Modals: Single User Creation
    function openNewUserModal() {
        const modal = document.getElementById('modalNewUser');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeNewUserModal() {
        const modal = document.getElementById('modalNewUser');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Modals: Edit User
    function openEditUserModal(id, name, nis, kelas, role) {
        const modal = document.getElementById('modalEditUser');
        const form = document.getElementById('formEditUser');

        form.action = "{{ url('admin/users') }}/" + id;
        document.getElementById('editNisInput').value = nis;
        document.getElementById('editNameInput').value = name;
        document.getElementById('editClassInput').value = kelas;
        document.getElementById('editRoleInput').value = role;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditUserModal() {
        const modal = document.getElementById('modalEditUser');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Modals: Reset Password
    function openResetPasswordModal(id, name, nis) {
        const modal = document.getElementById('modalResetPassword');
        const form = document.getElementById('formResetPassword');
        const row = document.querySelector(`tr[data-nis="${nis}"]`);

        form.action = "{{ url('admin/users') }}/" + id;
        document.getElementById('resetPassNis').value = nis;
        document.getElementById('resetPassName').value = name;
        document.getElementById('resetPassKelas').value = row ? row.getAttribute('data-kelas') : '';
        document.getElementById('resetPassRole').value = row ? row.getAttribute('data-role') : 'siswa';
        document.getElementById('resetPassUserLabel').innerText = `${name} (${nis})`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('modalResetPassword');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Feedback Toast Notification
    let toastTimer;
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const msgElem = document.getElementById('toastMessage');
        const iconElem = document.getElementById('toastIcon');

        msgElem.innerText = message;
        if (type === 'error') {
            iconElem.innerText = 'error';
            toast.className = 'fixed bottom-6 right-6 z-50 bg-error text-on-error px-4 py-3 rounded-xl border-2 stitch-border-on-surface shadow-2xl flex items-center gap-3';
        } else if (type === 'warning') {
            iconElem.innerText = 'warning';
            toast.className = 'fixed bottom-6 right-6 z-50 bg-tertiary-container text-on-tertiary-container px-4 py-3 rounded-xl border-2 stitch-border-on-surface shadow-2xl flex items-center gap-3';
        } else {
            iconElem.innerText = 'check_circle';
            toast.className = 'fixed bottom-6 right-6 z-50 bg-inverse-surface text-inverse-on-surface px-4 py-3 rounded-xl border-2 stitch-border-on-surface shadow-2xl flex items-center gap-3';
        }

        toast.classList.remove('hidden');
        toast.classList.add('flex');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 4000);
    }
</script>
</x-admin-layout>
