<?php
session_start();
session_destroy();

// Hapus cookie remember me
setcookie('remember_username', '', time() - 3600, '/');

header('Location: login.php');
exit;
?>