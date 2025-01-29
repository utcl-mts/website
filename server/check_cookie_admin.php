<?php
// Check for valid session and cookie
        if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
            header("Location: ../index.php?error=cookie_error");
            exit();
        }

?>