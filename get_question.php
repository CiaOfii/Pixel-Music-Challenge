<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/koneksi.php';

$level = $_GET['level'] ?? 'Pemula';
$mode = $_GET['mode'] ?? 'arcade';
$excludeIds = isset($_GET['exclude']) ? explode(',', $_GET['exclude']) : [];
$excludeIds = array_filter($excludeIds, 'is_numeric');

$difficultyMap = [
    'Pemula' => 'mudah',
    'Normal' => 'sedang',
    'Hard' => 'sulit',
    'Expert' => 'expert'
];
$difficulty = $difficultyMap[$level] ?? 'sedang';

try {
    $sql = "SELECT * FROM soal WHERE level_kesulitan = ? AND status_aktif = 1";
    $params = [$difficulty];
    
    if (!empty($excludeIds)) {
        $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
        $sql .= " AND id_soal NOT IN ($placeholders)";
        $params = array_merge($params, $excludeIds);
    }
    
    $sql .= " ORDER BY RAND() LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $soal = $stmt->fetch();
    
    if (!$soal && !empty($excludeIds)) {
        $stmt = $pdo->prepare("SELECT * FROM soal WHERE level_kesulitan = ? AND status_aktif = 1 ORDER BY RAND() LIMIT 1");
        $stmt->execute([$difficulty]);
        $soal = $stmt->fetch();
    }
    
    if (!$soal) {
        echo json_encode(['error' => 'No questions available for this level.']);
        exit;
    }
    
    echo json_encode([
        'id' => $soal['id_soal'],
        'lyric_start' => $soal['lirik_awal'],
        'option_a' => $soal['pilihan_a'],
        'option_b' => $soal['pilihan_b'],
        'option_c' => $soal['pilihan_c'],
        'option_d' => $soal['pilihan_d'],
        'correct_answer' => $soal['jawaban_benar'],
        'audio_preview' => $soal['nama_file_audio_preview'],
        'audio_full' => $soal['nama_file_audio_full'],
        'artist' => $soal['artis'],
        'year' => $soal['tahun_rilis'],
        'full_lyric_preview' => substr($soal['lirik_lanjutan'] ?? $soal['lirik_awal'], 0, 150)
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>