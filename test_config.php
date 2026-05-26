<?php
require_once 'config/koneksi.php';

echo "<h1>Test Config</h1>";

// Cek fungsi
echo "<p>isUserLoggedIn(): " . (isUserLoggedIn() ? "true" : "false") . "</p>";
echo "<p>isAdmin(): " . (isAdmin() ? "true" : "false") . "</p>";

// Cek session
echo "<p>Session ID: " . session_id() . "</p>";

// Cek database lagi
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM pemain");
    $count = $stmt->fetchColumn();
    echo "<p style='color:green'>✅ Database OK! Jumlah pemain: $count</p>";
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<a href='index.php'>Kembali ke Index</a>";
?>