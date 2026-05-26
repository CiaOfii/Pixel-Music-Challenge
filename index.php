<?php
require_once 'config/koneksi.php';

if (isUserLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
?>