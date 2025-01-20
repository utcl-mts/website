<?php
// Include the database connection file
include "../server/db_connect.php";

// Handle archiving a record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive'])) {
    $whole_school_id = intval($_POST['whole_school_id']);
    try {
        $sql = "UPDATE whole_school SET archived = 1 WHERE whole_school_id = :whole_school_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':whole_school_id', $whole_school_id, PDO::PARAM_INT);
        $stmt->execute();
        $success_message = "Record archived successfully.";
    } catch (PDOException $e) {
        $error_message = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Whole School</title>
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

    <br><br>

    <a href="whole_school_form.php">
        <button class="submit">Add New Record</button>
    </a>

    <!-- Display Active Records -->
    <div id="bigt">
        <h2>Whole School Records</h2>
        <?php
        try {
            // Fetch non-archived records
            $sql = "SELECT * FROM whole_school WHERE archived = 0";
            $stmt = $conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<table class='big_table'>";
                echo "<tr>";
                foreach (array_keys($results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($header) . "</th>";
                }
                echo "<th class='big_table_th'>Actions</th>";
                echo "</tr>";

                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
                        }
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "<td>
                            <form method='GET' action='edit_school_record.php' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button type='submit'>Edit</button>
                            </form>
                            <form method='POST' action='' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button type='submit' name='archive'>Archive</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No records found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
   
    <!-- Button to go to the form to add new records -->

    <!-- Display Archived Records -->
    <div id="archived-records">
        <h2>Archived Records</h2>
        <?php
        try {
            // Fetch archived records
            $sql = "SELECT * FROM whole_school WHERE archived = 1";
            $stmt = $conn->query($sql);
            $archived_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($archived_results) {
                echo "<table class='big_table'>";
                echo "<tr>";
                foreach (array_keys($archived_results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";

                foreach ($archived_results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
                        }
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No archived records found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
</div>
</body>
