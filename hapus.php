<?php
require_once '../config/koneksi.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM soal WHERE id_soal = ?");
$stmt->execute([$id]);

header('Location: dashboard.php');
exit;
?>