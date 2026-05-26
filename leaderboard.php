<?php
require_once 'config/koneksi.php';

// Ambil top 10 pemain berdasarkan skor tertinggi
$stmt = $pdo->query("SELECT username, skor_tertinggi, level_pemain, avatar, total_main FROM pemain WHERE status = 'aktif' ORDER BY skor_tertinggi DESC LIMIT 10");
$topPlayers = $stmt->fetchAll();

// Ambil top minggu ini
$stmt = $pdo->query("SELECT p.username, SUM(s.skor) as total_skor FROM skor s JOIN pemain p ON s.id_pemain = p.id_pemain WHERE s.tanggal_main >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY p.id_pemain ORDER BY total_skor DESC LIMIT 10");
$weeklyTop = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - Pixel Music Challenge</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .leaderboard-container {
            max-width: 800px;
            margin: 50px auto;
        }
        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a3a;
            border: 2px solid #00ffcc;
            margin: 20px 0;
        }
        .leaderboard-table th,
        .leaderboard-table td {
            border: 1px solid #00ffcc;
            padding: 12px;
            text-align: center;
            font-size: 10px;
        }
        .leaderboard-table th {
            background: #2a2a4a;
            color: #ffcc00;
        }
        .rank-1 { background: rgba(255, 215, 0, 0.2); }
        .rank-2 { background: rgba(192, 192, 192, 0.2); }
        .rank-3 { background: rgba(205, 127, 50, 0.2); }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            font-family: 'Press Start 2P', monospace;
            background: #2a2a4a;
            border: 2px solid #00ffcc;
            padding: 10px 20px;
            cursor: pointer;
        }
        .tab-btn.active {
            background: #00ffcc;
            color: #0a0a2a;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="leaderboard-container">
            <h1 style="text-align: center; margin-bottom: 30px;">🏆 LEADERBOARD</h1>
            
            <div class="tab-buttons">
                <div class="tab-btn active" onclick="showTab('alltime')">🏅 ALL TIME</div>
                <div class="tab-btn" onclick="showTab('weekly')">📅 MINGGU INI</div>
            </div>
            
            <div id="alltime" class="tab-content active">
                <table class="leaderboard-table">
                    <thead>
                        <tr><th>#</th><th>AVATAR</th><th>USERNAME</th><th>LEVEL</th><th>SKOR TERTINGGI</th><th>TOTAL MAIN</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topPlayers as $index => $player): ?>
                        <tr class="rank-<?= $index+1 <= 3 ? $index+1 : '' ?>">
                            <td><?= $index+1 ?></td>
                            <td>🎮</td>
                            <td><?= escape($player['username']) ?></td>
                            <td><?= $player['level_pemain'] ?></td>
                            <td><?= number_format($player['skor_tertinggi']) ?></td>
                            <td><?= $player['total_main'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($topPlayers) == 0): ?>
                        <tr><td colspan="6">Belum ada pemain</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="weekly" class="tab-content">
                <table class="leaderboard-table">
                    <thead>
                        <tr><th>#</th><th>USERNAME</th><th>TOTAL SKOR MINGGU INI</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($weeklyTop as $index => $player): ?>
                        <tr>
                            <td><?= $index+1 ?></td>
                            <td><?= escape($player['username']) ?></td>
                            <td><?= number_format($player['total_skor']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($weeklyTop) == 0): ?>
                        <tr><td colspan="3">Belum ada skor minggu ini</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button class="pixel-btn" onclick="location.href='index.php'">← KEMBALI</button>
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tab).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>