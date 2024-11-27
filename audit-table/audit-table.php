<?php

    // Start session to use session variables
    session_start();

    // Check for valid session and cookie
    if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['user_session'])) {
        header("Location: ../index.html");
        exit();
    }

    // Include database connection
    include '../server/db_connect.php';

?>
    <html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Audit Table</title>
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
                <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
                <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <li><a href="../administer/administer.html">Administer Medication</a></li>
                <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                <li class="logout"><a>Logout</a></li>

            </ul>

        </div>

    </div>

    </body>

    </html>

<?php
