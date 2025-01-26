<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $staff_id = $_POST['staff_id'];

        // Delete user securely
        $query = "DELETE FROM staff WHERE staff_id = :staff_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Failed to delete user.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }

    header("Location: staff_home.php");
    exit;
}
?>
