<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['user_session'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $take_id = intval($_POST['take_id']); // Get the `take_id` from the form

    try {
        // Check the current doses
        $check_sql = "SELECT doses FROM takes WHERE take_id = :take_id";
        $stmt = $conn->prepare($check_sql);
        $stmt->bindParam(':take_id', $take_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['doses'] > 0) {
            // Decrement the dose count
            $update_sql = "UPDATE takes SET doses = doses - 1 WHERE take_id = :take_id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':take_id', $take_id, PDO::PARAM_INT);
            $update_stmt->execute();
            header("Location: bigtable.php"); // Redirect back to the main page
            exit;
        } else {
            echo "Cannot decrement: doses are already zero.";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
