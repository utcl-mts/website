<?php
    // Start a new session
    session_start();
    // Include the database connection file
    include "../server/db_connect.php";
?>
    <html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Administer Medicaition</title>
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

                $sql = 'SELECT first_name from Students where student_id = ?';
                $stmt = $conn->prepare($sql);
                $stmt -> bindParam(1, $student_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $fn = $result['first_name'];

                $sql = 'SELECT last_name from Students where student_id = ?';
                $stmt = $conn->prepare($sql);
                $stmt -> bindParam(1, $student_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $ln = $result['last_name'];



                echo "<form method='post' action='add_new_med_audit.php' id='add_new_med_form'>";

                    echo "<input type='hidden' name='student_id' value='$student_id'>";
                    echo "<input type='hidden' name='first_name' value='$fn'>";
                    echo "<input type='hidden' name='last_name' value='$ln'>";

                    echo "<label for='med'> Enter medication name: </label>";
                    echo "<input type='text' id='med' placeholder='Medication Name' required>";

                    echo "<label for='brand'> Enter brand name: </label>";
                    echo "<input type='text' id='brand' PLACEHOLDER='Medication Brand' required>";

                    echo "<label for='max_dose'> Enter dose in packet: </label>";
                    echo "<input type='number' id='max_dose' required>";

                    echo "<label for='min_dose'> Enter minimum dose: </label>";
                    echo "<input type='number' id='min_dose' required>";

                    echo "<label for='expiry'> Enter expiry date: </label>";
                    echo "<input type='date' id='expiry' required>";

                    echo "<input type='submit' value='Add'>";

                echo "</form>";

            ?>

        </div>

    </body>

</html>
