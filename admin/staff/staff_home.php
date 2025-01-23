<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../../index.html");
    exit();
}

include "../../server/db_connect.php";

// Fetch staff data
$query = "SELECT staff_id, first_name, last_name, email, staff_code FROM staff WHERE staff_id != 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$staffData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>
<body class="full_page_styling">
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
                <li class="navbar_li"><a href="../../log-new-med/log_new_med.php">Add New Med</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

<h1>Staff Management</h1>


    <button class="submit"><a class="remove_a" href="create_user_form.php">Create a new user</a></button>

    <br><br>

<table class="big_table">
    <thead>
    <tr>
        <th class="big_table_th">Staff ID</th>
        <th class="big_table_th">First Name</th>
        <th class="big_table_th">Last Name</th>
        <th class="big_table_th">Email</th>
        <th class="big_table_th">Staff Code</th>
        <th class="big_table_th">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($staffData)): ?>
        <?php foreach ($staffData as $row): ?>
            <tr>
                <td class="big_table_td"><?= htmlspecialchars($row['staff_id']) ?></td>
                <td class="big_table_td"><?= htmlspecialchars($row['first_name']) ?></td>
                <td class="big_table_td"><?= htmlspecialchars($row['last_name']) ?></td>
                <td class="big_table_td"><?= htmlspecialchars($row['email']) ?></td>
                <td class="big_table_td"><?= htmlspecialchars($row['staff_code']) ?></td>
                <td class="action-buttons">
                    <form action="edit_user.php" method="GET" style="display:inline;">
                        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($row['staff_id']) ?>">
                        <button class="table_button" type="submit">Edit Details</button>
                    </form>
                    <form action="change_password.php" method="GET" style="display:inline;">
                        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($row['staff_id']) ?>">
                        <button class="table_button" type="submit">Change Password</button>
                    </form>
                    <!-- <form action="delete_user.php" method="POST" style="display:inline;"> -->
                    <form action="delete_user.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this member of staff?');">
                        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($row['staff_id']) ?>">
                        <button class="table_button" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No staff members found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
    <br><br>
    <a class="back_link" href="../admin_dashboard.php">< Go Back</a>

<?php
$conn = null; // Close the database connection
?>

</body>
</html>