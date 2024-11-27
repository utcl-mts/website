<?php
Start a new session
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
   header("Location: ../index.html");
   exit();
}
echo'<link rel="stylesheet" href="../style.css">';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
</head>
<body>
<div class="container">
    <!-- Navbar -->
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="insert_data.html">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <fieldset>
        <legend>Bulk Upload Students</legend>
        <button>
            <a href="import_students_template.csv" download>Download CSV Template</a>
        </button><br><br>
        <form action="process_csv.php" method="post" enctype="multipart/form-data">
            <label for="file">Upload a CSV File:</label><br>
            <input id="file" accept=".csv" type="file" name="file" required><br><br>
            <input type="submit" name="submit" value="Upload">
        </form>
    </fieldset>

    <fieldset>
        <legend>Create One Student</legend>
        <form action="upload_single.php" method="post">
            <input type="text" name="first_name" id="" placeholder="Enter First Name" required>
            <input type="text" name="last_name" id="" placeholder="Enter Last Name" required>
            <br><br>
            <input type="text" name="year" id="" placeholder="Enter Year Group" required>
            <br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    </fieldset>

    <fieldset>
        <legend>Export Students</legend>
        <form action="export_students.php" method="post">
            <label for="export_meds">Export as All Students Excel/CSV</label>
            <select id="1" name="export_meds">
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <input type="submit">
        </form>
    </fieldset>

    <fieldset>
        <legend>Export Brands</legend>
        <form action="export_brands.php" method="post">
            <label for="export_brands">Export as All Brands Excel/CSV</label>
            <select id="cars" name="export_brands">
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <input type="submit">
        </form>
    </fieldset>

    <fieldset>
        <legend>Export Medications</legend>
        <form action="export_meds.php" method="post">
            <label for="export_meds">Export as All Medication Excel/CSV</label>
            <select id="" name="export_meds">
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <input type="submit">
        </form>
    </fieldset>
</div>
</body>
</html>
