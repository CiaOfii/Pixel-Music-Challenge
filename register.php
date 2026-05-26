<?php
require_once 'config/koneksi.php';

if (isUserLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifikasi CSRF Token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token keamanan tidak valid. Silakan refresh halaman.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($username) || empty($password)) {
            $error = 'Username dan password harus diisi!';
        } elseif (strlen($username) < 3) {
            $error = 'Username minimal 3 karakter!';
        } elseif (strlen($password) < 4) {
            $error = 'Password minimal 4 karakter!';
        } elseif ($password !== $confirm_password) {
            $error = 'Konfirmasi password tidak cocok!';
        } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid!';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pemain WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'Username sudah terdaftar!';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $unlockedLevels = json_encode(['Pemula']);
                
                $stmt = $pdo->prepare("INSERT INTO pemain (username, password, nama_lengkap, email, avatar, unlocked_levels, created_at) VALUES (?, ?, ?, ?, 'default.png', ?, NOW())");
                
                if ($stmt->execute([$username, $hashedPassword, $nama_lengkap, $email, $unlockedLevels])) {
                    $success = 'Registrasi berhasil! Silakan login.';
                } else {
                    $error = 'Registrasi gagal, silakan coba lagi.';
                }
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
    <title>Register - Pixel Music Challenge</title>
    <meta name="description" content="Daftar akun Pixel Music Challenge dan mulai bermain game kuis musik retro 8-bit">
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
        
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }
        
        .register-box {
            background: rgba(26, 26, 58, 0.95);
            border: 4px solid #00ffcc;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 30px rgba(0,255,204,0.2);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }
        
        .title {
            font-size: 16px;
            color: #ffcc00;
            text-shadow: 3px 3px 0 #ff66cc;
            margin-bottom: 20px;
        }
        
        .input-group {
            margin-bottom: 15px;
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
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 5px rgba(255,204,0,0.3);
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
        
        .success {
            color: #00ff66;
            font-size: 8px;
            margin-bottom: 20px;
            background: rgba(0,255,102,0.1);
            padding: 10px;
            border: 1px solid #00ff66;
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
        
        small {
            font-size: 6px;
            color: #aaa;
            display: block;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .register-box { padding: 25px; margin: 20px; }
            .title { font-size: 12px; }
            input, .pixel-btn { font-size: 8px; padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="title">📝 REGISTER NEW ACCOUNT</div>
            
            <?php if ($error): ?>
                <div class="error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success">✅ <?= htmlspecialchars($success) ?> <a href="login.php" style="color:#00ff66;">Login disini</a></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="input-group">
                    <label>👤 USERNAME *</label>
                    <input type="text" name="username" required autofocus>
                    <small>Minimal 3 karakter, tanpa spasi</small>
                </div>
                <div class="input-group">
                    <label>📛 NAMA LENGKAP</label>
                    <input type="text" name="nama_lengkap">
                    <small>Opsional</small>
                </div>
                <div class="input-group">
                    <label>📧 EMAIL</label>
                    <input type="email" name="email">
                    <small>Opsional, untuk pemulihan password</small>
                </div>
                <div class="input-group">
                    <label>🔒 PASSWORD *</label>
                    <input type="password" name="password" required>
                    <small>Minimal 4 karakter</small>
                </div>
                <div class="input-group">
                    <label>🔒 KONFIRMASI PASSWORD *</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="pixel-btn">✨ REGISTER</button>
            </form>
            
            <div class="link">
                Sudah punya akun? <a href="login.php">LOGIN</a>
            </div>
        </div>
    </div>
</body>
</html>