<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>CSV Upload Results</title>
</head>
<body>
<div class="container">
    <!-- Navbar -->
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data.html">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a href="../logout.php">Logout</a></li>
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
header("refresh:5; insert_data.html");
echo '<br>';
echo "Successfully registered";
?>