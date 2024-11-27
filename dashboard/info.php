<?php

session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";

// Check if `takes_id` is provided
if (isset($_POST['takes_id'])) {
    $takes_id = $_POST['takes_id'];

    // Fetch the current notes from the database
    $sql = "SELECT notes FROM takes WHERE takes_id = :takes_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);
    $stmt->execute();
    $notes = $stmt->fetch(PDO::FETCH_ASSOC)['notes'] ?? '';
} else {
    die("Invalid request.");
}

// Handle form submission to update notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updated_notes'])) {
    $updated_notes = $_POST['updated_notes'];

    $update_sql = "UPDATE takes SET notes = :notes WHERE takes_id = :takes_id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindParam(':notes', $updated_notes, PDO::PARAM_STR);
    $update_stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $message = "Notes updated successfully!";
        $notes = $updated_notes; // Update notes for display
    } else {
        $message = "Error updating notes. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Notes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Edit Notes</h1>

    <!-- Display any success/error messages -->
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form to Edit Notes -->
    <form action="info.php" method="post">
        <input type="hidden" name="takes_id" value="<?php echo htmlspecialchars($takes_id); ?>">
        <textarea name="updated_notes" rows="10" cols="50"><?php echo htmlspecialchars($notes); ?></textarea>
        <br><br>
        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="dashboard.php">Go Back</a>
</div>
</body>
</html>
