<?php

    // Start session to use session variables if needed
    session_start();

    // Include database connection
    include '../server/db_connect.php';

    // Get information from the form on the HTML page
    $dose = $_POST['dose'];
    $staff_code = $_POST['staff_code'];
    $time = $_POST['time'];

    try {
        // Prepare SQL statement to insert information into the 'administer' table
        $sql = "INSERT INTO administer (staff_code, date_time, dose_given) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(1, $staff_code);
        $stmt->bindParam(2, $time);
        $stmt->bindParam(3, $dose);

        // Execute the statement
        if($stmt->execute()) {
            echo "Data successfully inserted!";
        } else {
            echo "Error inserting data.";
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

?>