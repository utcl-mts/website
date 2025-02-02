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
            <li class="navbar_li"><a class='active' href="create_single.php">Create Single Student</a></li>
        </div>
    </ul>
</div>

<div>
</div>
<h1>Bulk Upload</h1>
    <button class="download_template"><a href="../assets/cdn/import_students_template.csv" download>Download CSV Template</a></button>
    <br><br>
    <form action="process_csv.php" method="post" enctype="multipart/form-data">
        <div class='text-element'>Upload a File</div>
        <div class='text-element-faded'>.CSV is the only allowed format</div>
        <input class="file_upload" id="file" accept=".csv" type="file" name="file" required><br><br>
        <input class="small_submit" type="submit" name="submit" value="Upload">
    </form>

</body>

</html>