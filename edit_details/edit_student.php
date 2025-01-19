<?php
// Include the database connection file
include "../server/db_connect.php";

// Check if the student ID is provided
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    die("<p class='error'>No student ID provided.</p>");
}

$student_id = intval($_GET['student_id']);

// Initialize variables for the form
$first_name = $last_name = $year = "";
$success_message = $error_message = "";

try {
    // Fetch the student's current details
    $sql = "SELECT * FROM students WHERE student_id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("<p class='error'>Student not found.</p>");
    }

    // Populate form variables with current data
    $first_name = $student['first_name'];
    $last_name = $student['last_name'];
    $year = $student['year'];
} catch (PDOException $e) {
    die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $year = trim($_POST['year']);

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($year)) {
        $error_message = "All fields are required.";
    } else {
        try {
            // Update the student's record in the database
            $update_sql = "UPDATE students SET first_name = :first_name, last_name = :last_name, year = :year WHERE student_id = :student_id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $update_stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $update_stmt->bindParam(':year', $year, PDO::PARAM_STR);
            $update_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $update_stmt->execute();

            $success_message = "Student record updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body>
<div class="full_page_styling">

    <!-- universal nav bar -->
<div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../student_profile/student_profile.php">Student Profile</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <div id="form-container">
        <h2>Edit Student</h2>

        <!-- Display success or error message -->
        <?php if (!empty($success_message)) : ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <!-- Form for editing student -->
        <form method="POST" action="">
            <div class='text-element'>Enter first name</div>
            <div class='text-element-faded'>Example: Joe</div>
            <input class="text_input" type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            <br><br>
            <div class='text-element'>Enter year group</div>
            <div class='text-element-faded'>Example: 12</div>
            <input class="text_input" type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            <br><br>
            <div class='text-element'>Enter year group</div>
            <div class='text-element-faded'>Example: 12</div>
            <input class='smaller_int_input' type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" required>
            <br><br>
            <button class='submit' type="submit">Update Student</button>
        </form>

        <a class="back_link" href="student_table.php"> > Go Back</a>
    </div>
</div>
</body>
