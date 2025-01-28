<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.php");
    exit();
}

include "../server/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Debugging: Check if staff_id is received
        if (!isset($_POST['staff_id'])) {
            throw new Exception("Staff ID not provided.");
        }

        $staff_id = $_POST['staff_id'];

        // Debugging: Check if staff_id is valid
        if (!is_numeric($staff_id)) {
            throw new Exception("Invalid Staff ID.");
        }

        // Update the archive field to 1 instead of deleting the user
        $query = "UPDATE staff SET archived = 1 WHERE staff_id = :staff_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                echo "User archived successfully.";
            } else {
                echo "No user found with the provided Staff ID.";
            }
        } else {
            echo "Failed to archive user.";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }

    // Redirect only if the operation was successful
    if (isset($stmt) && $stmt->rowCount() > 0) {
        header("Location: staff_home.php");
        exit;
    }
}
?>