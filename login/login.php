<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
if (!file_exists("../server/db_connect.php")) {
    die("Error: db_connect.php file not found in expected directory.");
}
include "../server/db_connect.php";

// Verify database connection
if (!isset($conn)) {
    die("Error: Database connection not established.");
}

try {
    // Start session
    session_start();

    // Retrieve and sanitize input
    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        throw new Exception("Error: Email or password is missing from POST data.");
    }

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare the SQL query securely, using parameterized queries
    $sql = "SELECT * FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password matches
    if ($result && $password == $result["password"]) {
        $_SESSION["ssnlogin"] = true;
        $_SESSION["staff_id"] = $result["staff_id"];
        $_SESSION["email"] = $result["email"];
        $action = "Succ_Log";
        $staff_id = $result["staff_id"];  // Use valid staff_id for success logs
    } else {
        // Failed login attempt
        $action = "Fail_Log";
        $staff_id = 69; // Set to 60 temp fix
    }

    // Insert audit log for both success and failure
    $log_sql = "INSERT INTO audit (staff_id, act, date_time) VALUES (?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bindValue(1, $staff_id, PDO::PARAM_INT); // NULL for failed logins
    $log_stmt->bindValue(2, $action, PDO::PARAM_STR);
    $log_stmt->bindValue(3, time(), PDO::PARAM_INT); // Log time in epoch format

    if ($log_stmt->execute()) {
        // Redirect based on login success or failure
        if ($action == "Succ_Log") {
            header("Location: ../dashboard/dashboard.html");
            exit();
        } else {
            // If failed log, redirect to login page
            header("Location: ../index.html");
            exit();
        }
    } else {
        throw new Exception("Failed to insert audit log.");
    }
} catch (Exception $e) {
    // Handle errors gracefully, consider logging exceptions for security analysis
    echo "Error: " . htmlspecialchars($e->getMessage());
}
