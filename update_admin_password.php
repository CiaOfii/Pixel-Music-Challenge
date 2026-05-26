<?php
// update_admin_password.php - Upload ke hosting, jalankan SEKALI, lalu hapus!

$host = 'sql105.infinityfree.com';
$dbname = 'if0_41953237_music_db';
$username = 'if0_41953237';
$password = 'apabilaberaksi';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Ganti 'admin123' dengan password baru Anda
    $new_password = 'pentoltelu';
    $hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hash]);
    
    echo "✅ Password admin berhasil diubah menjadi: " . $new_password;
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>