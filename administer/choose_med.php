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
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Hours Tracking - Choose Medication</title>
        <link rel="stylesheet" href="../assets/style/style.css">

    </head>

    <body class="full_page_styling">
    <div>
<div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Create Notes</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../student_profile/student_profile.php">Student Profile</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

        <h1>Select a Student</h1>
    </div>
    </body>
    </html>


<?php

    // SQL query to fetch medication name and ID for a specific student
    $sql = 'SELECT med.med_id, med.med_name FROM med JOIN takes ON med.med_id = takes.med_id WHERE takes.student_id = ?';

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameter for student ID from POST data
    $stmt->bindParam(1, $_POST['sid']);

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $result = $stmt->fetchAll();

    // Display the form and table for medication selection
    echo "<form action='administer.php' method='POST'>";

        echo "<table>";

            // Display each medication with a checkbox and dose input for selection
            foreach ($result as $row) {

                echo "<tr>";

                    // Hidden field to pass student_id
                    echo "<td><input type='hidden' name='sid' value='" . $_POST['sid'] . "'></td>";

                    // Hidden field to pass med_id
                    echo "<td><input type='hidden' name='med[]' value='" . $row['med_id'] . "'></td>";

                    // Hidden field to pass staff_code
                    echo "<td><input type='hidden' name='staff_code' value='" . $_POST['staff_code'] . "'></td>";

                    // Checkbox to select what meds have been taken
                    echo "<td><input type='checkbox' name='selected_medications[]' value='" . $row['med_id'] . "'></td>";
                    echo "<td>Medication name: " . $row['med_name'] . "</td>";

                    echo "<td>";

                        // Input field for dose of each medication
                        echo "<label for='dose" . $row['med_id'] . "'>Enter dose: </label>";
                        echo "<input type='number' id='dose" . $row['med_id'] . "' name='dose' placeholder='Enter dose'>";
;
                        // Date input field of each medication
                        echo "<label for='admin_date'" . $row['med_id'] ." >Select Date: </label>";
                        echo "<input type='date' id='admin_date' name='date' >";

                        // Time input field of each medication
                        echo "<label for='admin_time'" . $row['med_id'] ." >Select Time: </label>";
                        echo "<input type='time' id='admin_time' name='time' >";

                    echo "</td>";

                echo "</tr>";

            }

        // Submit button for the form
        echo "</table>";

        echo "<input class='submit' type='submit' name='submit' value='Select Medications'>";

    echo "</form>";

?>