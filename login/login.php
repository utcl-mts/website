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

    // First check if the email exists and get the staff details
    $sql = "SELECT staff_id, `group`, password, email FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user exists and is a system account, log the attempt and deny access
    if ($user && $user['group'] === 'system') {
        // Log the system account login attempt
        $log_sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (:staff_id, 'System_Account_Login_Attempt', :date_time)";
        try {
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->execute([
                'staff_id' => $user['staff_id'],
                'date_time' => time()
            ]);
        } catch (PDOException $e) {
            error_log("Failed to log system account attempt: " . $e->getMessage());
        }

        header("Location: login.php?error=system_account");
        exit();
    }

    // Normal login process continues for non-system accounts
    if ($user && $password === $user['password']) {
        $_SESSION['staff_id'] = $user['staff_id'];
        $_SESSION["ssnlogin"] = true;
        $_SESSION["email"] = $user["email"];

        // Add cookie setting here
        setcookie(
            'cookies_and_cream',
            'active',
            [
                'expires' => time() + (2 *  3000000),  // 2 minutes
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );

        // Log successful login attempt
        try {
            $log_sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (:staff_id, 'Succ_Log', :date_time)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->execute([
                'staff_id' => $user['staff_id'],
                'date_time' => time()
            ]);
        } catch (PDOException $e) {
            error_log("Failed to log successful login: " . $e->getMessage());
        }

        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        // Log failed login attempt if user exists
        if ($user) {
            try {
                $log_sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (:staff_id, 'Failed_Log', :date_time)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->execute([
                    'staff_id' => $user['staff_id'],
                    'date_time' => time()
                ]);
            } catch (PDOException $e) {
                error_log("Failed to log failed login attempt: " . $e->getMessage());
            }
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
