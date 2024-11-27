<?php

    // Start the session
    session_start();

    // Check for valid session and cookie
    if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
        header("Location: ../index.html");
        exit();
    }

    // Include the database connection
    include '../server/db_connect.php';

?>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Administer Medicaition</title>
        <link rel="stylesheet" href="/style.css">

    </head>

    <body>

        <div class="container">

            <!-- universal nav bar-->
            <div class="navbar">

                <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">

                <h1 id="med_tracker">Med Tracker</h1>

                <ul>

                    <li><a href="../dashboard/dashboard.php">Home</a></li>
                    <li><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                    <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                    <li><a href="administer.html">Log Medication</a></li>
                    <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                    <li class="logout"><a>Logout</a></li>

                </ul>

            </div>

            <?php

            // Prepare and execute the SQL query
                $sql = "SELECT student_id, first_name, last_name FROM students WHERE first_name = ? AND year = ?";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(1, $_POST['student_fname']);
                $stmt->bindParam(2, $_POST['student_yeargroup']);

                $stmt->execute();

                $result = $stmt->fetchAll();

                // Display the table and form for student selection
                echo "<div class = 'choose_student_log'>";

                    echo "<form action='add_notes.php' method='POST'>";

                        echo "<table>";

                        // Display each student with a checkbox for selection
                        foreach ($result as $row) {

                            echo "<tr>";
                            echo "<td><input type='hidden' name='sid' value='" . $row['student_id'] . "'></td>";
                            echo "<td><input type='checkbox' name='selected_students[]' value='" . $row['student_id'] . "'></td>";
                            echo "<td>First name: " . htmlspecialchars($row['first_name']) . "</td><br>";
                            echo "<td>Last name: " . htmlspecialchars($row['last_name']) . "</td>";
                        }

                        // Submit button for the form
                        echo "</table>";

                    echo "<input type='submit' name='submit' value='Select Students'>";

                    echo "</form>";

                echo "</div>";

            ?>

        </div>

    </body>

</html>