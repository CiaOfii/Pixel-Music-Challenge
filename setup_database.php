<?php
// setup_database.php - Jalankan sekali untuk membuat semua tabel

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'music_game_db';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
    
    // Buat tabel pemain
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `pemain` (
            `id_pemain` INT(11) NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `nama_lengkap` VARCHAR(100) DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `avatar` VARCHAR(100) DEFAULT 'default.png',
            `total_skor` INT(11) DEFAULT 0,
            `level_pemain` INT(11) DEFAULT 1,
            `xp` INT(11) DEFAULT 0,
            `koin` INT(11) DEFAULT 0,
            `total_main` INT(11) DEFAULT 0,
            `skor_tertinggi` INT(11) DEFAULT 0,
            `unlocked_levels` TEXT DEFAULT '[\"Pemula\"]',
            `status` ENUM('aktif', 'nonaktif', 'banned') DEFAULT 'aktif',
            `last_login` TIMESTAMP NULL DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_pemain`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Buat tabel admin
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admin` (
            `id_admin` INT(11) NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `nama` VARCHAR(100) DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_admin`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Insert admin default
    $pdo->exec("INSERT IGNORE INTO `admin` (`username`, `password`, `nama`) VALUES 
        ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator')");
    
    // Buat tabel soal
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `soal` (
            `id_soal` INT(11) NOT NULL AUTO_INCREMENT,
            `nama_file_audio_preview` VARCHAR(255) DEFAULT NULL,
            `lirik_awal` TEXT,
            `pilihan_a` VARCHAR(500) NOT NULL,
            `pilihan_b` VARCHAR(500) NOT NULL,
            `pilihan_c` VARCHAR(500) NOT NULL,
            `pilihan_d` VARCHAR(500) NOT NULL,
            `jawaban_benar` ENUM('A','B','C','D') NOT NULL,
            `artis` VARCHAR(100) DEFAULT NULL,
            `level_kesulitan` ENUM('mudah','sedang','sulit','expert') DEFAULT 'sedang',
            `status_aktif` TINYINT(1) DEFAULT 1,
            PRIMARY KEY (`id_soal`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    echo "<h2 style='color:green'>✅ DATABASE BERHASIL DIBUAT!</h2>";
    echo "<a href='register.php'>→ Lanjut ke Register</a><br>";
    echo "<a href='login.php'>→ Login</a>";
    
} catch(PDOException $e) {
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
?>
<style>
    body { font-family: monospace; padding: 20px; background: #0a0a2a; color: #00ffcc; }
    a { color: #ffcc00; }
</style>