<?php
// fix_database.php - Jalankan sekali untuk memperbaiki database
require_once 'config/koneksi.php';

echo "<h1>Fix Database - Pixel Music Challenge</h1>";

try {
    // Cek kolom unlocked_levels
    $stmt = $pdo->query("SHOW COLUMNS FROM pemain LIKE 'unlocked_levels'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Kolom 'unlocked_levels' tidak ditemukan. Menambahkan...<br>";
        $pdo->exec("ALTER TABLE `pemain` ADD COLUMN `unlocked_levels` TEXT NULL DEFAULT '[\"Pemula\"]' AFTER `skor_tertinggi`");
        echo "✅ Kolom 'unlocked_levels' berhasil ditambahkan!<br>";
    } else {
        echo "✅ Kolom 'unlocked_levels' sudah ada.<br>";
    }
    
    // Cek kolom email
    $stmt = $pdo->query("SHOW COLUMNS FROM pemain LIKE 'email'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `pemain` ADD COLUMN `email` VARCHAR(100) NULL AFTER `nama_lengkap`");
        echo "✅ Kolom 'email' berhasil ditambahkan!<br>";
    }
    
    // Cek kolom last_login
    $stmt = $pdo->query("SHOW COLUMNS FROM pemain LIKE 'last_login'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `pemain` ADD COLUMN `last_login` TIMESTAMP NULL AFTER `status`");
        echo "✅ Kolom 'last_login' berhasil ditambahkan!<br>";
    }
    
    // Update data yang ada
    $pdo->exec("UPDATE pemain SET unlocked_levels = '[\"Pemula\"]' WHERE unlocked_levels IS NULL");
    echo "✅ Data pemain telah diupdate.<br>";
    
    // Cek tabel soal
    $stmt = $pdo->query("SHOW COLUMNS FROM soal LIKE 'level_kesulitan'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Kolom 'level_kesulitan' tidak ditemukan di tabel soal.<br>";
    } else {
        echo "✅ Kolom 'level_kesulitan' sudah ada.<br>";
    }
    
    echo "<hr>";
    echo "<h2>✅ Database siap digunakan!</h2>";
    echo "<a href='register.php' class='pixel-btn'>→ Lanjut ke Register</a>";
    echo "<br><br>";
    echo "<a href='login.php' class='pixel-btn'>→ Login</a>";
    
} catch(PDOException $e) {
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
?>
<style>
    body { font-family: monospace; padding: 20px; background: #0a0a2a; color: #00ffcc; }
    .pixel-btn { background: #2a2a4a; border: 2px solid #00ffcc; color: #00ffcc; padding: 10px 20px; text-decoration: none; display: inline-block; margin: 10px 0; }
    h1 { color: #ffcc00; }
</style>