<?php
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] == 'admin') {
        header('Location: pages/admin/dashboard.php');
    } else {
        header('Location: pages/user/dashboard.php');
    }
    exit;
} else {
    header('Location: login.php'); // Tambahkan ini!
    exit;
}
?>
