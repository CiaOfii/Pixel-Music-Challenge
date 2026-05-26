<?php
require_once '../config/koneksi.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// Get statistics
$totalSoal = $pdo->query("SELECT COUNT(*) FROM soal")->fetchColumn();
$totalPemain = $pdo->query("SELECT COUNT(*) FROM pemain")->fetchColumn();
$totalGameplay = $pdo->query("SELECT COUNT(*) FROM skor")->fetchColumn();
$gameplayToday = $pdo->query("SELECT COUNT(*) FROM skor WHERE DATE(tanggal_main) = CURDATE()")->fetchColumn();

$soal = $pdo->query("SELECT * FROM soal ORDER BY id_soal DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Pixel Music Challenge</title>
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
        }
        
        .admin-header {
            background: #0a0a2a;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff3366;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .admin-header h2 {
            color: #ffffff;
            font-size: 14px;
        }
        
        .admin-header span {
            color: #ffffff;
            font-size: 10px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-card {
            background: #1a1a3a;
            border: 2px solid #00ffcc;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 36px;
            color: #ffcc00;
        }
        
        .stat-card div:not(.stat-number) {
            color: #ffffff;
            font-size: 10px;
            margin-top: 8px;
        }
        
        h3 {
            color: #ffffff;
            margin: 20px 0;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #1a1a3a;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            border: 1px solid #00ffcc;
            padding: 12px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background: #0a0a2a;
            color: #ffcc00;
        }
        
        td {
            color: #ffffff;
            background: #1a1a3a;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 12px;
            margin: 0;
            font-size: 8px;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-family: 'Press Start 2P', monospace;
            border-radius: 4px;
        }
        
        .btn-edit { 
            background: #ffcc00; 
            color: #0a0a2a; 
            border: 1px solid #ffcc00;
        }
        
        .btn-edit:hover {
            background: #ffdd33;
            transform: scale(1.02);
        }
        
        .btn-delete { 
            background: #ff3366; 
            color: white; 
            border: 1px solid #ff3366;
        }
        
        .btn-delete:hover {
            background: #ff5577;
            transform: scale(1.02);
        }
        
        .btn-add {
            background: #00ff66;
            color: #0a0a2a;
            border-color: #00ff66;
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
            text-decoration: none;
            display: inline-block;
        }
        
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            th, td { font-size: 7px; padding: 6px; }
            .stat-number { font-size: 24px; }
            .admin-header h2 { font-size: 10px; }
            .action-btn { padding: 4px 8px; font-size: 6px; }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h2>🎮 ADMIN DASHBOARD - PIXEL MUSIC CHALLENGE</h2>
        <div>
            <span>👑 Halo, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
            <a href="logout.php" class="pixel-btn" style="margin-left:20px; padding:8px 16px; font-size:8px;">🚪 LOGOUT</a>
        </div>
    </div>
    
    <div class="container">
        <!-- STATISTIK CARD -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $totalSoal ?></div>
                <div>📝 TOTAL SOAL</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalPemain ?></div>
                <div>👥 TOTAL PEMAIN</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalGameplay ?></div>
                <div>🎮 TOTAL GAMEPLAY</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $gameplayToday ?></div>
                <div>📅 GAMEPLAY HARI INI</div>
            </div>
        </div>
        
        <!-- TOMBOL AKSI -->
        <div class="btn-group">
            <a href="tambah.php" class="pixel-btn btn-add" style="background:#00ff66; border-color:#00ff66; color:#0a0a2a;">➕ TAMBAH SOAL</a>
            <a href="statistik.php" class="pixel-btn">📊 STATISTIK LENGKAP</a>
        </div>
        
        <!-- TABEL DAFTAR SOAL -->
        <h3>📋 DAFTAR SOAL</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>LIRIK AWAL</th>
                    <th>ARTIS</th>
                    <th>GENRE</th>
                    <th>LEVEL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($soal) > 0): ?>
                    <?php foreach ($soal as $s): ?>
                    <tr>
                        <td><?= $s['id_soal'] ?></td>
                        <td><?= htmlspecialchars(substr($s['lirik_awal'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($s['artis']) ?></td>
                        <td><?= htmlspecialchars($s['genre_musik']) ?></td>
                        <td><?= $s['level_kesulitan'] ?></td>
                        <td><?= $s['status_aktif'] ? '✅ Aktif' : '❌ Nonaktif' ?></td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?= $s['id_soal'] ?>" class="action-btn btn-edit">✏️ EDIT</a>
                            <a href="hapus.php?id=<?= $s['id_soal'] ?>" class="action-btn btn-delete" onclick="return confirm('Yakin hapus soal ini?')">🗑️ HAPUS</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">❌ Belum ada soal. Silakan tambah soal!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>