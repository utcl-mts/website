<?php
// Include the database connection file
include "../server/db_connect.php";

// Check if the record ID is provided via GET
if (!isset($_GET['whole_school_id']) || empty($_GET['whole_school_id'])) {
    die("<p class='error'>No record ID provided.</p>");
}

$whole_school_id = intval($_GET['whole_school_id']);

// Initialize variables for the form
$name = $exp_date = $amount_left = $notes = "";
$success_message = $error_message = "";

// Fetch the current details of the record
try {
    $sql = "SELECT * FROM whole_school WHERE whole_school_id = :whole_school_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':whole_school_id', $whole_school_id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        die("<p class='error'>Record not found.</p>");
    }

    $name = $record['name'];
    $exp_date = date('Y-m-d', $record['exp_date']);
    $amount_left = $record['amount_left'];
    $notes = $record['notes'];
} catch (PDOException $e) {
    die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Handle form submission for updating the record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_record'])) {
    $name = trim($_POST['name']);
    $exp_date = strtotime(trim($_POST['exp_date']));
    $amount_left = intval($_POST['amount_left']);
    $notes = trim($_POST['notes']);

    if (!empty($name) && $exp_date && $amount_left >= 0) {
        try {
            $update_sql = "UPDATE whole_school SET name = :name, exp_date = :exp_date, amount_left = :amount_left, notes = :notes WHERE whole_school_id = :whole_school_id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $update_stmt->bindParam(':exp_date', $exp_date, PDO::PARAM_INT);
            $update_stmt->bindParam(':amount_left', $amount_left, PDO::PARAM_INT);
            $update_stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
            $update_stmt->bindParam(':whole_school_id', $whole_school_id, PDO::PARAM_INT);
            $update_stmt->execute();

            $success_message = "Record updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_message = "All fields are required, and amount left must be a non-negative integer.";
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
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <!-- Edit Record Form -->
    <div id="edit-record">
        <h2>Edit Whole School Record</h2>
        <?php if (!empty($success_message)) : ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class='text-element'>Enter item name:</div>
            <div class='text-element-faded'>Example: Defib Pads</div>
            <input class="text_input" type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <br><br>
            <div class='text-element'>Enter exp date:</div>
            <div class='text-element-faded'>Example: 01/02/25</div>
            <input class="temp_date_field" type="date" id="exp_date" name="exp_date" value="<?php echo htmlspecialchars($exp_date); ?>" required>
            <br><br>
            <div class='text-element'>Enter amount:</div>
            <div class='text-element-faded'>Example: 150</div>
            <input class="smaller_int_input" type="number" id="amount_left" name="amount_left" value="<?php echo htmlspecialchars($amount_left); ?>" min="0" required>
            <br><br>
            <div class='text-element'>Enter notes:</div>
            <div class='text-element-faded'>Example: There is a delivery of items each month</div>
            <textarea class="text_area" id="notes" name="notes"><?php echo htmlspecialchars($notes); ?></textarea>
            <br><br>
            <button class="submit" type="submit" name="update_record">Update Record</button>
        </form>
    </div>
</div>
</body>
