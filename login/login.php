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

// Helper function to log actions
function logAction($conn, $staff_id, $action) {
    try {
        $ip_address = $_SERVER['REMOTE_ADDR']; // Capture the IP address
        $action_with_ip = "$action, IP: $ip_address"; // Append IP address to the action
        $log_sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (:staff_id, :act, :date_time)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->execute([
            'staff_id' => $staff_id,
            'act' => $action_with_ip,
            'date_time' => time()
        ]);
    } catch (PDOException $e) {
        error_log("Failed to log action: " . $e->getMessage());
    }
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

    // First check if the email exists and get the staff details
    $sql = "SELECT staff_id, `group`, password, email FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user exists and is a system account, log the attempt and deny access
    if ($user && $user['group'] === 'system') {
        logAction($conn, $user['staff_id'], 'System account login attempt detected');
        header("Location: login.php?error=system_account");
        exit();
    }

    // Normal login process continues for non-system accounts
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['staff_id'] = $user['staff_id'];
        $_SESSION["ssnlogin"] = true;
        $_SESSION["email"] = $user["email"];

        // Add cookie setting here
        setcookie(
            'cookies_and_cream',
            'active',
            [
                'expires' => time() + (2 * 60),  // 2 minutes
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );

        // Log successful login attempt
        logAction($conn, $user['staff_id'], 'User successfully logged in');

        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        // Log failed login attempt if user exists
        if ($user) {
            logAction($conn, $user['staff_id'], 'Failed login attempt with valid email');
        } else {
            logAction($conn, 0, 'Failed login attempt with invalid email');
        }

        header("Location: login.php?error=invalid_credentials");
        exit();
    }
    exit();
} catch (Exception $e) {
    // Log error for debugging (to a file or error handling system)
    error_log("Login Error: " . $e->getMessage());
    // Redirect to login page in case of an error
    header("Location: ../index.html");
    exit();
}