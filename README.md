# 🎵 Selada — Sistem Layanan Dedikasi dan Antrian Lagu

**Selada** adalah aplikasi web **Radio Request OSIS Sekolah** berbasis Laravel 11. Aplikasi ini memungkinkan siswa untuk mengajukan request lagu YouTube, dan operator OSIS dapat memoderasi serta menyiarkan lagu tersebut saat jam istirahat.

Dibuat untuk kompetisi **USK 2026**.

---

## ✨ Fitur Utama

### 👤 Fitur Siswa (Mobile-Optimized 📱)
- **Login NIS**: Autentikasi menggunakan NIS & password (bukan email).
- **Request Lagu**: Input link YouTube/YouTube Music dengan preview otomatis & salam dedikasi (kuota **1 lagu/hari**).
- **Vote Lagu (Upvote)**: Siswa dapat memberi like/upvote lagu di antrian secara interaktif.
- **Top 5 Lagu Terpopuler**: Menampilkan lagu yang paling sering diputar/diminta di sekolah.
- **Riwayat Khusus Siswa**: Tab "Request Saya" untuk memantau status persetujuan lagu & alasan jika ditolak.
- **Banner ON AIR Live**: Animasi status siaran radio sekolah saat memutar lagu.
- **Kritik & Saran di Profil**: Wadah bagi siswa untuk menyampaikan aspirasi dan masukan ke pengurus OSIS (tanpa opsi hapus akun).
- **Mobile Floating Button (FAB)**: Akses cepat request lagu langsung dari smartphone.

### ⚙️ Fitur Admin / Operator OSIS
- **Dashboard Interaktif**: Statistik antrian, jadwal aktif, dan notifikasi kritik/saran baru.
- **Pemutar Siaran Musik (`/admin/player`)**: YouTube IFrame API dengan auto-next playlist, kontrol play/pause/skip, serta pembaca teks salam dedikasi siswa.
- **Moderasi Lagu**: Setujui, tolak dengan alasan, atur urutan prioritas lagu.
- **Kotak Kritik & Saran (`/admin/feedbacks`)**: Rekap aspirasi siswa dengan filter kategori & status tindak lanjut.
- **Manajemen Jadwal**: Jadwal siaran per hari & sesi jam istirahat sekolah.
- **Laporan & Rekapitulasi**: Filter periode tanggal, export cetak resmi dengan kop surat OSIS.
- **Manajemen Pengguna**: Tambah satuan & **Bulk Import Siswa** (via CSV atau copy-paste data teks).
- **Pengaturan Global**: Konfigurasi batas request, durasi video maksimal, dan filter kata terlarang (blacklist).

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | **Laravel 11** (PHP 8.2+) |
| Frontend | **Tailwind CSS 4**, **Alpine.js** |
| Database | **MySQL** (produksi) / SQLite (testing) |
| Video Player | **YouTube IFrame API**, **YouTube Data API v3** |
| Build Tool | **Vite 7** |
| Auth | NIS-based login (kustom) |

---

## 🚀 Panduan Menjalankan Aplikasi

```bash
# 1. Migrasi & Seeder Database
php artisan migrate --seed

# 2. Build Frontend Assets
npm run build

# 3. Jalankan Web Server
php artisan serve
```

Buka di browser: `http://localhost:8000`

---

## 🔑 Akun Bawaan (Default Login)

| Peran | NIS | Password |
|---|---|---|
| **Admin / Operator OSIS** | `admin` | `password` |
| Siswa 1 | `14144001` | `password` |
| Siswa 2 | `14144002` | `password` |
| ... | `14144003` s/d `14144010` | `password` |

---

## 🧪 Menjalankan Tests

```bash
php artisan test --filter="SongRequestTest|AdminModerationAndPlayerTest|AuthenticationTest|ReportAndScheduleTest|SongVoteAndFeedbackTest|ExampleTest"
```
Hasil: **24 tests passed (73 assertions)** ✅

---

## 👨‍💻 Developer

Dibuat oleh **Aliffansyah** untuk **USK 2026** (Web Technologies).
