# 📘 Resume Aplikasi Journey Learn LMS
## Hubungan dengan Software Development Life Cycle (SDLC)

---

## 🎯 Deskripsi Aplikasi

**Journey Learn** adalah sistem manajemen pembelajaran (Learning Management System) berbasis web yang memfasilitasi proses belajar mengajar secara daring antara Dosen dan Mahasiswa.

### Fitur Utama:
| Fitur | Deskripsi |
|-------|-----------|
| Multi-Role Authentication | Login/Register untuk Dosen & Mahasiswa |
| Manajemen Kelas | CRUD kelas oleh Dosen |
| Konten Multimedia | Video (YouTube), Text, Quiz |
| Sequential Learning | Materi terkunci secara berurutan |
| Quiz dengan Attempt Limit | Maksimal 3x percobaan per quiz |
| Progress Tracking | Dashboard dengan persentase penyelesaian |

### Teknologi:
- **Backend:** PHP 8.2 + Laravel 11
- **Database:** MySQL (db_elearning)
- **Frontend:** Blade Templates + Bootstrap 5
- **Authentication:** Laravel Breeze

---

## 🔄 Hubungan dengan SDLC

### Model SDLC: **Waterfall + Iterative**

```
┌─────────────────────────────────────────────────────────────┐
│  1. PLANNING        → Analisis kebutuhan, requirement      │
│  2. ANALYSIS        → Analisis bug, security audit         │
│  3. DESIGN          → Arsitektur MVC, database schema      │
│  4. DEVELOPMENT     → Coding PHP Native → Laravel          │
│  5. TESTING         → Pengujian fungsional                 │
│  6. DEPLOYMENT      → Deploy ke localhost/server           │
│  7. MAINTENANCE     → Bug fixes, security patches          │
└─────────────────────────────────────────────────────────────┘
```

---

## 📋 Fase SDLC dalam Proyek Ini

### 1️⃣ PLANNING (Perencanaan)

**Aktivitas yang Dilakukan:**
- Identifikasi kebutuhan stakeholder (Dosen & Mahasiswa)
- Menentukan scope fitur aplikasi
- Membuat mockup/wireframe UI
- Menyusun timeline pengembangan (4 bulan)

**Dokumen yang Dihasilkan:**
- User Requirements Specification
- Project Timeline
- Feature List & Priority

**Contoh dari Proyek:**
```
Fitur yang direncanakan:
✅ Login/Register dengan role
✅ Browse & enroll kelas
✅ View materi (video, text, quiz)
✅ Sequential learning
✅ Progress tracking
```

---

### 2️⃣ ANALYSIS (Analisis)

**Aktivitas yang Dilakukan:**
- Analisis sistem existing (PHP Native)
- Identifikasi bug dan security vulnerabilities
- Analisis kebutuhan migrasi ke Laravel

**Dokumen yang Dihasilkan:**
- Bug Analysis Report (40 temuan)
- Security Audit Report
- Migration Assessment

**Temuan Analisis:**
| Kategori | Temuan | Status |
|----------|--------|--------|
| Critical | XSS Vulnerability | ✅ Fixed |
| Critical | Session Fixation | ✅ Fixed |
| High | Sequential Learning Bypass | ✅ Fixed |
| High | Quiz Attempt Unlimited | ✅ Fixed |
| Medium | Missing CSRF Protection | ✅ Fixed (Laravel) |

---

### 3️⃣ DESIGN (Perancangan)

**Aktivitas yang Dilakukan:**
- Merancang arsitektur MVC Laravel
- Desain database schema (7 tabel)
- Merancang UI/UX dengan Bootstrap

**Arsitektur Sistem:**
```
┌─────────────────────────────────────────────┐
│                 BROWSER                      │
└──────────────────┬──────────────────────────┘
                   │
┌──────────────────▼──────────────────────────┐
│              LARAVEL 11                      │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐     │
│  │ Routes  │→ │Controller│→ │  Model  │     │
│  └─────────┘  └────┬────┘  └────┬────┘     │
│                    │            │           │
│              ┌─────▼─────┐     │           │
│              │   View    │     │           │
│              │  (Blade)  │     │           │
│              └───────────┘     │           │
└────────────────────────────────┼───────────┘
                                 │
┌────────────────────────────────▼───────────┐
│              MySQL Database                 │
│  users, kelas, materi, enrollment,         │
│  progress, kuis, hasil_kuis                │
└────────────────────────────────────────────┘
```

**Database Schema:**
```
users ──┬── enrollment ──── kelas ──── materi ──┬── kuis
        │                                        │
        └── progress ───────────────────────────┘
                                                 │
        └── hasil_kuis ─────────────────────────┘
```

---

### 4️⃣ DEVELOPMENT (Pengembangan)

**Aktivitas yang Dilakukan:**
- Setup Laravel 11 project
- Membuat 7 Eloquent Models
- Membuat 3 Controllers utama
- Membuat Role Middleware
- Membuat 12+ Blade Views
- Integrasi Laravel Breeze

**File Structure:**
```
LMS-Laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── KelasController.php
│   │   │   └── MateriController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Kelas.php
│       ├── Enrollment.php
│       ├── Materi.php
│       ├── Progress.php
│       ├── Kuis.php
│       └── HasilKuis.php
├── resources/views/
│   ├── dashboard/ (2 files)
│   ├── kelas/ (4 files)
│   └── materi/ (5 files)
└── routes/web.php
```

**Kode yang Dikembangkan:**
- **Lines of Code:** ~2000+ LOC
- **Controllers:** 3 dengan ~500 LOC
- **Models:** 7 dengan relationships
- **Views:** 12+ Blade templates
- **Routes:** 20+ endpoints

---

### 5️⃣ TESTING (Pengujian)

**Jenis Pengujian:**

| Jenis | Metode | Status |
|-------|--------|--------|
| Unit Testing | Manual function test | ✅ |
| Integration Testing | API endpoint test | ✅ |
| Security Testing | Vulnerability scan | ✅ |
| UAT (User Acceptance) | Manual user flow | 🔄 |

**Test Cases:**
```
✅ TC-001: Register mahasiswa baru
✅ TC-002: Register dosen baru
✅ TC-003: Login dengan email valid
✅ TC-004: Login dengan password salah
✅ TC-005: Browse kelas (mahasiswa)
✅ TC-006: Enroll kelas
✅ TC-007: Akses materi pertama
✅ TC-008: Materi kedua terkunci
✅ TC-009: Complete materi → unlock berikutnya
✅ TC-010: Submit quiz
✅ TC-011: Quiz attempt limit (max 3x)
✅ TC-012: Buat kelas baru (dosen)
✅ TC-013: Tambah materi
✅ TC-014: Tambah soal quiz
```

---

### 6️⃣ DEPLOYMENT (Penyebaran)

**Environment:**
- **Development:** localhost (XAMPP)
- **Production:** (pending)

**Cara Deploy:**
```bash
# Development
cd c:\xampp\htdocs\LMS-Laravel
php artisan serve

# Akses: http://127.0.0.1:8000
```

**Checklist Deployment:**
- [x] Configure .env
- [x] Database connection
- [x] Cache configuration
- [ ] Production server setup
- [ ] SSL certificate
- [ ] Domain configuration

---

### 7️⃣ MAINTENANCE (Pemeliharaan)

**Aktivitas Maintenance:**
- Bug fixes yang ditemukan saat testing
- Security patches
- Performance optimization
- Feature enhancements

**Bug Fixes yang Dilakukan:**
| Bug | Deskripsi | Solusi |
|-----|-----------|--------|
| Vite Error | ViteManifestNotFoundException | Ganti ke Tailwind CDN |
| DB Connection | No connection error | Start MySQL + fix cache config |
| Timestamps | Unknown column 'updated_at' | Disable timestamps di models |

---

## 📊 Kesimpulan

### Kelebihan Aplikasi:
1. ✅ Arsitektur MVC yang terstruktur (Laravel)
2. ✅ Keamanan terintegrasi (CSRF, XSS, Auth)
3. ✅ Sequential learning yang efektif
4. ✅ UI/UX modern dan responsive
5. ✅ Code maintainability tinggi

### Pembelajaran dari SDLC:
1. **Planning** → Pentingnya requirement yang jelas
2. **Analysis** → Bug analysis sebelum development
3. **Design** → Arsitektur yang scalable (MVC)
4. **Development** → Framework vs Native PHP
5. **Testing** → Test early, test often
6. **Deployment** → Environment configuration
7. **Maintenance** → Iterative improvement

---

## 📚 Referensi

- Laravel Documentation: https://laravel.com/docs
- SDLC Models: https://www.tutorialspoint.com/sdlc/
- PHP Best Practices: https://phptherightway.com/

---

*Dokumen ini dibuat sebagai bagian dari tugas mata kuliah*
*Tanggal: 28 Januari 2026*
