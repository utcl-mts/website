<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include "server/db_connect.php";

try {
    // Start the session at the beginning
    session_start();

    // Directly retrieve input values from $_POST without filtering
    $email = $_POST['email'] ;
    $pswd = $_POST['password'];

    if ($email && $pswd) {
        // Prepare the SQL query securely
        $sql = "SELECT * FROM staff WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $password = $result["password"];

            // Verify the password
            if (password_verify($pswd, $password)) {
                // Set session variables
                $_SESSION["ssnlogin"] = true;
                $_SESSION["id"] = $result["id"];
                $_SESSION["email"] = $result["email"];

                // Log the activity after successful password verification
                try {
                    $action = "log";
                    $date_time = time(); // Use Unix timestamp for `date_time` as per SQL schema
                    $staff_id = $result["staff_id"]; // Ensure `$staff_id` matches schema naming convention

                    // SQL query for audit log insertion
                    $sql = "INSERT INTO audits (staff_id, action, date_time) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(1, $staff_id, PDO::PARAM_INT);
                    $stmt->bindParam(2, $action, PDO::PARAM_STR);
                    $stmt->bindParam(3, $date_time, PDO::PARAM_INT);
                    $stmt->execute();

                    // Set headers to prevent caching and then redirect to dashboard
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                    header("Location: dashboard/dashboard.html");
                    exit(); // Stop script execution after the redirect
                } catch (Exception $e) {
                    echo "Error logging activity: " . $e->getMessage();
                }
            } else {
                // Invalid password, destroy session and redirect
                session_destroy();
                header("Location: login.php?error=invalid_password");
                exit();
            }
        } else {
            // User not found, redirect to login page with error
            header("Location: login.php?error=user_not_found");
            exit();
        }
    } else {
        // Redirect to login page if no username or password is provided
        header("Location: login.php?error=missing_credentials");
        exit();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
