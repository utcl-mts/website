<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
   header("Location: ../index.html");
   exit();
}
echo'<link rel="stylesheet" href="../assets/style/style.css">';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
</head>
<body class="full_page_styling">
<div>
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <li class="navbar_li"><a href="../administer/administer.html">Administer Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <h1>Edit Notes</h1>
    <button class="download_template"><a href="import_students_template.csv" download>Download CSV Template</a></button>
    <br><br>
    <form action="process_csv.php" method="post" enctype="multipart/form-data">
        <div class='text-element'>Upload a File</div>
        <div class='text-element-faded'>.CSV is the only allowed format</div>
        <input class="file_upload" id="file" accept=".csv" type="file" name="file" required><br><br>
        <input class="small_submit" type="submit" name="submit" value="Upload">
    </form>

    <hr>

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
    <hr>

    <h1>Export Students</h1>
    <form action="export_students.php" method="post">
        <label class="text-element" for="export_meds">Export as All Students Excel/CSV</label>
        <br><br>
        <select id="1" name="export_meds">
            <option value="excel">Excel</option>
            <option value="csv">CSV</option>
        </select>
        <br><br>
        <input class="small_submit" type="submit">
    </form>

    <hr>

    <h1>Export Brands</h1>
    <form action="export_brands.php" method="post">
        <label class="text-element"  for="export_brands">Export as All Brands Excel/CSV</label>
        <br><br>
        <select id="export_brands" name="export_brands">
            <option value="excel">Excel</option>
            <option value="csv">CSV</option>
        </select>
        <br><br>
        <input class="small_submit" type="submit">
    </form>

    <hr>

    <h1>Export Medications</h1>
    <form action="export_meds.php" method="post">
        <label class="text-element" for="export_meds">Export as All Medication Excel/CSV</label>
        <br><br>
        <select id="" name="export_meds">
            <option value="excel">Excel</option>
            <option value="csv">CSV</option>
        </select>
        <br><br>
        <input class="submit" type="submit">
    </form>
    <br><br><br>
</div>
</body>
</html>
