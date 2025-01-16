<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Hours Tracking - Upload Single</title>
</head>
<body>
<div class="container">
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

$stmt->execute();
header("refresh:5; insert_data_home.php");
echo '<br>';
echo "Successfully registered";
?>