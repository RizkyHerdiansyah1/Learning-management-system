# 🎨 Diagram Visual untuk Presentasi
## Journey Learn LMS - Visual Diagrams

---

## 📌 CARA MENGGUNAKAN DIAGRAM INI

### Opsi 1: Mermaid Live Editor (Recommended)
1. Buka: https://mermaid.live/
2. Copy kode Mermaid dari file ini
3. Paste di editor
4. Download sebagai PNG/SVG

### Opsi 2: Draw.io / diagrams.net
1. Buka: https://app.diagrams.net/
2. Buat diagram manual berdasarkan structure di bawah
3. Export sebagai PNG/PDF

### Opsi 3: Canva / PowerPoint
1. Gunakan template mind map
2. Isi dengan data dari dokumen ini

---

## 🏗️ DIAGRAM 1: ARSITEKTUR MVC SISTEM

```mermaid
graph TB
    subgraph "PRESENTATION LAYER"
        A[Browser - Mahasiswa]
        B[Browser - Dosen]
    end
    
    subgraph "APPLICATION LAYER - Laravel 11"
        C[Routes<br/>web.php]
        D[Middleware<br/>Auth + Role]
        E[Controllers<br/>Dashboard, Kelas, Materi]
        F[Models<br/>7 Eloquent Models]
        G[Views<br/>Blade Templates]
    end
    
    subgraph "DATA LAYER"
        H[(MySQL Database<br/>db_elearning)]
    end
    
    A --> C
    B --> C
    C --> D
    D --> E
    E --> F
    E --> G
    F --> H
    G --> A
    G --> B
    
    style A fill:#e1f5ff
    style B fill:#fff3e0
    style E fill:#c8e6c9
    style F fill:#f8bbd0
    style H fill:#d1c4e9
```

**Cara Render:**
Copy kode di atas → Paste ke https://mermaid.live/ → Download PNG

---

## 🗄️ DIAGRAM 2: DATABASE ERD (Entity Relationship)

```mermaid
erDiagram
    USERS ||--o{ ENROLLMENT : has
    USERS ||--o{ PROGRESS : tracks
    USERS ||--o{ HASIL_KUIS : submits
    USERS ||--o{ KELAS : creates
    
    KELAS ||--o{ ENROLLMENT : contains
    KELAS ||--o{ MATERI : has
    
    MATERI ||--o{ PROGRESS : tracked_in
    MATERI ||--o{ KUIS : contains
    MATERI ||--o{ HASIL_KUIS : generates
    
    USERS {
        int id PK
        string nama
        string email
        string password
        string role
        string foto_profil
    }
    
    KELAS {
        int id PK
        int dosen_id FK
        string nama_kelas
        string deskripsi
        string kategori
    }
    
    ENROLLMENT {
        int id PK
        int user_id FK
        int kelas_id FK
    }
    
    MATERI {
        int id PK
        int kelas_id FK
        string judul
        string tipe
        text konten
        int urutan
        int durasi_menit
    }
    
    PROGRESS {
        int id PK
        int user_id FK
        int materi_id FK
        string status
        datetime waktu_selesai
    }
    
    KUIS {
        int id PK
        int materi_id FK
        text pertanyaan
        string pilihan_a-d
        string jawaban_benar
        int poin
    }
    
    HASIL_KUIS {
        int id PK
        int user_id FK
        int materi_id FK
        int skor
        int total_soal
        json jawaban_detail
    }
```

**Cara Render:**
Copy kode di atas → Paste ke https://mermaid.live/ → Download PNG

---

## 🔄 DIAGRAM 3: SDLC WATERFALL FLOW

```mermaid
graph TD
    A[1. PLANNING<br/>Requirement Gathering] --> B[2. ANALYSIS<br/>Bug & Security Audit]
    B --> C[3. DESIGN<br/>MVC Architecture + ERD]
    C --> D[4. DEVELOPMENT<br/>Coding 3700+ LOC]
    D --> E[5. TESTING<br/>14 Test Cases]
    E --> F[6. DEPLOYMENT<br/>Localhost XAMPP]
    F --> G[7. MAINTENANCE<br/>Bug Fixes + Updates]
    G -.Iterative.-> B
    
    style A fill:#bbdefb
    style B fill:#c8e6c9
    style C fill:#fff9c4
    style D fill:#ffccbc
    style E fill:#f8bbd0
    style F fill:#d1c4e9
    style G fill:#b2dfdb
```

**Cara Render:**
Copy kode di atas → Paste ke https://mermaid.live/ → Download PNG

---

## 🧠 DIAGRAM 4: PROJECT MIND MAP

```mermaid
mindmap
  root((Journey Learn LMS))
    Features
      Multi-Role System
        Dosen
        Mahasiswa
      Sequential Learning
      Quiz System
        Max 3 Attempts
        Auto Grading
      Progress Tracking
      Multimedia Content
        Video YouTube
        Text
        Quiz
    Technology Stack
      Backend
        Laravel 11
        PHP 8.2
      Frontend
        Blade Templates
        Bootstrap 5
      Database
        MySQL
        7 Tables
      Auth
        Laravel Breeze
    Architecture
      MVC Pattern
      Models
        7 Eloquent Models
      Controllers
        DashboardController
        KelasController
        MateriController
      Views
        12+ Blade Files
    SDLC
      Planning
        Requirements
        Mockup
      Analysis
        40 Bugs Found
        Security Audit
      Design
        ERD
        UI/UX
      Development
        8 Weeks
        3700+ LOC
      Testing
        14 Test Cases
      Deployment
        Localhost
      Maintenance
        Ongoing
```

**Cara Render:**
Copy kode di atas → Paste ke https://mermaid.live/ → Download PNG

---

## 🎯 DIAGRAM 5: FLOW DIAGRAM USER JOURNEY

### A. Mahasiswa Flow

```mermaid
flowchart TD
    Start([Mahasiswa Login]) --> Dashboard[View Dashboard]
    Dashboard --> Browse[Browse Kelas]
    Browse --> Enroll[Enroll Kelas]
    Enroll --> ViewMateri[View Materi #1]
    ViewMateri --> Check{Tipe Materi?}
    
    Check -->|Video| WatchVideo[Watch Video]
    Check -->|Text| ReadText[Read Content]
    Check -->|Quiz| TakeQuiz[Take Quiz]
    
    WatchVideo --> Complete[Mark Complete]
    ReadText --> Complete
    TakeQuiz --> CheckScore{Score >= 70%?}
    
    CheckScore -->|Yes| Complete
    CheckScore -->|No| Retry{Attempt < 3?}
    
    Retry -->|Yes| TakeQuiz
    Retry -->|No| Locked[Cannot Retry]
    
    Complete --> Unlock[Unlock Next Materi]
    Unlock --> NextMateri{More Materi?}
    
    NextMateri -->|Yes| ViewMateri
    NextMateri -->|No| Finish([Class Complete!])
    
    style Start fill:#4caf50
    style Finish fill:#2196f3
    style Complete fill:#8bc34a
    style Locked fill:#f44336
```

### B. Dosen Flow

```mermaid
flowchart TD
    Start([Dosen Login]) --> Dashboard[View Dashboard<br/>+ Statistics]
    Dashboard --> Choice{Action?}
    
    Choice -->|Create| CreateClass[Create New Kelas]
    Choice -->|Manage| ManageClass[Manage Existing Kelas]
    
    CreateClass --> AddMateri[Add Materi]
    ManageClass --> AddMateri
    
    AddMateri --> MateriType{Materi Type?}
    
    MateriType -->|Video| InputVideo[Input YouTube URL]
    MateriType -->|Text| InputText[Input Text Content]
    MateriType -->|Quiz| CreateQuiz[Create Quiz]
    
    InputVideo --> SaveMateri[Save Materi]
    InputText --> SaveMateri
    CreateQuiz --> AddQuestions[Add Questions]
    AddQuestions --> SaveMateri
    
    SaveMateri --> More{Add More?}
    More -->|Yes| AddMateri
    More -->|No| Finish([Done])
    
    style Start fill:#ff9800
    style Finish fill:#4caf50
    style CreateQuiz fill:#9c27b0
```

---

## 📊 ALTERNATIF: Tools untuk Membuat Diagram

### 1. **Mermaid Live Editor** (Recommended)
- URL: https://mermaid.live/
- Gratis, langsung render
- Export PNG/SVG/PDF

### 2. **Draw.io / diagrams.net**
- URL: https://app.diagrams.net/
- Drag & drop interface
- Banyak template

### 3. **Lucidchart**
- URL: https://www.lucidchart.com/
- Professional templates
- Free tier available

### 4. **Canva**
- URL: https://www.canva.com/
- Mind map templates
- Easy to customize

### 5. **PowerPoint / Google Slides**
- Built-in SmartArt
- Custom shapes
- Export sebagai gambar

---

## 💡 Tips Presentasi dengan Diagram

1. **Gunakan warna konsisten** untuk setiap layer/komponen
2. **Tambahkan legenda** jika diagram kompleks
3. **Export dalam resolusi tinggi** (untuk proyektor)
4. **Buat animasi** di PowerPoint untuk flow diagram
5. **Print dalam A3** untuk poster presentasi

---

## 📥 Quick Links

| Diagram | Mermaid Code Location | Type |
|---------|----------------------|------|
| MVC Architecture | Section 1 | Graph TB |
| Database ERD | Section 2 | ER Diagram |
| SDLC Flow | Section 3 | Graph TD |
| Mind Map | Section 4 | Mindmap |
| User Journey | Section 5 | Flowchart |

---

*Semua diagram dalam file ini bisa di-copy dan di-render di https://mermaid.live/*
*Untuk hasil terbaik, export sebagai SVG (vector) agar tidak pecah saat diperbesar*
