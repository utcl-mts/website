<?php

    // Start a new session or connect to existing
    session_start();
    // Include the database connection file
    include "../server/db_connect.php";

?>

<html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Administer Medication</title>
        <link rel="stylesheet" href="../style.css">

    </head>

    <body>

        <div class="container">

            <!-- universal nav bar-->
            <div class="navbar">

                <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
                <h1 id="med_tracker">Med Tracker</h1>

                <ul>

                    <li><a href="../dashboard/dashboard.php">Home</a></li>
                    <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
                    <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                    <li><a href="administer.html">Log Medication</a></li>
                    <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                    <li class="logout"><a>Logout</a></li>

                </ul>

            </div>

            <?php

                $student_id = $_POST['student_id'];

                // Fetch student details
                $sql = 'SELECT first_name, last_name from Students where student_id = ?';
                $stmt = $conn->prepare($sql);
                $stmt -> bindParam(1, $student_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $fn = $result['first_name'];
                $ln = $result['last_name'];

                // Fetch medication names
                $sql = "SELECT med_name FROM med";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $meds = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all rows

                // Fetch brand names
                $sql = "SELECT brand_name FROM brand";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all rows

                echo "<form method='post' action='log_new_med_audit.php' id='add_new_med_form'>";

                    echo "<input type='hidden' name='student_id' value='$student_id'>";

                    echo "<label for='meds'>Medication:</label>";

                    echo "<select name='meds' id='meds'>";

                        echo "<option>Select a Med</option>";
                        foreach ($meds as $med) {
                            echo "<option value='" . $med['med_name'] . "'>" . $med['med_name'] . "</option>";
                        }

                    echo "</select>";

                        echo "<label for='brand'>Brand:</label>";

                        echo "<select name='brand' id='brand'>";

                        echo "<option>Select a Brand</option>";
                        foreach ($brands as $brand) {
                            echo "<option value='" . $brand['brand_name'] . "'>" . $brand['brand_name'] . "</option>";
                        }

                    echo "</select>";

                    echo "<label for='max_dose'>Enter dose in packet:</label>";
                    echo "<input type='number' name='max_dose' id='max_dose' required>";

                    echo "<label for='min_dose'>Enter minimum dose:</label>";
                    echo "<input type='number' name='min_dose' id='min_dose' required>";

                    echo "<label for='expiry'>Enter expiry date:</label>";
                    echo "<input type='date' name='expiry' id='expiry' required>";

                    echo "label for='strength'>Strength:</label>";
                    echo"<input type='number' name='strength' id='strength' required>";

                    echo "<input type='submit' value='Log'>";

                echo "</form>";

            ?>

        </div>

    </body>

</html>