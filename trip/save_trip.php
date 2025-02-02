<?php
// save_trip.php
session_start();
include "../server/db_connect.php";  // Adjust path as needed

// Retrieve and validate input
$trip_name  = $_POST['trip_name'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date   = $_POST['end_date'] ?? '';
$selected_students_json = $_POST['selected_students_json'] ?? '[]';

// Convert date inputs to Unix timestamps (or your preferred format)
$start_timestamp = strtotime($start_date);
$end_timestamp   = strtotime($end_date);

// Insert into the trips table
try {
    $sql = "INSERT INTO trips (trip_name, start_date, end_date, takes)
            VALUES (:trip_name, :start_date, :end_date, :takes)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':trip_name', $trip_name, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_timestamp, PDO::PARAM_INT);
    $stmt->bindParam(':end_date', $end_timestamp, PDO::PARAM_INT);
    $stmt->bindParam(':takes', $selected_students_json, PDO::PARAM_STR);
    $stmt->execute();

    // Clear the session selections once saved
    unset($_SESSION['selected_students']);

    // Redirect or display a success message
    header("Location: trip_management.php");
    exit;
} catch (PDOException $e) {
    die("Error saving trip: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}
?>
