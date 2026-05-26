<?php
require_once 'config/koneksi.php';

$achievements = $pdo->query("SELECT * FROM achievement ORDER BY FIELD(rarity, 'legendary', 'epic', 'rare', 'common'), id_achievement")->fetchAll();

$userAchievements = [];
if (isUserLoggedIn()) {
    $stmt = $pdo->prepare("SELECT id_achievement FROM pemain_achievement WHERE id_pemain = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userAchievements = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Achievement - Pixel Music Challenge</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        .achievement-card {
            background: #1a1a3a;
            border: 2px solid #00ffcc;
            padding: 20px;
            text-align: center;
            transition: all 0.2s;
        }
        .achievement-card.locked {
            opacity: 0.5;
            filter: grayscale(0.5);
        }
        .achievement-card.unlocked {
            border-color: #ffcc00;
            box-shadow: 0 0 15px rgba(255,204,0,0.3);
        }
        .achievement-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .achievement-name {
            color: #ff66cc;
            font-size: 10px;
            margin-bottom: 10px;
        }
        .achievement-desc {
            font-size: 8px;
            color: #aaa;
            margin-bottom: 10px;
        }
        .achievement-rarity {
            font-size: 8px;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .rarity-common { background: #666; color: white; }
        .rarity-rare { background: #3366ff; color: white; }
        .rarity-epic { background: #9933ff; color: white; }
        .rarity-legendary { background: #ff9900; color: white; }
        .badge-locked {
            position: absolute;
            background: rgba(0,0,0,0.7);
            border-radius: 50%;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; margin: 30px 0;">🏅 ACHIEVEMENT</h1>
        
        <div class="achievement-grid">
            <?php foreach ($achievements as $ach): ?>
            <?php $isUnlocked = in_array($ach['id_achievement'], $userAchievements); ?>
            <div class="achievement-card <?= $isUnlocked ? 'unlocked' : 'locked' ?>">
                <div class="achievement-icon"><?= $isUnlocked ? '🏆' : '🔒' ?></div>
                <div class="achievement-name"><?= escape($ach['nama_achievement']) ?></div>
                <div class="achievement-desc"><?= escape($ach['deskripsi']) ?></div>
                <div class="achievement-rarity rarity-<?= $ach['rarity'] ?>">
                    <?= strtoupper($ach['rarity']) ?>
                </div>
                <?php if ($isUnlocked): ?>
                    <div style="margin-top: 10px; font-size: 8px; color: #00ff66;">✓ UNLOCKED</div>
                <?php else: ?>
                    <div style="margin-top: 10px; font-size: 7px; color: #ff6666;">🔒 BELUM TERBUKA</div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-bottom: 50px;">
            <button class="pixel-btn" onclick="location.href='index.php'">← KEMBALI</button>
        </div>
    </div>
</body>
</html>