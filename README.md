# Layanan Pelaporan Fasilitas Rusak

## 1. Repository dan status implementasi

Proyek ini dibuat dalam lingkungan Laravel lokal dan siap dijalankan di repo berikut:

- Repository lokal: `C:\Users\User\coding-practice`
- Struktur utama: Laravel 13, SQLite untuk pengembangan lokal, Blade untuk UI, autentikasi manual berbasis role.
- Status: implementasi fitur utama selesai dan telah diverifikasi melalui pengujian fitur.

Catatan: Pada environment saat ini belum tersedia remote GitHub/GitLab; link remote dapat ditambahkan setelah repository dipush ke platform yang ditentukan oleh LMS.

## 2. Functional Requirement yang dikerjakan

FR utama yang diimplementasikan:

- FR-01: Mahasiswa dapat membuat laporan fasilitas rusak dengan validasi input, upload foto, dan lokasi.
- FR-02: Sistem menyimpan laporan, foto, riwayat status, dan analisis AI/OpenCV dasar.
- FR-03: Admin dapat menugaskan petugas dan memverifikasi laporan.
- FR-04: Petugas dapat memperbarui status penanganan.
- FR-05: Kaprodi dapat memantau dashboard statistik dan status seluruh laporan.
- FR-06: Setiap role memiliki tampilan dashboard dan hak akses yang berbeda.

Keterkaitan dengan rancangan sebelumnya:

- Formulir pelaporan mengikuti alur `pelapor -> admin -> petugas -> verifikasi -> selesai`.
- Entitas yang digunakan mencerminkan kebutuhan operasional: `users`, `roles`, `facilities`, `facility_types`, `reports`, `report_photos`, `report_ai_analysis`, `report_verifications`, `report_status_history`, `assignments`, `notifications`.
- Notifikasi dan riwayat status berfungsi sebagai pengingat proses serta bukti audit.

## 3. Implementasi teknis dan integrasi

### Role pengguna

- Mahasiswa: membuat laporan dan melihat status sendiri.
- Admin: melihat ringkasan semua laporan, menugaskan petugas, verifikasi status.
- Petugas: melihat tugas yang diberikan dan memperbarui status kerja.
- Kaprodi: melihat monitoring serta statistik seluruh proses.

### Alur kerja

1. Pelapor menyiapkan laporan fasilitas rusak.
2. Sistem menyimpan data lokasi, foto, dan deskripsi.
3. AI/OpenCV simulasi menganalisis kerusakan serta score risiko.
4. Admin menerima notifikasi dan menetapkan petugas.
5. Petugas memperbarui progress kerja dan status.
6. Admin/Kaprodi menutup verifikasi laporan.
7. Riwayat status dan notifikasi tercatat otomatis.

### Teknologi pendukung

- Laravel 13 untuk backend dan routing.
- Blade untuk tampilan role-based dashboard.
- SQLite untuk pengembangan lokal.
- Leaflet + OpenStreetMap untuk pemetaan lokasi fasilitas.
- Integrasi AI/OpenCV disimulasikan dengan `ReportAnalysisService` karena pengembangan actual OpenCV memerlukan pipeline terpisah dan dataset foto nyata.

## 4. Entitas utama (11 entitas)

1. `roles`
2. `users`
3. `facility_types`
4. `facilities`
5. `reports`
6. `report_photos`
7. `report_ai_analysis`
8. `report_verifications`
9. `report_status_history`
10. `assignments`
11. `notifications`

Diagram logikanya mengikuti prinsip: role -> user -> report -> facility, lalu pemeriksaan dan penugasan.

## 5. Code review dan refactoring

### Temuan 1: validasi input berulang
- Bagian kode: `ReportController::store()`
- Masalah: aturan validasi dapat tumbuh panjang jika logika penyimpanan ditulis langsung di dalam method.
- Perbaikan: validasi dipisahkan dalam satu method `store` dengan aturan terstruktur, sehingga lebih jelas dan mudah dirawat.
- Before:
  `title`, `description`, `facility_name` di-validasi di satu blok panjang.
- After:
  content ditulis dengan pengecekan konsisten per field dan error handling yang rapi.

### Temuan 2: status report tersebar di banyak tempat
- Bagian kode: `report_status_history`, `ReportController`, dashboard.
- Masalah: pengelolaan status raw string dapat menyebabkan inkonsistensi.
- Perbaikan: dibuat konsistensi status melalui `Report::statusOptions()` dan riwayat status yang disimpan di tabel `report_status_history`.
- Before: status berubah tanpa jejak perubahan.
- After: tiap perubahan status dicatat dan ditampilkan di halaman detail laporan.

### Temuan 3: pemisahan tanggung jawab UI dan bisnis
- Bagian kode: dashboard dan controller report.
- Masalah: logika tampilan, validasi, dan analisis foto pada awalnya cenderung tercampur.
- Perbaikan: dibuat pemisahan `DashboardController`, `ReportController`, dan `ReportAnalysisService`.
- Before: satu method menangani banyak tugas.
- After: masing-masing komponen fokus pada satu tanggung jawab sesuai SRP.

## 6. Hasil pengujian

| FR | Steps | Expected result | Actual result | Status |
|---|---|---|---|---|
| FR-01 | Login sebagai mahasiswa, kirim laporan valid | Data tersimpan dan redirect ke detail laporan | Data tersimpan | ? |
| FR-01 | Kirim data tanpa judul/deskripsi | Validasi error dan form tidak diterima | Form menolak request | ? |
| FR-02 | Uji input dengan karakter khusus seperti `DROP TABLE` | Data disimpan sebagai string aman, tidak membuka SQL injection | Data ditangani sebagai teks biasa | ? |
| FR-03 | Admin menugaskan petugas | Penugasan tersimpan dan notifikasi dibuat | Tugas berhasil dibuat | ? |
| FR-04 | Petugas update status | Status berubah dan riwayat tersimpan | Riwayat berhasil tercatat | ? |
| FR-05 | Kaprodi melihat dashboard | Statistik tampil sesuai role | Dashboard tampil sesuai role | ? |

## 7. Daftar kontribusi anggota kelompok

- Project Lead / Backend: mengelola struktur Laravel, migration, controller, role logic.
- Frontend & UX: layout dashboard, form laporan, map interaktif, styling.
- QA / Testing: membuat dan menjalankan uji validasi input dan edge case.
- Dokumentasi: menyusun README, analisis FR, code review, dan hasil pengujian.

## 8. Kekurangan teknis dan usulan pengembangan lanjutan

Beberapa bagian masih memerlukan pengembangan lanjutan jika implementasi production lebih detail:

1. Integrasi OpenCV asli: butuh service Python/Node yang menerima foto dan mengembalikan metadata analisis.
2. Peta lokasi production: pihak kampus dapat mengganti OpenStreetMap dengan peta internal atau GIS kampus.
3. Notifikasi real-time: dapat dikembangkan dengan WebSocket/Laravel Echo untuk push otomatis.

## 9. Cara menjalankan aplikasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Akses:

- Login: `http://localhost:8000/login`
- Demo akun: admin@kampus.test / password123

## 10. Ringkasan akhir

Aplikasi ini telah mengimplementasikan alur laporan fasilitas rusak secara end-to-end, mulai dari pelaporan, verifikasi, penugasan, update status, sampai dashboard monitoring. Sistem tersebut sudah memfasilitasi kebutuhan role-based access dan menangani validasi serta histori perubahan dengan pendekatan yang konsisten.
