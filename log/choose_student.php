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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/style.css">
    <title>Hours Tracking - Choose Student</title>
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
                <li class="navbar_li"><a href="../edit_details/student_table.php">Student Management</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
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

                        echo "<table class='administer_choose_student'>";
                        echo "<h1>Select Student</h1>";
                        // Display each student with a checkbox for selection
                        foreach ($result as $row) {

                            echo "<tr>";
                            echo "<td><input type='hidden' name='sid' value='" . $row['student_id'] . "'></td>";
                            echo "<td class='administer_choose_student_td'><input type='checkbox' name='selected_students[]' value='" . $row['student_id'] . "'></td>";
                            echo "<td class='administer_choose_student_td'><b>First name: </b>" . htmlspecialchars($row['first_name']) . "</td><br>";
                            echo "<td class='administer_choose_student_td'><b>Last name: </b>" . htmlspecialchars($row['last_name']) . "</td>";
                        }

                        // Submit button for the form
                        echo "</table>";
                    echo "<br>";
                    echo "<input class='submit' type='submit' name='submit' value='Select Students'>";

                    echo "</form>";

                echo "</div>";

            ?>

        </div>

    </body>

</html>