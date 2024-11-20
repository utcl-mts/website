<?php
// Start a new session
session_start();

// Include the database connection file
include "../server/db_connect.php";

$time = time();

// Arrays for which medication is expired
$expired = [];
$less_than_2_weeks = [];
$less_than_4_weeks = [];
$below_minimum_doses = [];

// Fetch details for expired medications and those close to expiring
$sql = "
    SELECT 
        takes.exp_date, 
        takes.takes_id, 
        students.first_name, 
        students.last_name, 
        students.year, 
        med.med_name, 
        brand.brand_name 
    FROM takes
    JOIN students ON takes.student_id = students.student_id
    JOIN med ON takes.med_id = med.med_id
    JOIN brand ON takes.brand_id = brand.brand_id
";
$stat = $conn->prepare($sql);
$stat->execute();
$result = $stat->fetchAll(PDO::FETCH_ASSOC);

// Put each medication into the appropriate array
foreach ($result as $row) {
    $expiry_date = $row["exp_date"];
    $takes_id = $row["takes_id"];
    $student_name = $row["first_name"] . " " . $row["last_name"];
    $student_year = $row["year"];
    $med_name = $row["med_name"];
    $brand_name = $row["brand_name"];
    $formatted_date = date("d-m-y", $expiry_date);

    $medication_info = "$student_name<br>Year: $student_year<br>Medication: $med_name<br>Brand: $brand_name<br>Expiry: $formatted_date";

    if ($expiry_date < $time) {
        $expired[] = $medication_info;
    } elseif ($expiry_date < $time + 1209600) { // Less than 2 weeks
        $less_than_2_weeks[] = $medication_info;
    } elseif ($expiry_date < $time + 2419200) { // Less than 4 weeks
        $less_than_4_weeks[] = $medication_info;
    }
}

// Get meds below minimum dose
$sql = "
    SELECT 
        takes.takes_id, 
        students.first_name, 
        students.last_name, 
        students.year, 
        med.med_name, 
        brand.brand_name, 
        takes.current_dose, 
        takes.min_dose 
    FROM takes
    JOIN students ON takes.student_id = students.student_id
    JOIN med ON takes.med_id = med.med_id
    JOIN brand ON takes.brand_id = brand.brand_id
    WHERE takes.current_dose < takes.min_dose
";
$stat = $conn->prepare($sql);
$stat->execute();
$dose_result = $stat->fetchAll(PDO::FETCH_ASSOC);

foreach ($dose_result as $row) {
    $student_name = $row["first_name"] . " " . $row["last_name"];
    $student_year = $row["year"];
    $med_name = $row["med_name"];
    $brand_name = $row["brand_name"];
    $below_minimum_doses[] = "$student_name<br>Year: $student_year<br>Medication: $med_name<br>Brand: $brand_name";
}
?>
<link rel="stylesheet" href="../style.css">
<body>
<div class="container">
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a>Logout</a></li>
        </ul>
    </div>

    <div class="notification_container">
        <!-- Expired Table -->
        <table class="notification_table" id="out_date">
            <tr>
                <th><h2>Expired</h2></th>
            </tr>
            <?php foreach ($expired as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 2 Weeks Table -->
        <table class="notification_table" id="near_out_date">
            <tr>
                <th><h2>Less than 2 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_2_weeks as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 4 Weeks Table -->
        <table class="notification_table" id="far_out_date">
            <tr>
                <th><h2>Less than 4 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_4_weeks as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Below Minimum Doses Table -->
        <table class="notification_table" id="below_min_dose">
            <tr>
                <th><h2>Below Minimum Doses</h2></th>
            </tr>
            <?php foreach ($below_minimum_doses as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>