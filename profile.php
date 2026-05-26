<?php
require_once 'config/koneksi.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM pemain WHERE id_pemain = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Ambil history game
$stmt = $pdo->prepare("SELECT * FROM skor WHERE id_pemain = ? ORDER BY tanggal_main DESC LIMIT 20");
$stmt->execute([$userId]);
$histories = $stmt->fetchAll();

$message = '';

// Update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare("UPDATE pemain SET nama_lengkap = ?, email = ? WHERE id_pemain = ?");
    if ($stmt->execute([$nama_lengkap, $email, $userId])) {
        $message = '✅ Profil berhasil diupdate!';
        // Refresh data user
        $stmt = $pdo->prepare("SELECT * FROM pemain WHERE id_pemain = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
    } else {
        $message = '❌ Gagal update profil.';
    }
}

// Ganti password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (password_verify($old_password, $user['password'])) {
        if (strlen($new_password) >= 4 && $new_password === $confirm_password) {
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE pemain SET password = ? WHERE id_pemain = ?");
            if ($stmt->execute([$new_hash, $userId])) {
                $message = '✅ Password berhasil diubah!';
            } else {
                $message = '❌ Gagal mengubah password.';
            }
        } else {
            $message = '❌ Password baru minimal 4 karakter atau tidak cocok!';
        }
    } else {
        $message = '❌ Password lama salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Pixel Music Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
        }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .profile-box {
            background: rgba(26, 26, 58, 0.95);
            border: 3px solid #00ffcc;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 30px;
        }
        h1 { font-size: 16px; color: #ffcc00; text-align: center; margin-bottom: 30px; }
        h2 { font-size: 12px; color: #ffcc00; margin: 20px 0 15px; }
        .info-group { margin-bottom: 15px; display: flex; border-bottom: 1px solid #2a2a4a; padding-bottom: 8px; flex-wrap: wrap; }
        .info-label { width: 150px; font-size: 9px; color: #ffcc00; }
        .info-value { font-size: 9px; color: #ffffff; }
        input {
            font-family: 'Press Start 2P', monospace;
            width: 100%;
            padding: 10px;
            background: #0a0a2a;
            border: 2px solid #ff66cc;
            color: #00ffcc;
            font-size: 9px;
            border-radius: 8px;
        }
        .pixel-btn {
            font-family: 'Press Start 2P', monospace;
            background: #2a2a4a;
            border: 4px solid #00ffcc;
            color: #00ffcc;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.1s ease;
            box-shadow: 4px 4px 0 #008866;
            font-size: 9px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #00ffcc; padding: 8px; text-align: center; font-size: 8px; }
        th { background: #0a0a2a; color: #ffcc00; }
        td { color: #ffffff; }
        .message { text-align: center; padding: 10px; margin-bottom: 20px; border-radius: 8px; font-size: 9px; }
        .message.success { background: #0a2a1a; border: 1px solid #00ff66; color: #00ff66; }
        .message.error { background: #2a0a1a; border: 1px solid #ff3366; color: #ff6666; }
        .back-link { text-align: center; margin-top: 20px; }
        @media (max-width: 768px) {
            .info-group { flex-direction: column; }
            .info-label { width: 100%; margin-bottom: 5px; }
            h1 { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-box">
            <h1>👤 PROFIL PEMAIN</h1>
            
            <?php if ($message): ?>
                <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <div class="info-group">
                <div class="info-label">🎮 USERNAME</div>
                <div class="info-value"><?= escape($user['username']) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">📛 NAMA LENGKAP</div>
                <div class="info-value"><?= escape($user['nama_lengkap'] ?? '-') ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">📧 EMAIL</div>
                <div class="info-value"><?= escape($user['email'] ?? '-') ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">⭐ LEVEL</div>
                <div class="info-value"><?= escape($user['level_pemain'] ?? '1') ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">🪙 KOIN</div>
                <div class="info-value"><?= number_format($user['koin'] ?? 0) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">🏆 SKOR TERTINGGI</div>
                <div class="info-value"><?= number_format($user['skor_tertinggi'] ?? 0) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">📅 BERGABUNG SEJAK</div>
                <div class="info-value"><?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?></div>
            </div>
        </div>
        
        <div class="profile-box">
            <h2>✏️ UPDATE PROFIL</h2>
            <form method="POST">
                <input type="hidden" name="update_profile" value="1">
                <div class="info-group">
                    <div class="info-label">📛 NAMA LENGKAP</div>
                    <input type="text" name="nama_lengkap" value="<?= escape($user['nama_lengkap'] ?? '') ?>">
                </div>
                <div class="info-group">
                    <div class="info-label">📧 EMAIL</div>
                    <input type="email" name="email" value="<?= escape($user['email'] ?? '') ?>">
                </div>
                <button type="submit" class="pixel-btn" style="width:100%; margin-top:15px;">💾 SIMPAN PERUBAHAN</button>
            </form>
        </div>
        
        <div class="profile-box">
            <h2>🔒 GANTI PASSWORD</h2>
            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="info-group">
                    <div class="info-label">PASSWORD LAMA</div>
                    <input type="password" name="old_password" required>
                </div>
                <div class="info-group">
                    <div class="info-label">PASSWORD BARU</div>
                    <input type="password" name="new_password" required>
                </div>
                <div class="info-group">
                    <div class="info-label">KONFIRMASI</div>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="pixel-btn" style="width:100%; margin-top:15px;">🔑 UBAH PASSWORD</button>
            </form>
        </div>
        
        <div class="profile-box">
            <h2>📜 RIWAYAT PERMAINAN</h2>
            <?php if (count($histories) > 0): ?>
                <table>
                    <thead>
                        <tr><th>TANGGAL</th><th>SKOR</th><th>BENAR</th><th>SALAH</th><th>MODE</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($histories as $h): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($h['tanggal_main'])) ?></td>
                            <td><?= number_format($h['skor']) ?></td>
                            <td><?= $h['jumlah_benar'] ?></td>
                            <td><?= $h['jumlah_salah'] ?></td>
                            <td><?= escape($h['mode_permainan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; font-size:9px;">Belum ada riwayat permainan. Yuk main dulu!</p>
            <?php endif; ?>
        </div>
        
        <div class="back-link">
            <a href="dashboard.php" class="pixel-btn">← KEMBALI KE DASHBOARD</a>
        </div>
    </div>
</body>
</html> 