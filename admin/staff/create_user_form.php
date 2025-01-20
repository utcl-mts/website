<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../../index.html");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body class='full_page_styling'>
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
                <li class="navbar_li"><a href="../../log-new-med/log_new_med.php">Add New Med</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <h1>Create a new user</h1>

    <form action="create_user_data.php" method="post">
        <div class='text-element'>Enter first name</div>
        <div class='text-element-faded'>Example: Joe</div>
        <input class="text_input" type="text" name="first_name" id="" required>
        <br><br>
        <div class='text-element'>Enter last name</div>
        <div class='text-element-faded'>Example: Bloggs</div>
        <input class="text_input" type="text" name="last_name" id="" required>
        <br><br>
        <div class='text-element'>Enter email: </div>
        <div class='text-element-faded'>Example: joe.bloggs@utcleeds.co.uk</div>
        <input class="text_input" type="text" name="email" id="" required>
        <br><br>
        <div class='text-element'>Enter password: </div>
        <div class='text-element-faded'>Example: Password123@#!!</div>
        <input class="text_input" type="password" name="password" id="" required>
        <br><br>
        <div class='text-element'>Enter confirm password: </div>
        <div class='text-element-faded'>Example: Password123@#!!</div>
        <input class="text_input" type="password" name="c_password" id="" required>
        <br><br>
        <div class='text-element'>Select the user group: </div>
        <div class='text-element-faded'>Example: Admin</div>
        <select id="group" name="group" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <br><br><br>
        <input class="small_submit" type="submit" name="submit" value="Submit">
        <br>
    </form>