<?php

    // Start session to use session variables
    session_start();

    // Include database connection
    include '../server/db_connect.php';

?>

<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Log</title>
        <link rel="stylesheet" href="/style.css">
    </head>

    <body>
        <div class="container">
            <!-- universal nav bar -->
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

            <h2>Medication Log</h2>

            <table>

                <thead>

                    <tr>

                        <th>Log ID</th>
                        <th>Student ID</th>
                        <th>Staff ID</th>
                        <th>Notes</th>
                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                        try {

                            // Prepare and execute the query
                            $sql = "SELECT * FROM log ORDER BY log_id DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();

                            // Fetch all rows
                            $result = $stmt->fetchAll();

                            // Iterate through the result set and display rows
                            foreach ($result as $row) {

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['log_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date_time']) . "</td>";
                                echo "</tr>";

                            }

                        } catch (PDOException $e) {

                            // Handle any errors
                            echo "<tr><td colspan='5'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";

                        }

                    ?>

                </tbody>

            </table>

        </div>

    </body>

</html>
