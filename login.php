<?php
session_start();
require_once '../config/koneksi.php';

if (isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_name'] = $admin['nama'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Pixel Music Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .admin-login {
            max-width: 400px;
            margin: 50px auto;
            background: #1a1a3a;
            border: 3px solid #ff3366;
            padding: 40px;
            text-align: center;
            border-radius: 16px;
        }
        h2 { color: #ff3366; margin-bottom: 30px; font-size: 16px; }
        input {
            font-family: 'Press Start 2P', monospace;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #0a0a2a;
            border: 2px solid #ff3366;
            color: #ff3366;
            box-sizing: border-box;
            font-size: 10px;
            border-radius: 8px;
        }
        .error {
            color: #ff6666;
            margin-bottom: 20px;
            font-size: 10px;
            background: rgba(255,102,102,0.1);
            padding: 10px;
            border-radius: 8px;
        }
        .pixel-btn {
            font-family: 'Press Start 2P', monospace;
            background: #2a2a4a;
            border: 4px solid #ff3366;
            color: #ff3366;
            padding: 12px 24px;
            cursor: pointer;
            transition: all 0.1s ease;
            box-shadow: 4px 4px 0 #880000;
            font-size: 10px;
            width: 100%;
            margin-top: 10px;
            border-radius: 8px;
        }
        .pixel-btn:hover {
            background: #ff3366;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #880000;
        }
        /* ========== TAMBAHAN CSS UNTUK LINK KEMBALI ========== */
        .back-link {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(0,255,204,0.3);
        }
        .back-link a {
            color: #00ffcc;
            font-size: 8px;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .admin-login { margin: 20px; padding: 25px; }
            h2 { font-size: 12px; }
            input, .pixel-btn { font-size: 8px; }
        }
    </style>
</head>
<body>
    <div class="admin-login">
        <h2>👑 ADMIN PANEL</h2>
        <?php if ($error): ?>
            <div class="error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="USERNAME" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <button type="submit" class="pixel-btn">LOGIN</button>
        </form>
        
        <!-- ========== TOMBOL KEMBALI KE LOGIN USER ========== -->
        <div class="back-link">
            <a href="../login.php">← Kembali ke Halaman Login User</a>
        </div>
    </div>
</body>
</html>