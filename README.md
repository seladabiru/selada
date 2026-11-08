<div align="center">

# 📻 SELADA (Sistem Layanan Dedikasi & Antrian Lagu)
### *Modern School Radio Broadcasting & Song Dedication Platform*
**Dibangun khusus untuk Kompetisi Keahlian USK 2026 — Bidang Web Technologies**

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![YouTube API](https://img.shields.io/badge/YouTube_API-IFrame_v3-FF0000?style=for-the-badge&logo=youtube&logoColor=white)](https://developers.google.com/youtube/iframe_api_reference)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Automated Tests](https://img.shields.io/badge/Automated-Tests-blue?style=for-the-badge&logo=githubactions&logoColor=white)](tests)

<p align="center">
  <a href="#-tentang-proyek">Tentang Proyek</a> •
  <a href="#-fitur-utama">Fitur Utama</a> •
  <a href="#-arsitektur-dan-desain">Arsitektur & Desain</a> •
  <a href="#-panduan-instalasi">Panduan Instalasi</a> •
  <a href="#-kredensial-login">Kredensial Login</a> •
  <a href="#-pengujian-otomatis">Pengujian Otomatis</a> •
  <a href="#-kontributor">Kontributor</a>
</p>

---

</div>

## 📖 Tentang Proyek

**SELADA** (*Sistem Layanan Dedikasi dan Antrian Lagu*) adalah platform digitalisasi siaran radio dan pemutaran musik istirahat sekolah yang dikelola oleh Pengurus OSIS. 

Sebelumnya, alur request lagu di sekolah menghadapi kendala konvensional seperti:
1. **Pencatatan Manual**: Kertas memo/pesan WhatsApp tercecer dan tidak transparan.
2. **Ketiadaan Moderasi Konten**: Rentan terhadap pemutaran lagu berlirik eksplisit, kasar, atau titipan salam bermuatan *cyber-bullying*.
3. **Monopoli Antrian**: Tidak adanya sistem kuota membuat segelintir siswa mendominasi antrian.
4. **Kendala Pemutaran DJ**: Sering terjadi jeda hening (*dead air*), iklan video, atau link rusak saat pergantian track manual.

**SELADA** memecahkan masalah tersebut dengan menyajikan ekosistem web responsif dua sisi:
- **Sisi Siswa (Mobile-Optimized PWA Style)**: Mengajukan lagu via tautan YouTube dengan deteksi otomatis, menitipkan salam hangat, memantau status secara *real-time*, dan menyampaikan kritik/saran ke sekolah.
- **Sisi Studio Operator OSIS (Broadcast Control Center)**: DJ Player otomatis berbasis *YouTube IFrame API* tanpa jeda iklan/mati suara, visualizer spektrum audio, pembaca teleprompter salam dedikasi, moderasi lagu ketat dengan filter kata terlarang otomatis, jadwal jam istirahat, serta rekapitulasi laporan resmi ber-kop surat.

---

## ✨ Fitur Utama

```
SELADA Platform
├── 📱 Siswa (Mobile-First Experience)
│   ├── 🔑 Autentikasi NIS (Cepat & Sederhana)
│   ├── ⚡ Request Lagu Instan (oEmbed YouTube Auto-Fetch)
│   ├── 💌 Dedikasi & Titip Pesan Hangat
│   ├── 📻 Banner ON AIR & progress lagu yang sinkron dengan DJ Player
│   ├── 📋 Status Real-Time "Request Saya" (Menunggu, Disetujui, Diputar, Ditolak)
│   ├── 🏆 Top Hits SMKN 20 berdasarkan jumlah pemutaran
│   └── 💬 Kotak Aspirasi & Kritik/Saran Siswa
│
└── 📻 Operator OSIS (Live Broadcast Studio)
    ├── 🎛️ DJ Web Player (YouTube IFrame API Auto-Next Engine)
    ├── 📊 Realtime 10-Bar Audio Spectrum Visualizer
    ├── ⏱️ Interactive Scrubber Seeking & Master Gain Gain Control
    ├── 📜 Teleprompter Live Dedication Card (NIS, Nama, Kelas, Pesan)
    ├── 🛡️ Moderasi Lagu dengan Filter Blacklist Kata Otomatis
    ├── ⏰ Penjadwalan Siaran Sesi Istirahat Sekolah
    ├── 👥 Bulk Import Data Siswa (CSV & Raw Text Parser)
    └── 📑 Rekapitulasi & Export Laporan Siaran Resmi OSIS
```

### 📱 1. Fitur Siswa (User Experience)
- **Autentikasi Berbasis NIS**: Siswa masuk menggunakan Nomor Induk Siswa (NIS) dan password tanpa perlu mengingat email.
- **Smart Link Resolver**: Cukup tempel tautan YouTube/YouTube Music/Shorts, sistem langsung mengambil judul, *thumbnail* HD, durasi, dan nama *channel* via YouTube oEmbed API.
- **Daily Quota Management**: Dilengkapi pembatas kuota harian dinamis (misal: 1 lagu/hari) untuk mencegah spamming dan memastikan pemerataan kesempatan.
- **Antrian & Now Playing Terpisah**: Lagu yang sedang diputar tampil pada banner tersendiri, tidak diduplikasi di daftar lagu berikutnya. Daftar antrean diperbarui otomatis tiap 10 detik dan dapat disegarkan manual.
- **Status Siaran Sinkron**: Banner user menampilkan ON AIR hanya saat YouTube Player DJ melaporkan lagu sedang diputar. Status pause atau heartbeat yang kedaluwarsa akan menampilkan OFF AIR; progress banner mengikuti posisi player.
- **Top Hits SMKN 20**: Peringkat dikelompokkan berdasarkan ID video dan jumlah record lagu berstatus `diputar` atau `selesai`; lagu yang sedang diputar ikut dihitung. Halaman tidak menyediakan tombol cuplikan.
- **Direct Section & URL Hash Sync**: Navigasi antrian (`#antrian`), riwayat pengajuan (`#my_songs`), chart populer (`#populer`), dan arsip siaran (`#riwayat`) tersinkronisasi mulus tanpa memuat ulang halaman (*zero-reload*).
- **Transparency Tracking**: Riwayat pengajuan menampilkan label status transparan (*Disetujui*, *Diputar*, atau *Ditolak* lengkap dengan catatan alasan dari operator OSIS).
- **Kotak Aspirasi & Feedback Siswa**: Mengirim kritik, saran sarana prasarana, fasilitas siaran, maupun aspirasi kegiatan sekolah langsung ke database pengurus OSIS.

### 🎛️ 2. Fitur Studio Operator OSIS (Admin Control Suite)
- **Gapless Broadcast Player**: Terintegrasi penuh dengan *YouTube IFrame API* yang otomatis memutar lagu berikutnya di antrian ketika track selesai, dilengkapi tombol *Play/Pause*, *Skip*, dan pembaruan status database instan.
- **Status & Progress Player**: Player mengirim heartbeat playback dan progress ke server. Status siaran diperbarui ke seluruh halaman; heartbeat dianggap kedaluwarsa setelah 30 detik tanpa pembaruan.
- **Spectrum Waveform Visualizer**: Animasi equalizer spektrum audio dinamis yang merefleksikan getaran audio saat siaran mengudara.
- **Full Scrubber & Volume Control**: Slider progres waktu interaktif dengan durasi berjalan (*current time, remaining time, total time*) dan pengaturan gain volume master + toggle mute instan.
- **Live Dedication Teleprompter**: Menampilkan kartu titipan salam siswa yang sedang mengudara mencakup Avatar, Nama Lengkap, Kelas, NIS, dan Pesan Dedikasi sehingga DJ OSIS dapat membacakan salam di mikrofon sekolah secara akurat.
- **Automated Content Moderation**:
  - Filter otomatis kata-kata terlarang (*profanity / cyber-bullying blacklist*).
  - Pembatasan durasi maksimum (misal: maks 10 menit).
  - Validasi duplikasi lagu di hari yang sama.
- **Manajemen Jadwal Siaran**: Pengaturan hari siaran (Senin s/d Jumat) dan sesi istirahat aktif (Istirahat Pagi, Siang, Sore) dengan status otomatis.
- **Bulk Import Akun Siswa**: Operator dapat mengunggah berkas CSV atau menyalin teks tabel NIS siswa secara massal dengan proteksi transaksi database.
- **Laporan & Audit Trail**: Generator rekapitulasi request, lagu selesai, lagu ditolak, durasi siaran, dan siswa aktif per periode tanggal. Daftar laporan menggunakan pagination 15 record per halaman dan menyediakan tampilan cetak dengan kop surat resmi OSIS.

---

## 🎨 Arsitektur & Desain

Aplikasi ini mengadopsi filosofi desain **Vibrant Youth Web Design** (Material Design 3 Tokenized) yang disinkronkan dari artboard Figma & Google Stitch:

| Token Desain | Nilai Hex | Penggunaan Utama |
|---|---|---|
| **Primary Container** | `#a3e635` | Aksen warna hijau melon (Pill button, Action CTA, ON AIR Active) |
| **On Primary Container**| `#416400` | Teks kontras tinggi di atas container hijau |
| **Surface / Background**| `#faf8ff` | Background utama bernuansa sejuk dan bersih |
| **On Surface** | `#131b2e` | Tipografi heading dan teks utama yang tegas |
| **Secondary Accent** | `#8127cf` | Elemen lencana kategori, sorotan chart, dan badge spesial |
| **Danger / Live Ping** | `#ae3115` | Indikator *LIVE ON AIR* dan badge penolakan |

Logo SELADA yang dipakai di navigasi user, sidebar admin, halaman login, dan favicon berasal dari `public/images/selada-radio-logo.png`. Branding chart Top Hits menggunakan nama **SMKN 20**.

### Struktur Direktori Proyek
```text
selada/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/               # PlayerController, AdminSongController, FeedbackController, dll
│   │   ├── Auth/                # AuthenticatedSessionController (NIS Auth & Redirection)
│   │   ├── SongController.php   # Antrian siswa dan pengajuan lagu
│   │   └── ProfileController.php# Profil siswa & formulir aspirasi
│   └── Models/                  # Song, User, Schedule, Setting, Feedback
├── database/
│   ├── migrations/              # Skema tabel database MySQL
│   └── seeders/                 # DatabaseSeeder (Admin, Siswa, Jadwal, Setting, Lagu Awal)
├── public/
│   └── images/figma/            # Aset SVG resmi Figma & Stitch (Queue, Music, Flame, Profile)
├── resources/
│   ├── css/app.css              # Tailwind CSS 4 Stylesheet & Keyframe Animations
│   ├── js/app.js                # Alpine.js & Bootstrap runtime
│   └── views/
│       ├── admin/               # Studio Player, Dashboard, Songs, Feedbacks, Settings
│       ├── layouts/             # navigation.blade.php, app.blade.php, admin.blade.php
│       └── songs/               # index.blade.php, create.blade.php, top_hits.blade.php
├── routes/
│   ├── web.php                  # Rute aplikasi siswa & grup rute admin dengan middleware
│   └── auth.php                 # Rute autentikasi
└── tests/                       # Unit & Feature Test Suites
```

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan project di lingkungan lokal:

### 1. Prasyarat Sistem
- **PHP** >= 8.2 (ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`)
- **Composer** >= 2.5
- **Node.js** >= 18.x & **npm**
- **MySQL Database Server** (XAMPP, Laragon, Docker, atau MySQL Standalone)

### 2. Kloning Repositori
```bash
git clone https://github.com/seladabiru/selada.git
cd selada
```

### 3. Instal Dependensi Backend & Frontend
```bash
# Dependensi PHP via Composer
composer install

# Dependensi Frontend Node.js
npm install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin file konfigurasi contoh:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database Anda:
```env
APP_NAME="SELADA Radio"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=selada
DB_USERNAME=root
DB_PASSWORD=
```

Buat Application Encryption Key:
```bash
php artisan key:generate
```

### 5. Migrasi & Seeding Database
Jalankan migrasi tabel beserta data demo (*dummy users, sample songs, schedules, global settings*):
```bash
php artisan migrate --seed
```

### 6. Kompilasi Aset Frontend
```bash
npm run build
```
*(Untuk mode pengembangan dengan Hot Module Reloading, gunakan `npm run dev`)*

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban: **`http://localhost:8000`**

---

## 🔑 Kredensial Login

Setelah menjalankan `php artisan migrate --seed`, akun demo berikut siap digunakan:

| Peran Akun | NIS / Username | Password | Deskripsi Akses |
|---|---|---|---|
| **Admin / Operator OSIS** | `admin` | `password` | Akses penuh ke Studio DJ Player, Moderasi, dan Pengaturan |
| **Siswa 1** | `14144001` | `password` | Akun Siswa (Balamantri - XII MIPA 1) |
| **Siswa 2** | `14144002` | `password` | Akun Siswa (Siti Rahma - XI IPS 2) |
| **Siswa 3 s/d 10** | `14144003` s/d `14144010` | `password` | Akun Siswa Tambahan untuk pengujian multi-user |

> **Catatan Pengalihan Otomatis:** Sistem mendeteksi peran pengguna saat login. Pengguna `admin` akan langsung dialihkan ke **Studio Dashboard Admin**, sedangkan siswa akan langsung diarahkan ke **Antrian Lagu Siaran**.

---

## 🧪 Pengujian Otomatis (Testing)

SELADA dilengkapi rangkaian uji otomatis (*Feature & Unit Tests*) untuk memastikan keandalan alur bisnis:

```bash
php artisan test
```

### Ruang Lingkup Test Suite:
1. `AuthenticationTest`: Uji login NIS, pembatasan akses siswa ke area admin, dan proteksi CSRF.
2. `SongRequestTest`: Uji parsing URL YouTube, pembatasan kuota harian, pemfilteran blacklist kata kasar, dan capping durasi.
3. `TopHitsTest`: Uji penghitungan jumlah pemutaran dan penghapusan tombol cuplikan.
4. `SongFeedbackTest`: Uji pengiriman aspirasi siswa dan tindak lanjut admin.
5. `AdminModerationAndPlayerTest`: Uji alur approval lagu, status switching, dan respons API Player (`play`, `skip`, `finish`).
6. `ReportAndScheduleTest`: Uji export cetak laporan resmi OSIS dan konfigurasi sesi siaran.

Jalankan perintah di atas untuk memperoleh hasil suite terbaru; dokumentasi ini tidak mengasumsikan semua tes selalu lulus.

---

## 🛡️ Keamanan & Integritas Data

- **Proteksi Anti-Spam & Quota Guard**: Setiap siswa dibatasi kuota request harian yang dihitung per tanggal kalender lokal.
- **Blacklist Filter Sanitization**: Regex scanner memverifikasi judul lagu, channel YouTube, dan isi pesan titipan salam terhadap daftar kata terlarang.
- **SQL Injection & XSS Protection**: Menggunakan *Eloquent ORM Parameter Binding*, token *CSRF*, dan pembersihan Blade output `{{ ... }}`.
- **Role-Based Middleware Boundary**: Rute admin diproteksi ketat menggunakan middleware `admin` (`App\Http\Middleware\AdminMiddleware`). Percobaan bypass langsung di-*redirect* dengan aman.

---

## 👨‍💻 Kontributor

- **Aliffansyah** — *Lead Developer & UI/UX Engineer*
- **SELADA Team** — *OSIS Radio Development Initiative*
- Dikembangkan untuk **USK 2026 (Uji Sertifikasi Keahlian)**

---

<div align="center">
  <sub>Dilisensikan di bawah <a href="LICENSE">MIT License</a>. Dibuat dengan dedikasi untuk komunitas sekolah.</sub>
</div>
