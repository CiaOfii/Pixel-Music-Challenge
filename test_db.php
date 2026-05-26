<?php
$host = 'sql105.infinityfree.com';
$dbname = 'if0_41953237_music_db';
$username = 'if0_41953237';
$password = 'apabilaberaksi';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h1 style='color:green'>✅ KONEKSI BERHASIL!</h1>";
    echo "<p>Host: $host</p>";
    echo "<p>Database: $dbname</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) FROM pemain");
    $count = $stmt->fetchColumn();
    echo "<p>Jumlah pemain: $count</p>";
    
} catch(PDOException $e) {
    echo "<h1 style='color:red'>❌ KONEKSI GAGAL!</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>