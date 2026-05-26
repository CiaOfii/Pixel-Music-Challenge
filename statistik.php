<?php
require_once '../config/koneksi.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// Statistik per level
$stats = $pdo->query("SELECT level_kesulitan, COUNT(*) as total FROM soal GROUP BY level_kesulitan")->fetchAll();
$playerStats = $pdo->query("SELECT COUNT(*) as total, SUM(total_main) as total_games, SUM(skor_tertinggi) as total_score FROM pemain")->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Statistik - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
        }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .stat-box {
            background: #1a1a3a;
            border: 3px solid #00ffcc;
            padding: 30px;
            margin: 20px 0;
            border-radius: 8px;
        }
        h1 { text-align: center; color: #ffcc00; font-size: 14px; margin-bottom: 30px; }
        h3 { color: #ffcc00; margin: 20px 0 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            border: 1px solid #00ffcc;
            padding: 10px;
            text-align: center;
            font-size: 9px;
        }
        th { background: #0a0a2a; color: #ffcc00; }
        td { color: #ffffff; }
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
            margin-top: 20px;
        }
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="stat-box">
            <h1>📊 STATISTIK GAME</h1>
            
            <h3>📝 STATISTIK SOAL PER LEVEL</h3>
            <table>
                <tr><th>Level</th><th>Jumlah Soal</th></tr>
                <?php foreach ($stats as $s): ?>
                <tr>
                    <td><?= $s['level_kesulitan'] ?></td>
                    <td><?= $s['total'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <h3>👥 STATISTIK PEMAIN</h3>
            <table>
                <tr><th>Total Pemain</th><td><?= $playerStats['total'] ?></td></tr>
                <tr><th>Total Gameplay</th><td><?= $playerStats['total_games'] ?></td></tr>
                <tr><th>Total Skor</th><td><?= number_format($playerStats['total_score']) ?></td></tr>
            </table>
            
            <div style="text-align: center;">
                <a href="dashboard.php" class="pixel-btn">← KEMBALI KE DASHBOARD</a>
            </div>
        </div>
    </div>
</body>
</html>