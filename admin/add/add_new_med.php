<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Add New Med</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
<div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href=/administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../../student_profile/student_profile.php">Student Profile</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>
    <h1>Create new Medicine</h1>

            <form method="post" action="">
                <div class='text-element'>Enter med name</div>
                <div class='text-element-faded'>Example: Paracetamol</div>
                <input class="text_input" type="text" id="medication" name="medication" required>
                <br><br>
                <button class="submit" type="submit">Submit</button>
            </form>

        </div>

    </body>

</html>


<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../../server/db_connect.php";

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['medication'])) {

            try {

                $sql = "INSERT INTO med (med_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $_POST['medication'], PDO::PARAM_STR);
                $stmt->execute();
                echo "<br><br>";
                echo '<div class="success-banner">';
                echo '<div class="success-header">';
                    echo '<h2>Success</h2>';
            echo '</div>';
            echo '<div class="success-content">';
                echo '<p>Medication successfully added!</p>';
            echo '</div>';
            echo '</div>';
                header("refresh:10; url=../admin_dashboard.php");

            } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();

            }
        } else {

        echo "Please fill in the medication name.";

        }

    }

?>