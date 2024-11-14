<?php

// Include database connection
if (!file_exists("../server/db_connect.php")) {
    die("Error: db_connect.php file not found in expected directory.");
}
include "../server/db_connect.php";

// Verify database connection
if (!$conn) {
    die("Error: Database connection failed.");
}

try {
    // Start session
    session_start();

    // Check for required POST data and sanitize inputs
    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        throw new Exception("Email or password is missing.");
    }
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare SQL query to find user by email
    $sql = "SELECT * FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Define default action and timestamp for audit logging
    $date_time = time();
    $action = "Fail_Log"; // Set default action to failed login
    $staff_id = $user ? $user["staff_id"] : 1;

    // Check if user exists and verify password (no hashing)
    if ($user && $password === $user["password"]) {
        // Credentials are correct; log successful login
        $_SESSION["ssnlogin"] = true;
        $_SESSION["staff_id"] = $user["staff_id"];
        $_SESSION["email"] = $user["email"];

        $action = "Succ_Log"; // Update action to indicate successful login

        // Insert successful login into audit log
        $log_sql = "INSERT INTO audit (staff_id, act, date_time) VALUES (:staff_id, :action, :date_time)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
        $log_stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $log_stmt->bindParam(':date_time', $date_time, PDO::PARAM_INT);

        if (!$log_stmt->execute()) {
            throw new Exception("Failed to insert successful login audit log: " . implode(", ", $log_stmt->errorInfo()));
        }

        // Redirect to dashboard for successful login
        header("Location: ../dashboard/dashboard.html");
    } else {
        // Credentials are incorrect; log failed login attempt
        $staff_id = $staff_id ?? 1; // Use 1 if staff_id is null

        $fail_log_sql = "INSERT INTO audit (staff_id, act, date_time) VALUES (:staff_id, :action, :date_time)";
        $fail_log_stmt = $conn->prepare($fail_log_sql);
        $fail_log_stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
        $fail_log_stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $fail_log_stmt->bindParam(':date_time', $date_time, PDO::PARAM_INT);

        if (!$fail_log_stmt->execute()) {
            throw new Exception("Failed to insert failed login audit log: " . implode(", ", $fail_log_stmt->errorInfo()));
        }

        // Redirect to login page for failed login
        header("Location: ../index.html");
    }
    exit();
} catch (Exception $e) {
    // Log error for debugging (to a file or error handling system)
    error_log("Login Error: " . $e->getMessage());
    // Redirect to login page in case of an error
    header("Location: ../index.html");
    exit();
}
