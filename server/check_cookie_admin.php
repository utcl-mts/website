<?php
// Check for valid session and cookie
    if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream']) || $_SESSION['group'] !== 'admin') {
        header("Location: ../index.php?error=no_access");
        exit();
    }
?>