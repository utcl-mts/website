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
    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        throw new Exception("Email or password is missing.");
    }

    // Sanitize email input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }

    // Sanitize password input
    $password = $_POST["password"]; // Password will be hashed, no need to sanitize

    // First check if the email exists and get the staff details
    $sql = "SELECT staff_id, `group`, password, email, staff_code, archived FROM staff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user exists and is a system account, log the attempt and deny access
    if ($user && $user['group'] === 'system') {
        $source = "Login";
        $staff_id = $user['staff_id']; // Fetch from POST, not SESSION
        $staff_code = $user['staff_code']; // Staff code is correctly from SESSION
        $action = "System account login attempt detected";

        logAction($conn, $staff_id, $action, $source);
        header("Location: ../index.php?error=system_account");
        exit();
    }

    // Check if the account is archived
    if ($user && $user['archived'] == 1) {
        $source = "Login";
        $staff_id = $user['staff_id']; // Fetch from POST, not SESSION
        $staff_code = $user['staff_code']; // Staff code is correctly from SESSION
        $action = "Attempted to login to archived staff account";

        logAction($conn, $staff_id, $action, $source);
//        logAction($conn, $user['staff_id'], 'Attempted login to archived account');
        header("Location: ../index.php?error=account_archived");
        exit();
    }

    // Normal login process continues for non-system accounts
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['staff_id'] = $user['staff_id'];
        $_SESSION["ssnlogin"] = true;
        $_SESSION["email"] = $user["email"];
        $_SESSION["staff_code"] = $user["staff_code"];  // Store staff_code in session
        $_SESSION["group"] = $user["group"]; // Store group in session

        // Set login cookie
        if (!setcookie(
            'cookies_and_cream',
            'active',
            [
                'expires' => time() + (10000 * 60),  // 5 minutes
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        )) {
            // Log failed cookie setting
            $source = "Login";
            $staff_id = $user['staff_id']; // Fetch from POST, not SESSION
            $staff_code = $user['staff_code']; // Staff code is correctly from SESSION
            $action = "Failed to set login cookie.";

            logAction($conn, $staff_id, $action, $source);
//            logAction($conn, $user['staff_id'], 'Failed to set login cookie');
            header("Location: ../index.php?error=cookie_error");
            exit();
        }

        // Log successful login attempt
        $source = "Login";
        $staff_id = $user['staff_id']; // Fetch from POST, not SESSION
        $staff_code = $user['staff_code']; // Staff code is correctly from SESSION
        $action = "User successfully logged in";

        logAction($conn, $staff_id, $action, $source);
//        logAction($conn, $user['staff_id'], 'User successfully logged in');

        // Redirect to dashboard
        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        // Log failed login attempt if user exists
        if ($user) {
            logAction($conn, $user['staff_id'], 'Failed login attempt with valid email');
        } else {
            logAction($conn, 0, 'Failed login attempt with invalid email');
        }

        // Redirect with invalid credentials error
        header("Location: ../index.php?error=invalid_credentials");
        exit();
    }
} catch (Exception $e) {
    // Log error for debugging (to a file or error handling system)
    error_log("Login Error: " . $e->getMessage());
    // Redirect to login page in case of an error
    header("Location: ../index.php?error=unknown_error");
    exit();
}