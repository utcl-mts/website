<?php

session_start();

include "../server/db_connect.php";
include "../server/audit-log.php";
include "../server/check_cookie_user.php";

if (isset($_POST['takes_id'])) {
    $takes_id = $_POST['takes_id'];

    $sql = "UPDATE takes SET archived = 1 WHERE takes_id = :takes_id";
    $stat = $conn->prepare($sql);
    $stat->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);

    $staff_id = $_SESSION['staff_id'];
    $staff_code = $_SESSION['staff_code'];
    $action = "$staff_code Archived $takes_id";
    $source = "Archived";

    logAction($conn, $staff_id, $action, $source);

    if ($stat->execute()) {
        // Redirect back to the notification page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Error updating activity.";
    }
} else {
    echo "Invalid request.";
}
?>
