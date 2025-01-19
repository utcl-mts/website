<?php
// Start session to use session variables
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/style.css">
    <title>Hours Tracking - Log Form</title>
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
                <li class="navbar_li"><a href="../log/log_form.php">Log Medication</a></li>
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
        <h1>Choose a Student</h1>
        <form action="choose_student.php" method="post">
            <div class='text-element'>Enter students first name</div>
            <div class='text-element-faded'>Example: Joe</div>
            <input class="text_input" type="text" id="sfn"name="student_fname" required>
            <br><br>
            <div class='text-element'>Enter students year group</div>
            <div class='text-element-faded'>Example: 12</div>
            <input class="small_int_input" type="text" id="syg" name="student_yeargroup" required>
            <br><br>
            <button class="small_submit" type="submit">Submit</button>
        </form>
    </div>
</body>
</html>