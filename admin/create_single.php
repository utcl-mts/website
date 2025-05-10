<?php
session_start();

include "../server/check_cookie_admin.php";
include "../server/db_connect.php";
include "../server/navbar/admin_dashboard.php";
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Hours Tracking - Add New Med</title>
        <link rel="stylesheet" href=../assets/style/style.css>
    </head>
    <body class="full_page_styling">
    <br>

    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="student_management.php">View All Students</a></li>
                <li class="navbar_li"><a href="progress_students.php">Progress Students</a></li>
                <li class="navbar_li"><a class="active" href="create_single.php">Create Single Student</a></li>
                <li class="navbar_li"><a href="bulk_upload.php">Bulk Upload</a></li>
                <li class="navbar_li"><a href="export_students.php">Export All Students</a></li>
            </div>
        </ul>
    </div>

    <div>
    </div>
    <h1>Create One Student</h1>
    <form action="upload_single.php" method="post">
        <div class='text-element'>Enter students first name</div>
        <div class='text-element-faded'>Example: Joe</div>
        <input class="text_input" type="text" name="first_name" id="" required>
        <br><br>
        <div class='text-element'>Enter students last name</div>
        <div class='text-element-faded'>Example: Bloggs</div>
        <input class="text_input" type="text" name="last_name" id="" required>
        <br><br>
        <div class='text-element'>Enter students year group</div>
        <div class='text-element-faded'>Example: 12</div>
        <input class="small_int_input" type="text" name="year" id="" required>
        <br><br>
        <input class="small_submit" type="submit" name="submit" value="Submit">
    </form>
    </div>

    </body>

    </html>