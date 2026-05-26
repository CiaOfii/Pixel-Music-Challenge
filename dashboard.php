<?php
require_once 'config/koneksi.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$userLevel = $_SESSION['level'];
$userCoins = $_SESSION['coins'];

// Ambil statistik pemain
$stmt = $pdo->prepare("SELECT 
    COALESCE(SUM(skor), 0) as total_score, 
    COUNT(*) as total_games, 
    COALESCE(MAX(skor), 0) as high_score,
    COALESCE(SUM(jumlah_benar), 0) as total_correct,
    COALESCE(SUM(jumlah_salah), 0) as total_wrong
    FROM skor WHERE id_pemain = ?");
$stmt->execute([$userId]);
$stats = $stmt->fetch();

// Ambil XP progress ke level berikutnya
$xp = $pdo->prepare("SELECT xp FROM pemain WHERE id_pemain = ?");
$xp->execute([$userId]);
$currentXp = $xp->fetchColumn();
$xpNeeded = 100 - ($currentXp % 100);
$nextLevel = floor($currentXp / 100) + 1;

// Level definitions - SEMUA TERBUKA
$levels = [
    ['id' => 'Pemula', 'name' => '🌱 PEMULA', 'color' => '#00ff66', 'bg' => '#0a2a1a', 'icon' => '🌱', 'desc' => '8 soal | 45 detik | +10 poin'],
    ['id' => 'Normal', 'name' => '⚡ NORMAL', 'color' => '#00ffcc', 'bg' => '#0a2a2a', 'icon' => '⚡', 'desc' => '8 soal | 30 detik | +12 poin'],
    ['id' => 'Hard', 'name' => '🔥 HARD', 'color' => '#ff6600', 'bg' => '#2a1a0a', 'icon' => '🔥', 'desc' => '5 soal | 20 detik | +15 poin'],
    ['id' => 'Expert', 'name' => '💀 EXPERT', 'color' => '#ff3366', 'bg' => '#2a0a1a', 'icon' => '💀', 'desc' => '3 soal | 15 detik | +20 poin']
];

// Ambil top 5 leaderboard
$topPlayers = $pdo->query("SELECT username, skor_tertinggi, level_pemain FROM pemain WHERE status = 'aktif' ORDER BY skor_tertinggi DESC LIMIT 5")->fetchAll();

// Ambil achievement terbaru
$stmt = $pdo->prepare("SELECT a.nama_achievement, a.icon FROM achievement a 
    JOIN pemain_achievement pa ON a.id_achievement = pa.id_achievement 
    WHERE pa.id_pemain = ? ORDER BY pa.unlocked_at DESC LIMIT 3");
$stmt->execute([$userId]);
$recentAchievements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pixel Music Challenge</title>
    <meta name="description" content="Pilih level kesulitan dan mulai bermain Pixel Music Challenge - Game kuis musik retro 8-bit">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Press Start 2P', monospace;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3a 100%);
            color: #00ffcc;
            min-height: 100vh;
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
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; position: relative; z-index: 1; }
        
        /* Header */
        .header {
            background: rgba(10, 10, 42, 0.95);
            border: 3px solid #ffcc00;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            border-radius: 16px;
        }
        .user-info { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .user-badge { background: #2a2a4a; padding: 8px 15px; border-radius: 8px; font-size: 10px; }
        .user-badge span { color: #ffcc00; }
        .logout-btn { background: #ff3366; border-color: #ff3366; padding: 8px 16px; font-size: 8px; text-decoration: none; }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(26, 26, 58, 0.95);
            border: 2px solid #00ffcc;
            padding: 15px;
            text-align: center;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-value { font-size: 24px; color: #ffcc00; margin-bottom: 5px; }
        .stat-label { font-size: 8px; }
        
        /* XP Bar */
        .xp-container { margin-bottom: 30px; }
        .xp-bar {
            background: #2a2a4a;
            border: 2px solid #ffcc00;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .xp-progress {
            background: linear-gradient(90deg, #00ff66, #ffcc00);
            width: 0%;
            height: 100%;
            transition: width 0.5s ease;
        }
        .xp-text { font-size: 8px; text-align: right; margin-top: 5px; }
        
        /* Section Title */
        .section-title { font-size: 14px; color: #ffcc00; text-align: center; margin-bottom: 25px; }
        
        /* Level Grid */
        .level-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .level-card {
            background: #1a1a3a;
            border: 3px solid;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 16px;
            position: relative;
        }
        .level-card:hover { transform: translateY(-5px); filter: brightness(1.05); }
        .level-icon { font-size: 48px; margin-bottom: 15px; }
        .level-name { font-size: 14px; margin-bottom: 10px; }
        .level-desc { font-size: 7px; margin-bottom: 15px; color: #aaa; }
        .level-reward { font-size: 8px; background: rgba(0,0,0,0.5); padding: 8px; border-radius: 8px; }
        
        /* Leaderboard Preview */
        .leaderboard-preview {
            background: rgba(26, 26, 58, 0.95);
            border: 2px solid #00ffcc;
            padding: 20px;
            border-radius: 16px;
            margin-top: 30px;
        }
        .leaderboard-title { font-size: 10px; color: #ffcc00; margin-bottom: 15px; text-align: center; }
        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #2a2a4a;
            font-size: 9px;
        }
        .leaderboard-item:last-child { border-bottom: none; }
        .rank-1 { background: rgba(255, 215, 0, 0.15); border-radius: 8px; }
        .rank-2 { background: rgba(192, 192, 192, 0.1); border-radius: 8px; }
        .rank-3 { background: rgba(205, 127, 50, 0.1); border-radius: 8px; }
        
        /* Recent Achievements */
        .achievement-list { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 15px; }
        .achievement-badge {
            background: #2a2a4a;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 7px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            border-radius: 8px;
        }
        .pixel-btn:hover {
            background: #00ffcc;
            color: #0a0a2a;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 #008866;
        }
        
        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .level-grid { grid-template-columns: 1fr; }
            .stat-value { font-size: 18px; }
            .user-badge { font-size: 8px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <div class="user-badge">🎮 <?= htmlspecialchars($username) ?></div>
                <div class="user-badge">⭐ LV.<?= $userLevel ?></div>
                <div class="user-badge">🪙 <?= number_format($userCoins) ?> COINS</div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="profile.php" class="pixel-btn" style="padding: 8px 16px;">👤 PROFIL</a>
                <a href="leaderboard.php" class="pixel-btn" style="padding: 8px 16px;">🏆 RANKING</a>
                <a href="logout.php" class="pixel-btn logout-btn" style="background:#ff3366; border-color:#ff3366;">🚪 LOGOUT</a>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['total_games'] ?? 0) ?></div>
                <div class="stat-label">TOTAL MAIN</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['total_score'] ?? 0) ?></div>
                <div class="stat-label">TOTAL SKOR</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['high_score'] ?? 0) ?></div>
                <div class="stat-label">SKOR TERTINGGI</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= number_format($stats['total_correct'] ?? 0) ?> / <?= number_format(($stats['total_correct'] ?? 0) + ($stats['total_wrong'] ?? 0)) ?></div>
                <div class="stat-label">BENAR / TOTAL</div>
            </div>
        </div>
        
        <!-- XP Progress -->
        <div class="xp-container">
            <div class="xp-bar">
                <div class="xp-progress" style="width: <?= ($currentXp % 100) ?>%"></div>
            </div>
            <div class="xp-text">⚡ <?= $currentXp ?> XP - <?= $xpNeeded ?> XP lagi ke Level <?= $nextLevel ?></div>
        </div>
        
        <!-- Level Selection - SEMUA TERBUKA -->
        <div class="section-title">⚔️ PILIH LEVEL KESULITAN ⚔️</div>
        <div class="level-grid">
            <?php foreach ($levels as $level): ?>
                <div class="level-card" style="border-color: <?= $level['color'] ?>; background: <?= $level['bg'] ?>"
                     onclick="startGame('<?= $level['id'] ?>')">
                    <div class="level-icon"><?= $level['icon'] ?></div>
                    <div class="level-name" style="color: <?= $level['color'] ?>"><?= $level['name'] ?></div>
                    <div class="level-desc"><?= $level['desc'] ?></div>
                    <div class="level-reward">
                        🎁 <?= $level['id'] == 'Pemula' ? 'Mulai Sekarang' : ($level['id'] == 'Normal' ? '+12 Poin per benar' : ($level['id'] == 'Hard' ? '+15 Poin per benar' : '+20 Poin per benar')) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Leaderboard Preview -->
        <div class="leaderboard-preview">
            <div class="leaderboard-title">🏆 TOP 5 PLAYER 🏆</div>
            <?php foreach ($topPlayers as $i => $p): ?>
            <div class="leaderboard-item rank-<?= $i+1 ?>">
                <span>#<?= $i+1 ?> <?= htmlspecialchars($p['username']) ?></span>
                <span style="color:#ffcc00"><?= number_format($p['skor_tertinggi']) ?> pts</span>
                <span>Lv.<?= $p['level_pemain'] ?></span>
            </div>
            <?php endforeach; ?>
            <div style="text-align: center; margin-top: 15px;">
                <a href="leaderboard.php" class="pixel-btn" style="font-size:8px; padding:8px 16px;">VIEW FULL LEADERBOARD →</a>
            </div>
        </div>
        
        <!-- Recent Achievements -->
        <?php if (count($recentAchievements) > 0): ?>
        <div class="leaderboard-preview" style="margin-top: 20px;">
            <div class="leaderboard-title">🏅 PRESTASI TERBARU 🏅</div>
            <div class="achievement-list">
                <?php foreach ($recentAchievements as $ach): ?>
                <div class="achievement-badge">
                    <span>🏆</span> <?= htmlspecialchars($ach['nama_achievement']) ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 15px;">
                <a href="achievement.php" class="pixel-btn" style="font-size:8px; padding:8px 16px;">LIHAT SEMUA PRESTASI →</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        function startGame(level) {
            localStorage.setItem('selectedLevel', level);
            window.location.href = 'game.php';
        }
    </script>
</body>
</html>