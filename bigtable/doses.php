<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $take_id = intval($_POST['take_id']); // Get the `take_id` from the form
    $decrement_amount = intval($_POST['decrement_amount']); // Get the decrement amount

    try {
        // Check the current doses
        $check_sql = "SELECT current_dose FROM takes WHERE takes_id = :take_id";
        $stmt = $conn->prepare($check_sql);
        $stmt->bindParam(':take_id', $take_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['current_dose'] >= $decrement_amount) {
            // Decrement the dose count
            $update_sql = "UPDATE takes SET current_dose = current_dose - :decrement_amount WHERE takes_id = :take_id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':take_id', $take_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':decrement_amount', $decrement_amount, PDO::PARAM_INT);
            $update_stmt->execute();
            
            // Redirect back to the main page with a success message
            header("Location: bigtable.php?success=1");
            exit;
        } else {
            // Redirect back with an error message
            header("Location: bigtable.php?error=1");
            exit;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>