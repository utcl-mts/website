<?php
include "../server/db_connect.php";

// Get student_id and takes_id from the GET request
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$takes_id = isset($_GET['takes_id']) ? intval($_GET['takes_id']) : null;

// Validate the inputs
if (!$student_id || !$takes_id) {
    die("<p class='error'>Invalid request. Missing student or medication data.</p>");
}

try {
    // Query to fetch notes along with staff_code for the specified student and takes_id
    $sql = "SELECT notes.note_id, notes.content, notes.created_at, 
                   students.first_name, students.last_name, 
                   med.med_name, notes.staff_code
            FROM notes
            INNER JOIN takes ON notes.takes_id = takes.takes_id
            INNER JOIN students ON takes.student_id = students.student_id
            INNER JOIN med ON takes.med_id = med.med_id
            WHERE takes.takes_id = :takes_id AND students.student_id = :student_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();

    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Notes for " . htmlspecialchars($notes[0]['first_name'], ENT_QUOTES) . " " . htmlspecialchars($notes[0]['last_name'], ENT_QUOTES) . "</h1>";
    echo "<h2>Medication: " . htmlspecialchars($notes[0]['med_name'], ENT_QUOTES) . "</h2>";

    if ($notes) {
        echo "<table class='notes_table'>";
        echo "<tr><th>Staff Code</th><th>Content</th><th>Created At</th></tr>";

        foreach ($notes as $note) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($note['staff_code'], ENT_QUOTES) . "</td>";  // Display the staff_code from notes table
            echo "<td>" . htmlspecialchars($note['content'], ENT_QUOTES) . "</td>";
            echo "<td>" . htmlspecialchars(date('d/m/Y H:i', strtotime($note['created_at'])), ENT_QUOTES) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No notes found for this student and medication.</p>";
    }
} catch (PDOException $e) {
    die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "</p>");
}
?>
