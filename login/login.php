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
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $pswd = $_POST['password'];

    if ($email && $pswd) {
        // Prepare the SQL query securely
        $sql = "SELECT * FROM staff WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hashed_password = $result["password"];

            // Verify password
            if (password_verify($pswd, $hashed_password)) {
                // Set session variables
                $_SESSION["ssnlogin"] = true;
                $_SESSION["staff_id"] = $result["staff_id"];
                $_SESSION["email"] = $result["email"];

                // Log successful login
                $action = "log";
                $date_time = date("Y-m-d H:i:s");
                $staff_id = $result["staff_id"];

                // Audit log insertion
                $log_sql = "INSERT INTO audits (staff_id, action, date_time) VALUES (:staff_id, :action, :date_time)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
                $log_stmt->bindParam(':action', $action, PDO::PARAM_STR);
                $log_stmt->bindParam(':date_time', $date_time, PDO::PARAM_STR);
                $log_stmt->execute();

                // Redirect to dashboard
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Location: dashboard/dashboard.html");
                exit();
            } else {
                // Incorrect password
                session_destroy();
                header("Location: login.php?error=invalid_password");
                exit();
            }
        } else {
            // User not found
            header("Location: login.php?error=user_not_found");
            exit();
        }
    } else {
        // Missing credentials
        header("Location: login.php?error=missing_credentials");
        exit();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
