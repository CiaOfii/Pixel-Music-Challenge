<?php
require_once 'config/koneksi.php';

if (isUserLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Remember me checkbox
if (isset($_COOKIE['remember_username'])) {
    $remember_username = $_COOKIE['remember_username'];
} else {
    $remember_username = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifikasi CSRF Token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token keamanan tidak valid. Silakan refresh halaman.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($username) || empty($password)) {
            $error = 'Username dan password harus diisi!';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM pemain WHERE username = ? AND status = 'aktif'");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_pemain'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['level'] = $user['level_pemain'];
                $_SESSION['coins'] = $user['koin'];
                $_SESSION['unlocked_levels'] = json_decode($user['unlocked_levels'] ?? '["Pemula"]', true);
                
                // Remember me
                if ($remember) {
                    setcookie('remember_username', $username, time() + 86400 * 30, '/');
                }
                
                // Update last login
                $stmt = $pdo->prepare("UPDATE pemain SET last_login = NOW() WHERE id_pemain = ?");
                $stmt->execute([$user['id_pemain']]);
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Username atau password salah!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pixel Music Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0,0,0,0.1) 0px, rgba(0,0,0,0.1) 2px, transparent 2px, transparent 4px);
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-box {
            background: rgba(26, 26, 58, 0.95);
            border: 4px solid #00ffcc;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 30px rgba(0,255,204,0.2);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }
        
        .title {
            font-size: 20px;
            color: #ffcc00;
            text-shadow: 3px 3px 0 #ff66cc;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 8px;
            color: #00ffcc;
            margin-bottom: 30px;
        }
        
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .input-group label {
            font-size: 8px;
            display: block;
            margin-bottom: 8px;
            color: #ffcc00;
        }
        
        input {
            font-family: 'Press Start 2P', monospace;
            width: 100%;
            padding: 12px;
            background: #0a0a2a;
            border: 2px solid #ff66cc;
            color: #00ffcc;
            font-size: 10px;
            box-sizing: border-box;
            border-radius: 8px;
        }
        
        input:focus {
            outline: none;
            border-color: #ffcc00;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .checkbox-group input {
            width: auto;
            margin: 0;
        }
        
        .checkbox-group label {
            font-size: 8px;
            margin: 0;
            color: #00ffcc;
        }
        
        .pixel-btn {
            font-family: 'Press Start 2P', monospace;
            background: #2a2a4a;
            border: 4px solid #00ffcc;
            color: #00ffcc;
            padding: 12px 24px;
            cursor: pointer;
            transition: all 0.1s ease;
            box-shadow: 4px 4px 0 #008866;
            font-size: 10px;
            width: 100%;
            margin-top: 10px;
            border-radius: 8px;
        }
        
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        
        .error {
            color: #ff6666;
            font-size: 8px;
            margin-bottom: 20px;
            background: rgba(255,102,102,0.1);
            padding: 10px;
            border: 1px solid #ff6666;
            border-radius: 8px;
        }
        
        .link {
            margin-top: 20px;
            font-size: 8px;
        }
        
        .link a {
            color: #ffcc00;
            text-decoration: none;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
        
        .admin-link {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(0,255,204,0.3);
        }
        
        @media (max-width: 768px) {
            .login-box { padding: 25px; margin: 20px; }
            .title { font-size: 14px; }
            input { font-size: 8px; padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="title">🎵 PIXEL MUSIC<br>CHALLENGE 🎮</div>
            <div class="subtitle">⚡ LOGIN TO PLAY ⚡</div>
            
            <?php if ($error): ?>
                <div class="error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="input-group">
                    <label>👤 USERNAME</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($remember_username) ?>" required autofocus>
                </div>
                <div class="input-group">
                    <label>🔒 PASSWORD</label>
                    <input type="password" name="password" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="pixel-btn">🎮 LOGIN & PLAY</button>
            </form>
            
            <div class="link">
                Belum punya akun? <a href="register.php">REGISTER SEKARANG</a>
            </div>
            
            <div class="link admin-link">
                <a href="admin/login.php">👑 Admin Login</a>
            </div>
        </div>
    </div>
</body>
</html>