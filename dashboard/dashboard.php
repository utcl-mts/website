<?php
// Start a new session
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

// Include the database connection file
include "../server/db_connect.php";

// Time and categorization arrays
$time = time();
$expired = [];
$less_than_2_weeks = [];
$less_than_4_weeks = [];
$below_minimum_doses = [];

// Fetch details for expired medications and those close to expiring
$sql = "
    SELECT 
        takes.exp_date, 
        takes.takes_id,
        students.student_id, 
        students.first_name, 
        students.last_name, 
        students.year, 
        med.med_name, 
        brand.brand_name,
        takes.notes,
        (SELECT CONCAT(notes.staff_code, ' logged ', notes.content) 
            FROM notes 
            WHERE notes.takes_id = takes.takes_id 
            ORDER BY notes.created_at DESC 
            LIMIT 1) AS recent_note
    FROM takes
    JOIN students ON takes.student_id = students.student_id
    JOIN med ON takes.med_id = med.med_id
    JOIN brand ON takes.brand_id = brand.brand_id
    WHERE takes.archived = 0
";
$stat = $conn->prepare($sql);
$stat->execute();
$result = $stat->fetchAll(PDO::FETCH_ASSOC);

// Categorize medications
foreach ($result as $row) {
    $expiry_date = $row["exp_date"];
    $takes_id = $row["takes_id"];
    $student_id = $row["student_id"]; // Store student_id from database
    $student_name = $row["first_name"] . " " . $row["last_name"];
    $student_year = $row["year"];
    $med_name = $row["med_name"];
    $brand_name = $row["brand_name"];
    $notes = $row["notes"];
    $recent_note = $row["recent_note"] ?? "No recent notes available";
    $formatted_date = date("d-m-y", $expiry_date);

    $medication_info = [
        'info' => "$student_name<br>Year: $student_year<br>Medication: $med_name , $brand_name<br>Expiry: <b>$formatted_date</b>",
        'takes_id' => $takes_id,
        'notes' => $notes,
        'recent_note' => $recent_note,
        'student_id' => $student_id  // Include student_id in the medication info array
    ];

    if ($expiry_date < $time) {
        $expired[] = $medication_info;
    } elseif ($expiry_date < $time + 1209600) { // Less than 2 weeks
        $less_than_2_weeks[] = $medication_info;
    } elseif ($expiry_date < $time + 2419200) { // Less than 4 weeks
        $less_than_4_weeks[] = $medication_info;
    }
}

// Fetch details for medications below minimum dose
$sql = "
    SELECT 
        takes.takes_id,
        students.student_id, 
        students.first_name, 
        students.last_name, 
        students.year, 
        med.med_name, 
        brand.brand_name, 
        takes.current_dose, 
        takes.min_dose,
        takes.notes,
        (SELECT CONCAT(notes.staff_code, ' logged ', notes.content) 
            FROM notes 
            WHERE notes.takes_id = takes.takes_id 
            ORDER BY notes.created_at DESC 
            LIMIT 1) AS recent_note
    FROM takes
    JOIN students ON takes.student_id = students.student_id
    JOIN med ON takes.med_id = med.med_id
    JOIN brand ON takes.brand_id = brand.brand_id
    WHERE takes.current_dose < takes.min_dose AND takes.archived = 0
";
$stat = $conn->prepare($sql);
$stat->execute();
$dose_result = $stat->fetchAll(PDO::FETCH_ASSOC);

foreach ($dose_result as $row) {
    $takes_id = $row["takes_id"];
    $student_id = $row["student_id"]; // Store student_id from database
    $student_name = $row["first_name"] . " " . $row["last_name"];
    $student_year = $row["year"];
    $med_name = $row["med_name"];
    $brand_name = $row["brand_name"];
    $current_dose = $row["current_dose"];
    $notes = $row["notes"];
    $recent_note = $row["recent_note"] ?? "No recent notes available";

    $medication_info = [
        'info' => "$student_name<br>Year: $student_year<br>Medication: $med_name , $brand_name<br>Dose: $current_dose",
        'takes_id' => $takes_id,
        'notes' => $notes,
        'recent_note' => $recent_note,
        'student_id' => $student_id  // Include student_id in the medication info array
    ];

    $below_minimum_doses[] = $medication_info;
}
?>
<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Dashboard</title>
<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
            <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
            <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li class="navbar_li"><a href="../log/log_form.php">Create Notes</a></li>
            <li class="navbar_li"><a href="../whole_school/active_records.php">Whole School Medication</a></li>
            <li class="navbar_li"><a href="../student_profile/student_profile.php">Student Profile</a></li>
            <li class="navbar_li"><a href="../edit_details/student_table.php">Student Management</a></li>
            <li class="navbar_li"><a href="../log-new-med/log_new_med.php">Add New Med</a></li>
        </div>
        <div class="nav_left">
            <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
            <li class="navbar_li"><a href="../logout.php">Logout</a></li>
        </div>
    </ul>
</div>
    <br><br>

    <div class="notification_container">
        <!-- Expired Table -->
        <table class="notification_table" id="out_date">
            <tr>
                <th class="notification_table_th"><h2>Expired</h2></th>
            </tr>
            <?php foreach ($expired as $medication): ?>
                <tr>
                    <td class="notification_table_td">
                        <?php echo $medication['info']; ?>
                        <br>
                        <strong>Recent Note:</strong> <?php echo htmlspecialchars($medication['recent_note']); ?>
                        <br>
                        <form method="GET" action="create_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Create Notes</button>
                        </form>
                        <form method="GET" action="view_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">View Notes</button>
                        </form>
                        <br>
                        <form action="archive.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive this medication?');">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Archive</button>
                        </form>
                        <form action="../log-new-med/log_new_med.php" method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <button class="home_page_button" type="submit">Log New Med</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 2 Weeks Table -->
        <table class="notification_table" id="near_out_date">
            <tr>
                <th class="notification_table_th"><h2>Less than 2 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_2_weeks as $medication): ?>
                <tr>
                    <td class="notification_table_td">
                        <?php echo $medication['info']; ?>
                        <br>
                        <strong>Recent Note:</strong> <?php echo htmlspecialchars($medication['recent_note']); ?>
                        <br>
                        <form method="GET" action="create_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Create Notes</button>
                        </form>
                        <form method="GET" action="view_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">View Notes</button>
                        </form>
                        <br>
                        <form action="archive.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive this medication?');">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Archive</button>
                        </form>
                        <form action="../log-new-med/log_new_med.php" method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <button class="home_page_button" type="submit">Log New Med</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 4 Weeks Table -->
        <table class="notification_table" id="far_out_date">
            <tr>
                <th class="notification_table_th"><h2>Less than 4 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_4_weeks as $medication): ?>
                <tr>
                    <td class="notification_table_td">
                        <?php echo $medication['info']; ?>
                        <br>
                        <strong>Recent Note:</strong> <?php echo htmlspecialchars($medication['recent_note']); ?>
                        <br>
                        <form method="GET" action="create_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Create Notes</button>
                        </form>
                        <form method="GET" action="view_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">View Notes</button>
                        </form>
                        <br>
                        <form action="archive.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive this medication?');">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Archive</button>
                        </form>
                        <form action="../log-new-med/log_new_med.php" method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <button class="home_page_button" type="submit">Log New Med</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Below Minimum Doses Table -->
        <table class="notification_table" id="below_min_dose">
            <tr>
                <th class="notification_table_th"><h2>Below Minimum Doses</h2></th>
            </tr>
            <?php foreach ($below_minimum_doses as $medication): ?>
                <tr>
                    <td class="notification_table_td">
                        <?php echo $medication['info']; ?>
                        <br>
                        <strong>Recent Note:</strong> <?php echo htmlspecialchars($medication['recent_note']); ?>
                        <br>
                        <form method="GET" action="create_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Create Notes</button>
                        </form>
                        <form method="GET" action="view_notes.php" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">View Notes</button>
                        </form>
                        <br>
                        <form action="archive.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive this medication?');">
                            <input type="hidden" name="takes_id" value="<?php echo $medication['takes_id']; ?>">
                            <button class="home_page_button" type="submit">Archive</button>
                        </form>
                        <form action="../log-new-med/log_new_med.php" method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $medication['student_id']; ?>">
                            <button class="home_page_button" type="submit">Log New Med</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
