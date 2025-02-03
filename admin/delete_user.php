<?php
session_start();

require "../server/check_cookie_admin.php";
require "../server/db_connect.php";
require "../server/audit-log.php";

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
                $staff_id = $_SESSION['staff_id'];
                $staff_code = $_SESSION['staff_code'];
                $action = "$staff_code Archived $staff_id";
                $source = "Delete User";

                logAction($conn, $staff_id, $action, $source);
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