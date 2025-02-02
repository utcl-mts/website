<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Student Medication</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">

<?php
session_start();
include "../server/db_connect.php";
include "../server/audit-log.php";
include "../server/navbar/admin_dashboard.php";
include "../server/check_cookie_admin.php";

$first_name = $_POST['first_name'];
$first_name = strtoupper($first_name);
$last_name = $_POST['last_name'];
$last_name = strtoupper($last_name);
$year = $_POST['year'];

$sql = "INSERT INTO students (first_name, last_name, year) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1,$first_name);
$stmt->bindParam(2,$last_name);
$stmt->bindParam(3,$year);

$staff_id = $_SESSION['staff_id'];
$ip_address = $_SERVER['REMOTE_ADDR'];
$action = "Student: " . $first_name . " " . $last_name. " was created";
// ID of the user performing the action
logAction($conn, $staff_id, $action);

$stmt->execute();
header("refresh:5; student_management.php");
echo '<br>';
echo '<div class="success-banner">';
    echo '<div class="success-header">';
        echo '<h2>Success</h2>';
    echo '</div>';
    echo '<div class="success-content">';
        echo '<p>Sucessfully added</p>';
    echo '</div>';
echo '</div>';
?>