<?php

    // Start a new session
    session_start();

    // Check if the user is logged in (example check, adapt to your app)
    if (!isset($_SESSION['user_id'])) {

        header("Location: ../login.php");
        exit();
    }

    // Include the database connection file
    include "../server/db_connect.php";

    try {

        // Sanitize POST inputs
        $sid = htmlspecialchars($_POST['student_id']);
        $max_dose = htmlspecialchars($_POST['max_dose']);
        $min_dose = htmlspecialchars($_POST['min_dose']);
        $expiry = htmlspecialchars($_POST['expiry']);
        $med = htmlspecialchars($_POST['meds']);
        $brand = htmlspecialchars($_POST['brand']);
        $strength = htmlspecialchars($_POST['strength']);

        // Prepare the SQL query with explicit column names
        $sql = "INSERT INTO takes (student_id, max_dose, min_dose, expiry, med_name, brand_name, strength) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(1, $sid, PDO::PARAM_STR);
        $stmt->bindParam(2, $max_dose, PDO::PARAM_STR);
        $stmt->bindParam(3, $min_dose, PDO::PARAM_STR);
        $stmt->bindParam(4, $expiry, PDO::PARAM_STR);
        $stmt->bindParam(5, $med, PDO::PARAM_STR);
        $stmt->bindParam(6, $brand, PDO::PARAM_STR);
        $stmt->bindParam(7, $strength, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {

            echo "Record successfully added!";

        } else {

            echo "Error adding record. Please try again.";
        }

    } catch (PDOException $e) {

        // Handle database errors
        echo "Database error: " . $e->getMessage();

    } catch (Exception $e) {

        // Handle general errors
        echo "An unexpected error occurred: " . $e->getMessage();
    }

?>