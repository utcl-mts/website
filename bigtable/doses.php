<?php
session_start();

include "../server/check_cookie_user.php";
include "../server/db_connect.php";
include "../server/audit-log.php";

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

            $staff_id = $_SESSION['staff_id']; // Fetch from POST, not SESSION
            $staff_code = $_SESSION['staff_code']; // Staff code is correctly from SESSION
            $action = "$staff_code decreased $decrement_amount for $take_id";

            logAction($conn, $staff_id, $action);


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