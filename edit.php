<?php
require_once '../config/koneksi.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;
$soal = $pdo->prepare("SELECT * FROM soal WHERE id_soal = ?");
$soal->execute([$id]);
$soal = $soal->fetch();

if (!$soal) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lirik_awal = $_POST['lirik_awal'] ?? '';
    $lirik_lanjutan = $_POST['lirik_lanjutan'] ?? '';
    $pilihan_a = $_POST['pilihan_a'] ?? '';
    $pilihan_b = $_POST['pilihan_b'] ?? '';
    $pilihan_c = $_POST['pilihan_c'] ?? '';
    $pilihan_d = $_POST['pilihan_d'] ?? '';
    $jawaban_benar = $_POST['jawaban_benar'] ?? '';
    $genre_musik = $_POST['genre_musik'] ?? '';
    $artis = $_POST['artis'] ?? '';
    $tahun_rilis = $_POST['tahun_rilis'] ?? '';
    $level_kesulitan = $_POST['level_kesulitan'] ?? 'sedang';
    $status_aktif = $_POST['status_aktif'] ?? 1;
    
    // PERBAIKAN: Handle tahun_rilis yang kosong
    if ($tahun_rilis === '' || $tahun_rilis === null) {
        $tahun_rilis = null;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE soal SET 
            lirik_awal = ?, 
            lirik_lanjutan = ?, 
            pilihan_a = ?, 
            pilihan_b = ?, 
            pilihan_c = ?, 
            pilihan_d = ?, 
            jawaban_benar = ?, 
            genre_musik = ?, 
            artis = ?, 
            tahun_rilis = ?, 
            level_kesulitan = ?, 
            status_aktif = ?, 
            updated_at = NOW() 
            WHERE id_soal = ?");
        
        if ($stmt->execute([
            $lirik_awal, 
            $lirik_lanjutan, 
            $pilihan_a, 
            $pilihan_b, 
            $pilihan_c, 
            $pilihan_d, 
            $jawaban_benar, 
            $genre_musik, 
            $artis, 
            $tahun_rilis, 
            $level_kesulitan, 
            $status_aktif, 
            $id
        ])) {
            $success = 'Soal berhasil diupdate!';
            // Refresh data
            $soal = $pdo->prepare("SELECT * FROM soal WHERE id_soal = ?");
            $soal->execute([$id]);
            $soal = $soal->fetch();
        } else {
            $error = 'Gagal mengupdate soal.';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Soal - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
        }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .form-container {
            background: #1a1a3a;
            border: 3px solid #00ffcc;
            padding: 30px;
            border-radius: 8px;
        }
        h2 { text-align: center; margin-bottom: 30px; color: #ffcc00; font-size: 14px; }
        label { display: block; margin: 15px 0 5px; font-size: 10px; color: #ffcc00; }
        input, select, textarea {
            font-family: 'Press Start 2P', monospace;
            width: 100%;
            padding: 10px;
            background: #0a0a2a;
            border: 2px solid #ff66cc;
            color: #00ffcc;
            box-sizing: border-box;
            font-size: 9px;
        }
        textarea { min-height: 80px; }
        .success { color: #00ff66; text-align: center; margin-bottom: 20px; font-size: 10px; }
        .error { color: #ff6666; text-align: center; margin-bottom: 20px; font-size: 10px; }
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
            margin-top: 20px;
        }
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #00ffcc; text-decoration: none; font-size: 10px; }
        @media (max-width: 768px) {
            .form-container { padding: 20px; margin: 20px; }
            h2 { font-size: 10px; }
            label, input, select, textarea { font-size: 7px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>✏️ EDIT SOAL #<?= $id ?></h2>
            
            <?php if ($success): ?>
                <div class="success">✅ <?= $success ?> <a href="dashboard.php" style="color:#00ffcc;">Kembali ke Dashboard</a></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <label>📝 LIRIK AWAL</label>
                <textarea name="lirik_awal" required><?= htmlspecialchars($soal['lirik_awal']) ?></textarea>
                
                <label>📄 LIRIK LANJUTAN</label>
                <textarea name="lirik_lanjutan"><?= htmlspecialchars($soal['lirik_lanjutan']) ?></textarea>
                
                <label>A. PILIHAN A</label>
                <input type="text" name="pilihan_a" value="<?= htmlspecialchars($soal['pilihan_a']) ?>" required>
                
                <label>B. PILIHAN B</label>
                <input type="text" name="pilihan_b" value="<?= htmlspecialchars($soal['pilihan_b']) ?>" required>
                
                <label>C. PILIHAN C</label>
                <input type="text" name="pilihan_c" value="<?= htmlspecialchars($soal['pilihan_c']) ?>" required>
                
                <label>D. PILIHAN D</label>
                <input type="text" name="pilihan_d" value="<?= htmlspecialchars($soal['pilihan_d']) ?>" required>
                
                <label>✅ JAWABAN BENAR</label>
                <select name="jawaban_benar" required>
                    <option value="A" <?= $soal['jawaban_benar'] == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $soal['jawaban_benar'] == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= $soal['jawaban_benar'] == 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D" <?= $soal['jawaban_benar'] == 'D' ? 'selected' : '' ?>>D</option>
                </select>
                
                <label>🎸 GENRE MUSIK</label>
                <select name="genre_musik">
                    <option value="Pop" <?= $soal['genre_musik'] == 'Pop' ? 'selected' : '' ?>>Pop</option>
                    <option value="Rock" <?= $soal['genre_musik'] == 'Rock' ? 'selected' : '' ?>>Rock</option>
                    <option value="Jazz" <?= $soal['genre_musik'] == 'Jazz' ? 'selected' : '' ?>>Jazz</option>
                    <option value="HipHop" <?= $soal['genre_musik'] == 'HipHop' ? 'selected' : '' ?>>HipHop</option>
                    <option value="Dangdut" <?= $soal['genre_musik'] == 'Dangdut' ? 'selected' : '' ?>>Dangdut</option>
                    <option value="KPop" <?= $soal['genre_musik'] == 'KPop' ? 'selected' : '' ?>>KPop</option>
                    <option value="Metal" <?= $soal['genre_musik'] == 'Metal' ? 'selected' : '' ?>>Metal</option>
                    <option value="RnB" <?= $soal['genre_musik'] == 'RnB' ? 'selected' : '' ?>>RnB</option>
                </select>
                
                <label>🎤 ARTIS / BAND</label>
                <input type="text" name="artis" value="<?= htmlspecialchars($soal['artis']) ?>">
                
                <label>📅 TAHUN RILIS</label>
                <input type="number" name="tahun_rilis" value="<?= $soal['tahun_rilis'] ?>" min="1900" max="2026" placeholder="Contoh: 2020">
                <small style="font-size:7px; color:#aaa;">Kosongkan jika tidak tahu</small>
                
                <label>⚡ LEVEL KESULITAN</label>
                <select name="level_kesulitan">
                    <option value="mudah" <?= $soal['level_kesulitan'] == 'mudah' ? 'selected' : '' ?>>🌱 PEMULA (Mudah)</option>
                    <option value="sedang" <?= $soal['level_kesulitan'] == 'sedang' ? 'selected' : '' ?>>⚡ NORMAL (Sedang)</option>
                    <option value="sulit" <?= $soal['level_kesulitan'] == 'sulit' ? 'selected' : '' ?>>🔥 HARD (Sulit)</option>
                    <option value="expert" <?= $soal['level_kesulitan'] == 'expert' ? 'selected' : '' ?>>💀 EXPERT (Expert)</option>
                </select>
                
                <label>📌 STATUS</label>
                <select name="status_aktif">
                    <option value="1" <?= $soal['status_aktif'] == 1 ? 'selected' : '' ?>>✅ Aktif</option>
                    <option value="0" <?= $soal['status_aktif'] == 0 ? 'selected' : '' ?>>❌ Nonaktif</option>
                </select>
                
                <button type="submit" class="pixel-btn">💾 UPDATE SOAL</button>
            </form>
            
            <div class="back-link">
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>