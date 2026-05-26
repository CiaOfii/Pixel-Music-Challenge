<?php
require_once 'config/koneksi.php';

// Hash password admin123
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

echo "Password: admin123<br>";
echo "Hash: " . $hashed_password . "<br><br>";

// Update ke database
try {
    // Cek apakah admin sudah ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = 'admin'");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        // Update password admin yang sudah ada
        $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'");
        $stmt->execute([$hashed_password]);
        echo "✅ Password admin berhasil diupdate!<br>";
    } else {
        // Insert admin baru
        $stmt = $pdo->prepare("INSERT INTO admin (username, password, nama) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $hashed_password, 'Administrator']);
        echo "✅ Admin baru berhasil dibuat!<br>";
    }
    
    echo "<br><strong>🚀 Silakan login ke admin panel dengan:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='admin/login.php'>Klik disini untuk login</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>