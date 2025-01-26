<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/navbar/admin_dashboard.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<div>
    <br>
    <h1>Administrator Dashboard</h1>
    <ul class="list">
        <li class="list_li"><a class="list_li_a" href="staff_home.php">Staff Management</a></li>
        <li class="list_li"><a class="list_li_a" href="medication_management.php">Medication Management</a></li>
        <li class="list_li"><a class="list_li_a" href="brand_management.php">Brand Management</a></li>
        <li class="list_li"><a class="list_li_a" href="">Full Site Backup</a></li>
    </ul>