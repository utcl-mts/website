<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Student Medication</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

<?php
session_start();
include "../server/db_connect.php";
include "../audit-log/audit-log.php";

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
header("refresh:5; insert_data_home.php");
echo '<br>';
echo "Successfully registered";
?>