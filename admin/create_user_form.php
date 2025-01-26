<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/navbar/admin_dashboard.php";

?>


<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class='full_page_styling'>
<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="staff_home.php">View All Staff</a></li>
            <li class="navbar_li"><a class='active' href="create_user_form.php">Create new staff</a></li>
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
        <div class='text-element'>Enter staff code</div>
        <div class='text-element-faded'>Example: JBL</div>
        <input class="text_input" type="text" name="staff_code" id="" required>
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