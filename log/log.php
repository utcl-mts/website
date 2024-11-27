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

        </div>

    </body>

</html>

<?php

    // Session staff id variable from login
    $staff_id = $_SESSION['staff_id'];

    // Get information from the form on the HTML page
    $date = $_POST["log_date"];
    $time = $_POST["log_time"];
    $notes = $_POST["log_notes"];
    $stu_id = $_POST["sid"];

    try{

        // Combine the date and time into a single string
        $date_time_str = $date . ' ' . $time;

        // Convert the combined date and time string to a Unix timestamp (epoch time)
        $date_time_epoch = strtotime($date_time_str);

        // SQL statement to insert into the log database
        $sql = "INSERT INTO log (student_id, staff_id, notes, date_time ) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to prevent SQL injection
        $stmt ->bindParam(1, $stu_id);
        $stmt ->bindParam(2, $staff_id);
        $stmt ->bindParam(3, $notes);
        $stmt ->bindParam(4, $date_time_epoch);

        // Execute the statement
        if($stmt->execute()) {
            echo "Data successfully inserted!";
            echo "";
        } else {
            echo "Error inserting data.";
            echo "";
        }

    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

?>