<?php

// Include database connection
if (!file_exists("../server/db_connect.php")) {
    die("Error: db_connect.php file not found in expected directory.");
}
include "../server/db_connect.php";
include "../server/audit-log.php";

// Verify database connection
if (!$conn) {
    die("Error: Database connection failed.");
}

try {
    // Start session
    session_start();

    // Check for required POST data and sanitize inputs
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        throw new Exception("Email or password is missing.");
    }

    // Sanitize and validate email input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }

    // Raw password (will be verified against hashed password in DB)
    $password = $_POST["password"];

    // Fetch user record
    $sql = "SELECT staff_id, `group`, password, email, staff_code, archived FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $staff_id = $user['staff_id'];
        $staff_code = $user['staff_code'];
        $source = "Login";

        // Deny system account logins
        if ($user['group'] === 'system') {
            logAction($conn, $staff_id, "System account login attempt detected", $source);
            header("Location: ../index.php?error=system_account");
            exit();
        }

        // Deny archived accounts
        if ((int)$user['archived'] === 1) {
            logAction($conn, $staff_id, "Attempted to login to archived staff account", $source);
            header("Location: ../index.php?error=account_archived");
            exit();
        }

        // Password verification
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['ssnlogin'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['staff_code'] = $staff_code;
            $_SESSION['group'] = $user['group'];

            // Set secure login cookie
            if (!setcookie(
                'cookies_and_cream',
                'active',
                [
                    'expires' => time() + (5 * 60),  // 5 minutes
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            )) {
                logAction($conn, $staff_id, "Failed to set login cookie", $source);
                header("Location: ../index.php?error=cookie_error");
                exit();
            }

            // Successful login
            logAction($conn, $staff_id, "User successfully logged in", $source);
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            // Wrong password
            logAction($conn, $staff_id, "Failed login attempt with valid email", $source);
        }
    } else {
        // Email not found
        logAction($conn, 0, "Failed login attempt with invalid email", "Login");
    }

    // Redirect for invalid credentials
    header("Location: ../index.php?error=invalid_credentials");
    exit();
} catch (Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    header("Location: ../index.php?error=unknown_error");
    exit();
}

