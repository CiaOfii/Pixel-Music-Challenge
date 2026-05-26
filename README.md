# 🎵 Pixel Music Challenge

**Pixel Music Challenge** adalah sebuah game kuis musik interaktif berbasis web yang dirancang dengan tampilan visual retro arcade 8-bit. Game ini menguji kemampuan pemain dalam mengenali dan melanjutkan lirik lagu berdasarkan cuplikan audio yang diputar oleh sistem.

---

## 📌 Latar Belakang

Proyek ini dikembangkan sebagai tugas akhir mata kuliah Pemrograman Web. Tujuan utama dari pengembangan game ini adalah menciptakan media hiburan interaktif yang tidak hanya menyenangkan tetapi juga dapat menguji wawasan pemain terhadap musik dan lirik lagu dari berbagai genre.

---

## ✨ Fitur Utama

### 👤 Untuk Pemain (User)
- Registrasi dan login akun
- Pilihan 4 level kesulitan: **Pemula, Normal, Hard, Expert**
- Sistem permainan dengan soal acak (tidak berulang dalam satu sesi)
- Streak bonus (3x, 5x, 10x jawaban benar berturut-turut)
- Perolehan XP dan Koin
- Leaderboard global (semua waktu dan mingguan)
- Sistem pencapaian (Achievement)
- Halaman profil dengan riwayat permainan
- Efek visual pixel retro, screen shake, dan partikel

### 👑 Untuk Administrator
- Login khusus admin
- Manajemen soal (CRUD: Tambah, Lihat, Edit, Hapus)
- Upload file audio preview dan full lagu
- Statistik jumlah pemain, soal, dan gameplay
- Konfigurasi pengaturan game (poin, timer, jumlah soal)

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Keterangan |
|-----------|------------|
| **PHP (Native)** | Backend logic dan koneksi database |
| **MySQL** | Penyimpanan data pemain, soal, skor, dll |
| **HTML5, CSS3** | Struktur dan tampilan antarmuka |
| **JavaScript (Vanilla)** | Logika gameplay, timer, audio, dan interaksi |
| **PDO** | Koneksi database yang aman (SQL injection protection) |
| **Hosting** | InfinityFree (gratis) |

---

## 🔐 Fitur Keamanan

| Teknik Keamanan | Penerapan |
|----------------|-----------|
| Password Hashing | `password_hash()` dengan algoritma BCRYPT |
| Prepared Statement | PDO untuk mencegah SQL Injection |
| CSRF Token | Token unik pada setiap form |
| XSS Protection | `htmlspecialchars()` untuk sanitasi output |
| Session Management | Pengelolaan session untuk login user dan admin |

---

## 📁 Struktur Database (8 Tabel)

| Tabel | Fungsi |
|-------|--------|
| `pemain` | Menyimpan data pemain (username, password, level, XP, koin) |
| `admin` | Menyimpan data administrator |
| `soal` | Menyimpan soal, lirik, pilihan jawaban, dan file audio |
| `skor` | Menyimpan riwayat permainan pemain |
| `achievement` | Menyimpan daftar pencapaian yang tersedia |
| `pemain_achievement` | Menyimpan pencapaian yang telah diperoleh pemain |
| `konfigurasi_game` | Menyimpan pengaturan game (poin, timer, jumlah soal per level) |
| `level_progress` | Menyimpan progres pemain per level |

---

## 🎮 Cara Menjalankan di Lokal (Localhost)

### Prasyarat
- Web server (Laragon / XAMPP)
- PHP 7.4 atau lebih tinggi
- MySQL

### Langkah-langkah
## 🛠️ Prasyarat

- Web server (Laragon / XAMPP)
- PHP 7.4 atau lebih tinggi
- MySQL

## 🚀 Langkah-langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/Pixel-Music-Challenge.git

# 2. Pindahkan folder ke direktori web server
# Contoh Laragon: D:\laragon\www\
# Contoh XAMPP: C:\xampp\htdocs\

# 3. Import database
# - Buka phpMyAdmin
# - Buat database baru: music_game_db
# - Import file database.sql

# 4. Konfigurasi database
# - Buka config/koneksi.php
# - Sesuaikan credential database dengan environment lokal

---

## ✅ **Kunci Agar Demo Online Tidak Ikut ke Bash**

```markdown

```         

---       

## 🌐 Demo Online   ← HEADING BARU DI LUAR CODE BLOCK

🔗 https://musicgames.fwh.is
