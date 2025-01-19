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
        <title>Hours Tracking - Add Notes</title>
        <link rel="stylesheet" href="../assets/style/style.css">

    </head>

    <body class="full_page_styling">
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../student_profile/student_profile.php">Student Profile</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>
    </body>
    </html>

<?php
    echo "<h1>Add Notes</h1>";
    echo "<form action='log.php' method='POST'>";
        echo "<td><input type='hidden' name='sid' value='" . $_POST['sid'] . "'></td>";

            ### TODO: Discuss using https://design-system.service.gov.uk/patterns/dates/ this date format instead and try and change the logic to still work with epoch -- James
            ### Still going to style it to match the style for the rest of the site but might be changed
            ### Working Example, https://github.com/SilentSmeary/hours-tracking/blob/main/student/insert_log.php
            echo "<div class='text-element'>Enter date</div>
                  <div class='text-element-faded'>Example: 12/05/2025</div>";
            echo "<input class='temp_date_field' type='date' id='log_date' name='log_date' >";
            echo "<br><br>";
            // Time input field of each medication
            echo "<div class='text-element'>Enter time</div>
                  <div class='text-element-faded'>Example: 12/05/2025</div>";
            echo "<input class='temp_time_field' type='time' id='log_time' name='log_time' >";
            echo "<br><br>";
            // Notes input for log
            echo "<div class='text-element'>Enter notes</div>
                  <div class='text-element-faded'>Example: 2x Given</div>";
//            echo "<input type='text' id='log_notes' name='log_notes' >";
            echo "<textarea class='text_area' name='log_notes'></textarea>";
            echo "<br><br>";
        // Submit button for the form
        echo "</table>";

        echo "<input class='submit' type='submit' name='submit' value='Save and Continue'>";

    echo "</form>";

?>