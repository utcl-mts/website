<?php

session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../../server/db_connect.php";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
        $staff_id = $_POST['staff_id'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate passwords
        if (empty($new_password) || empty($confirm_password)) {
            $error = "Both password fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $query = "UPDATE staff SET password = :password WHERE staff_id = :staff_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo '<div class="success-banner">';
                echo '<div class="success-header">';
                    echo '<h2>Success</h2>';
            echo '</div>';
            echo '<div class="success-content">';
                echo '<p>Password sucessfully changed</p>';
            echo '</div>';
            echo '</div>';
                header("Location: staff_home.php");
            } else {
                echo '<div class="error-banner">';
                echo '<div class="error-header">';
                    echo '<h2>Error</h2>';
                echo '</div>';
                echo '<div class="error-content">';
                    echo '<p>Failed to update the password.</p>';
                echo '</div>';
                echo '</div>';
            }
        }
    } else {
        $error = "Invalid request.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body class='full_page_styling'>
<div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href=/administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../../student_profile/student_profile.php">Student Profile</a></li>
                <li class="navbar_li"><a href="../../edit_details/student_table.php">Student Management</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

<h1>Change Password</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php elseif (isset($success)): ?>
    <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<?php if (!isset($success)): ?>
    <form action="change_password.php" method="POST">
        <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
        <div class='text-element'>Enter new password</div>
        <div class='text-element-faded'>Example: Bloggs123@#!!</div>
        <input class="text_input" type="password" id="new_password" name="new_password" required>
        <br><br>
        <div class='text-element'>Confirm Password</div>
        <div class='text-element-faded'>Same as the password entered above</div>
        <input class="text_input" type="password" id="confirm_password" name="confirm_password" required>
        <br><br>
        <button class="submit" type="submit">Update Password</button>
    </form>
<?php endif; ?>

</body>
</html>
