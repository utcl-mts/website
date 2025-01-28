<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.php");
    exit();
}

include "../server/db_connect.php";
include "../server/navbar/admin_dashboard.php";

// Fetch staff data
$query = "SELECT staff_id, first_name, last_name, email, staff_code 
          FROM staff 
          WHERE staff_id != 1 AND archived != 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$staffData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Admin Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="staff_home.php">View All Staff</a></li>
            <li class="navbar_li"><a class='active' href="archived_staff.php">Archived Staff</a></li>
            <li class="navbar_li"><a href="create_user_form.php">Create new staff</a></li>
        </div>
    </ul>
</div>
<h1>Archived Staff</h1>
<table class="big_table">
    <thead>
    <tr>
        <th class="big_table_th">Staff ID</th>
        <th class="big_table_th">First Name</th>
        <th class="big_table_th">Last Name</th>
        <th class="big_table_th">Email</th>
        <th class="big_table_th">Staff Code</th>
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
    <a class="back_link" href="admin_dashboard.php">< Go Back</a>

<?php
$conn = null; // Close the database connection
?>

</body>
</html>