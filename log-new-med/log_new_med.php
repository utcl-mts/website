<?php

    // Start a new session or connect to an existing one
    session_start();

    // Include the database connection file
    include "../server/db_connect.php";

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Administer Medication</title>
        <link rel="stylesheet" href="../style.css">

    </head>

    <body>

        <div class="container">

            <!-- Universal navbar -->
            <div class="navbar">

                <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
                <h1 id="med_tracker">Med Tracker</h1>

                <ul>

                    <li><a href="../dashboard/dashboard.php">Home</a></li>
                    <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
                    <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                    <li><a href="administer.html">Log Medication</a></li>
                    <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                    <li class="logout"><a href="../logout.php">Logout</a></li>

                </ul>

            </div>

            <?php
                // Check if student_id is provided
                if (isset($_POST['student_id'])) {
                    $student_id = htmlspecialchars($_POST['student_id']); // Sanitize input

                    // Fetch student details
                    try {

                        $sql = 'SELECT first_name, last_name FROM Students WHERE student_id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {

                            $fn = htmlspecialchars($result['first_name']); //Used htmlspecialchars to sanitize inputs.
                            $ln = htmlspecialchars($result['last_name']);

                        } else {

                            echo "<p>Student not found.</p>";
                            exit();
                        }

                    } catch (PDOException $e) {

                        echo "<p>Error fetching student details: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    // Fetch medication names
                    try {

                        $sql = "SELECT med_name FROM med";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $meds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {

                        echo "<p>Error fetching medications: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    // Fetch brand names
                    try {

                        $sql = "SELECT brand_name FROM brand";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    } catch (PDOException $e) {

                        echo "<p>Error fetching brands: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    echo "<form method='post' action='log_new_med_audit.php' id='add_new_med_form'>";

                    echo "<input type='hidden' name='student_id' value='$student_id'>";

                    echo "<label for='meds'>Medication:</label>";
                    echo "<select name='meds' id='meds' required>";
                    echo "<option value=''>Select a Med</option>";

                    foreach ($meds as $med) {

                        echo "<option value='" . htmlspecialchars($med['med_name']) . "'>" . htmlspecialchars($med['med_name']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='brand'>Brand:</label>";
                    echo "<select name='brand' id='brand' required>";
                    echo "<option value=''>Select a Brand</option>";
                    foreach ($brands as $brand) {

                        echo "<option value='" . htmlspecialchars($brand['brand_name']) . "'>" . htmlspecialchars($brand['brand_name']) . "</option>";
                    }
                    echo "</select>";

                    echo "<label for='max_dose'>Enter dose in packet:</label>";
                    echo "<input type='number' name='max_dose' id='max_dose' min='0' required>";

                    echo "<label for='min_dose'>Enter minimum dose:</label>";
                    echo "<input type='number' name='min_dose' id='min_dose' min='0' required>";

                    echo "<label for='expiry'>Enter expiry date:</label>";
                    echo "<input type='date' name='expiry' id='expiry' required>";

                    echo "<label for='strength'>Strength:</label>";
                    echo "<input type='number' name='strength' id='strength' min='0' required>";

                    echo "<input type='submit' value='Log'>";

                    echo "</form>";

                } else {

                    echo "<p>No student ID provided.</p>";
                }

            ?>

        </div>

    </body>

</html>