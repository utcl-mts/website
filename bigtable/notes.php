<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - View Notes</title>
<div>
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Create Notes</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
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

if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    try {
        // Prepare and execute the query
        $sql = "SELECT log_id, student_id, staff_id, notes, date_time 
                FROM log 
                WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the table
        echo "<h2>Log Records for Student ID: " . htmlspecialchars($student_id) . "</h2>";
        if ($results) {
            echo "<table class='big_table'>";
            echo "<tr>
                    <th class='big_table_th'>Log ID</th>
                    <th class='big_table_th'>Date Logged</th>
                    <th class='big_table_th'>Notes</th>
                </tr>";

            foreach ($results as $row) {
                echo "<tr>";
                echo "<td class='big_table_td'>" . htmlspecialchars($row['log_id']) . "</td>";
                echo "<td class='big_table_td'>" . htmlspecialchars(date('d/m/Y H:i', $row['date_time'])) . "</td>";
                echo "<td class='big_table_td'>" . htmlspecialchars($row['notes']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No records found for this student.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p class='error'>No student ID provided.</p>";
}
?>
