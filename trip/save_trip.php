<?php
// save_trip.php
session_start();
require "../server/db_connect.php";  // Adjust path as needed
require "../server/audit-log.php";  // Adjust path as needed


// Retrieve and validate input
$trip_name  = trim($_POST['trip_name'] ?? '');
$start_date = trim($_POST['start_date'] ?? '');
$end_date   = trim($_POST['end_date'] ?? '');
$selected_students_json = $_POST['selected_students_json'] ?? '[]';

// Convert date inputs to Unix timestamps
$start_timestamp = strtotime($start_date);
$end_timestamp   = strtotime($end_date);

// Decode the selected students JSON and count them
$selected_students = json_decode($selected_students_json, true);
$student_count = is_array($selected_students) ? count($selected_students) : 0;

if (empty($trip_name) || !$start_timestamp || !$end_timestamp) {
    die("Error: Missing required fields or invalid date format.");
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO trips (trip_name, start_date, end_date, takes)
            VALUES (:trip_name, :start_date, :end_date, :takes)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':trip_name', $trip_name, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_timestamp, PDO::PARAM_INT);
    $stmt->bindParam(':end_date', $end_timestamp, PDO::PARAM_INT);
    $stmt->bindParam(':takes', $selected_students_json, PDO::PARAM_STR);
    $stmt->execute();

    $staff_id = $_SESSION['staff_id'];
    $staff_code = $_SESSION['staff_code'];
    $action = "$staff_code created $trip_name, $start_date, $end_date, $student_count";
    $source = "Created Trip";

    logAction($conn, $staff_id, $action, $source);

    // Clear the session selections once saved
    unset($_SESSION['selected_students']);

    // Redirect or display a success message
    header("Location: trip_management.php?success=1");
    exit;
} catch (PDOException $e) {
    die("Error saving trip: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}
?>
