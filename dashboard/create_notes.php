<?php
session_start(); // Start the session to access session variables

include "../server/db_connect.php";

// Get student_id and takes_id from the GET request
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$takes_id = isset($_GET['takes_id']) ? intval($_GET['takes_id']) : null;

// Check if staff_code exists in the session
if (!isset($_SESSION['staff_code'])) {
    die("<p class='error'>Staff code not found. Please log in again.</p>");
}

$staff_code = $_SESSION['staff_code'];  // Get the staff_code from session

// Redirect back if the required data is missing
if (!$student_id || !$takes_id) {
    die("<p class='error'>Invalid request. Missing student or medication data.</p>");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);
    $note_date = $_POST['note_date'];  // Get the date from the form input
    $note_time = $_POST['note_time'];  // Get the time from the form input

    // Combine date and time into a single string
    $full_note_datetime = $note_date . ' ' . $note_time;

    // Validate that the user has entered a valid date and time
    if (empty($content)) {
        echo "<p class='error'>Note content cannot be empty.</p>";
    } elseif (empty($note_date) || empty($note_time)) {
        echo "<p class='error'>Please select both date and time for the note.</p>";
    } else {
        try {
            // Insert the note with the user-selected date and time, and the staff_code
            $sql = "INSERT INTO notes (takes_id, content, created_at, staff_code) 
                    VALUES (:takes_id, :content, :created_at, :staff_code)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $full_note_datetime, PDO::PARAM_STR);
            $stmt->bindParam(':staff_code', $staff_code, PDO::PARAM_STR);
            $stmt->execute();

            echo "<p class='success'>Note added successfully!</p>";
        } catch (PDOException $e) {
            die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "</p>");
        }
    }
}

include "../server/main_navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Notes</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<div>
    <h1>Create A Note</h1>
    <p>You are adding a note for Student ID: <strong><?php echo htmlspecialchars($student_id, ENT_QUOTES); ?></strong> with Takes ID: <strong><?php echo htmlspecialchars($takes_id, ENT_QUOTES); ?></strong></p>

    <form method="POST">
        <div class='text-element'>Enter the note date:</div>
        <div class='text-element-faded'>Example: 22/01/2025</div>
        <input class='temp_date_field' type="date" id="note_date" name="note_date" required>
        <br><br>

        <div class='text-element'>Enter the time:</div>
        <div class='text-element-faded'>Example: 22/01/2025</div>
        <input class='temp_time_field' type="time" id="note_time" name="note_time" required>

        <br><br>

        <div class='text-element'>Enter the note:</div>
        <div class='text-element-faded'>Example: Attempted to call mum</div>
        <textarea class='text_area' id="content" name="content" required></textarea>

        <br><br>

        <input class='submit' type="submit" value="Submit">
    </form>

    <br>
    <a class='back_link' href="../dashboard/dashboard.php" class="button"> > Go Back</a>
</div>
</body>
</html>
