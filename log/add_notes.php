<?php

// Start session to use session variables
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

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

            <!-- universal nav bar-->
            <div class="navbar">

                <img id="logo" src="../assets/images/utcleeds.svg" alt="UTC Leeds">

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

        </div>

    </body>

</html>

<?php

    echo "<form action='log.php' method='POST'>";

        echo "<table>";

            // Hidden field to pass student_id
            echo "<td><input type='hidden' name='sid' value='" . $_POST['sid'] . "'></td>";

            // Date input field of each medication
            echo "<label for='log_date'>Select Date: </label>";
            echo "<input type='date' id='log_date' name='log_date' >";

            // Time input field of each medication
            echo "<label for='log_time' >Select Time: </label>";
            echo "<input type='time' id='log_time' name='log_time' >";

            // Notes input for log
            echo "<label for='log_notes' >Enter Notes: </label>";
            echo"<input type='text' id='log_notes' name='log_notes' >";

        // Submit button for the form
        echo "</table>";

        echo "<input type='submit' name='submit' value='Submit notes'>";

    echo "</form>";

        echo "</table>";

    echo "</form>";

?>