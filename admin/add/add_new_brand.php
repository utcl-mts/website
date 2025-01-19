<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Add new Brand</title>
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
                <li class="navbar_li"><a href="../../edit_details/student_table.php">Student Management</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <h1>Add new brand</h1>
            <form method="post" action="">
                    <div class='text-element'>Enter brand name</div>
                    <div class='text-element-faded'>Example: Tesco</div>
                    <input class="text_input" type="text" id="brand" name="brand" required></td>
                    <br><br>
                <button class="submit" type="submit">Submit</button>
            </form>
    </body>
    <br><br>
    <a class="back_link" href="../admin_dashboard.php">< Go Back</a>
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
        if (!empty($_POST['brand'])) {
            try {
                $sql = "INSERT INTO brand (brand_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $_POST['brand'], PDO::PARAM_STR);
                $stmt->execute();
                echo "<br><br>";
                echo '<div class="success-banner">';
                    echo '<div class="success-header">';
                        echo '<h2>Success</h2>';
                echo '</div>';
                echo '<div class="success-content">';
                    echo '<p>Brand sucessfully added</p>';
                echo '</div>';
                echo '</div>';
                header("refresh:10; url=../admin_dashboard.php");

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Please fill in the brand name.";
        }
    }

?>