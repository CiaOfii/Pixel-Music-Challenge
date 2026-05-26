<?php
require_once '../config/koneksi.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// PASTIKAN FOLDER UPLOAD ADA
$preview_dir = '../assets/audio/preview/';
$full_dir = '../assets/audio/full/';

// Buat folder jika belum ada
if (!is_dir($preview_dir)) {
    mkdir($preview_dir, 0777, true);
}
if (!is_dir($full_dir)) {
    mkdir($full_dir, 0777, true);
}

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
    
    // Handle tahun_rilis yang kosong
    if ($tahun_rilis === '' || $tahun_rilis === null) {
        $tahun_rilis = null;
    }
    
    // Handle file uploads
    $audioPreview = null;
    $audioFull = null;
    
    // Upload audio preview
    if (isset($_FILES['audio_preview']) && $_FILES['audio_preview']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['audio_preview']['name'], PATHINFO_EXTENSION));
        $allowed = ['mp3', 'wav', 'ogg', 'm4a'];
        
        if (in_array($ext, $allowed)) {
            $audioPreview = uniqid() . '_preview.' . $ext;
            $target_path = $preview_dir . $audioPreview;
            
            if (move_uploaded_file($_FILES['audio_preview']['tmp_name'], $target_path)) {
                // Upload sukses
            } else {
                $error .= "Gagal upload audio preview. ";
            }
        } else {
            $error .= "Format audio preview harus MP3/WAV/OGG. ";
        }
    }
    
    // Upload audio full
    if (isset($_FILES['audio_full']) && $_FILES['audio_full']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['audio_full']['name'], PATHINFO_EXTENSION));
        $allowed = ['mp3', 'wav', 'ogg', 'm4a'];
        
        if (in_array($ext, $allowed)) {
            $audioFull = uniqid() . '_full.' . $ext;
            $target_path = $full_dir . $audioFull;
            
            if (move_uploaded_file($_FILES['audio_full']['tmp_name'], $target_path)) {
                // Upload sukses
            } else {
                $error .= "Gagal upload audio full. ";
            }
        } else {
            $error .= "Format audio full harus MP3/WAV/OGG. ";
        }
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO soal (nama_file_audio_preview, nama_file_audio_full, lirik_awal, lirik_lanjutan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, genre_musik, artis, tahun_rilis, level_kesulitan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$audioPreview, $audioFull, $lirik_awal, $lirik_lanjutan, $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, $jawaban_benar, $genre_musik, $artis, $tahun_rilis, $level_kesulitan])) {
            $success = 'Soal berhasil ditambahkan!';
            if ($error) {
                $success .= ' (' . $error . ')';
                $error = '';
            }
        } else {
            $error = 'Gagal menambahkan soal.';
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
    <title>Tambah Soal - Admin</title>
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
        h2 { text-align: center; margin-bottom: 30px; color: #ffcc00; font-size: 16px; }
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
        input[type="file"] { padding: 8px; }
        .success { color: #00ff66; text-align: center; margin-bottom: 20px; font-size: 10px; }
        .error { color: #ff6666; text-align: center; margin-bottom: 20px; font-size: 10px; }
        .warning { color: #ffcc00; text-align: center; margin-bottom: 20px; font-size: 9px; }
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
        small { font-size: 7px; color: #aaa; }
        @media (max-width: 768px) {
            .form-container { padding: 20px; margin: 20px; }
            h2 { font-size: 12px; }
            label, input, select, textarea { font-size: 7px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>➕ TAMBAH SOAL BARU</h2>
            
            <?php if ($success): ?>
                <div class="success">✅ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="warning">
                ⚠️ Pastikan file audio berformat MP3 dan ukuran tidak terlalu besar (max 5MB)
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <label>🎵 AUDIO PREVIEW (MP3) - WAJIB</label>
                <input type="file" name="audio_preview" accept="audio/mpeg, audio/wav, audio/ogg" required>
                <small>File audio potongan lagu (wajib untuk gameplay)</small>
                
                <label>🎤 AUDIO FULL (MP3) - Opsional</label>
                <input type="file" name="audio_full" accept="audio/mpeg, audio/wav, audio/ogg">
                <small>File audio lagu lengkap (opsional)</small>
                
                <label>📝 LIRIK AWAL</label>
                <textarea name="lirik_awal" required placeholder="Contoh: Karena aku suka kamu..."></textarea>
                <small>Lirik yang akan ditampilkan di soal</small>
                
                <label>📄 LIRIK LANJUTAN (Jawaban lengkap)</label>
                <textarea name="lirik_lanjutan" placeholder="Contoh: Dan aku akan selalu menyayangimu"></textarea>
                <small>Lirik lengkap untuk feedback setelah menjawab</small>
                
                <label>A. PILIHAN JAWABAN A</label>
                <input type="text" name="pilihan_a" required>
                
                <label>B. PILIHAN JAWABAN B</label>
                <input type="text" name="pilihan_b" required>
                
                <label>C. PILIHAN JAWABAN C</label>
                <input type="text" name="pilihan_c" required>
                
                <label>D. PILIHAN JAWABAN D</label>
                <input type="text" name="pilihan_d" required>
                
                <label>✅ JAWABAN BENAR</label>
                <select name="jawaban_benar" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
                
                <label>🎸 GENRE MUSIK</label>
                <select name="genre_musik">
                    <option value="Pop">Pop</option>
                    <option value="Rock">Rock</option>
                    <option value="Jazz">Jazz</option>
                    <option value="HipHop">HipHop</option>
                    <option value="Dangdut">Dangdut</option>
                    <option value="KPop">KPop</option>
                    <option value="Metal">Metal</option>
                    <option value="RnB">RnB</option>
                </select>
                
                <label>🎤 ARTIS / BAND</label>
                <input type="text" name="artis" placeholder="Contoh: Adele, Ed Sheeran, Queen">
                
                <label>📅 TAHUN RILIS</label>
                <input type="number" name="tahun_rilis" min="1900" max="2026" placeholder="Contoh: 2020">
                <small>Kosongkan jika tidak tahu</small>
                
                <label>⚡ LEVEL KESULITAN</label>
                <select name="level_kesulitan" required>
                    <option value="mudah">🌱 PEMULA (Mudah)</option>
                    <option value="sedang">⚡ NORMAL (Sedang)</option>
                    <option value="sulit">🔥 HARD (Sulit)</option>
                    <option value="expert">💀 EXPERT (Expert)</option>
                </select>
                
                <button type="submit" class="pixel-btn">💾 SIMPAN SOAL</button>
            </form>
            
            <div class="back-link">
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>