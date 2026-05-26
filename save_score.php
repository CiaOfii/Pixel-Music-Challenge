<?php
header('Content-Type: application/json');
require_once '../config/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isUserLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$score = $data['score'] ?? 0;
$level = $data['level'] ?? 'Pemula';
$correct = $data['correct'] ?? 0;
$wrong = $data['wrong'] ?? 0;
$streak = $data['streak'] ?? 0;
$coinsEarned = $data['coins'] ?? 0;
$xpGained = $data['xp'] ?? 0;
$levelCleared = $data['level_cleared'] ?? false;
$unlockNext = $data['unlock_next'] ?? null;

try {
    // Save history
    $stmt = $pdo->prepare("INSERT INTO skor (id_pemain, skor, jumlah_benar, jumlah_salah, streak_terpanjang, xp_didapat, koin_didapat, mode_permainan, tanggal_main) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$userId, $score, $correct, $wrong, $streak, $xpGained, $coinsEarned, $level]);
    
    // Update user stats
    $stmt = $pdo->prepare("UPDATE pemain SET total_skor = total_skor + ?, total_main = total_main + 1, koin = koin + ?, xp = xp + ? WHERE id_pemain = ?");
    $stmt->execute([$score, $coinsEarned, $xpGained, $userId]);
    
    // Update highest score
    $stmt = $pdo->prepare("UPDATE pemain SET skor_tertinggi = GREATEST(skor_tertinggi, ?) WHERE id_pemain = ?");
    $stmt->execute([$score, $userId]);
    
    // Level up system (every 100 XP)
    $stmt = $pdo->prepare("SELECT xp, level_pemain FROM pemain WHERE id_pemain = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    $newLevel = floor($user['xp'] / 100) + 1;
    if ($newLevel > $user['level_pemain']) {
        $stmt = $pdo->prepare("UPDATE pemain SET level_pemain = ? WHERE id_pemain = ?");
        $stmt->execute([$newLevel, $userId]);
        $_SESSION['level'] = $newLevel;
    }
    
// Unlock new level if cleared
$unlockedNewLevel = null;
if ($levelCleared && $unlockNext) {
    $stmt = $pdo->prepare("SELECT unlocked_levels FROM pemain WHERE id_pemain = ?");
    $stmt->execute([$userId]);
    $currentUnlocked = json_decode($stmt->fetchColumn(), true);
    
    if (!in_array($unlockNext, $currentUnlocked)) {
        $currentUnlocked[] = $unlockNext;
        $stmt = $pdo->prepare("UPDATE pemain SET unlocked_levels = ? WHERE id_pemain = ?");
        $stmt->execute([json_encode($currentUnlocked), $userId]);
        $unlockedNewLevel = $unlockNext;
    }
}
    
    // Update session coins
    $stmt = $pdo->prepare("SELECT koin FROM pemain WHERE id_pemain = ?");
    $stmt->execute([$userId]);
    $_SESSION['coins'] = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'xp_gained' => $xpGained,
        'coins_gained' => $coinsEarned,
        'unlocked_new_level' => $unlockedNewLevel
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>