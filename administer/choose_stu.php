<?php

    // Start the session
    session_start();

    // Include the database connection
    include '../server/db_connect.php';

    // Prepare and execute the SQL query
    $sql = "SELECT student_id, first_name, last_name FROM students WHERE first_name = ? AND year = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(1, $_POST['student_fname']);
    $stmt->bindParam(2, $_POST['student_yeargroup']);

    $stmt->execute();

    $result = $stmt->fetchAll();

    // Display the table and form for student selection
    echo "<form action='choose_med.php' method='POST'>";
    echo "<table>";

    // Display each student with a checkbox for selection
    foreach ($result as $row) {

        echo "<tr>";
        echo "<td><input type='hidden' name='sid' value='" . $row['student_id'] . "'></td>";
        echo "<td><input type='checkbox' name='selected_students[]' value='" . $row['student_id'] . "'></td>";
        echo "<td>First name: " . htmlspecialchars($row['first_name']) . "</td>";
        echo "<td>  Last name: " . htmlspecialchars($row['last_name']) . "</td>";
        echo "</tr>";
    }

    // Submit button for the form
    echo "</table>";
    echo "<input type='submit' name='submit' value='Select Students'>";
    echo "</form>";

?>