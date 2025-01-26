<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";
include "../server/navbar/admin_dashboard.php";

// Pagination parameters
$records_per_page = 18; // Number of records per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

// Calculate offset
$offset = ($current_page - 1) * $records_per_page;

// Define custom headings
$custom_headings = [
    'med_id' => 'Med ID',
    'med_name' => 'Med Name'
];

try {
    // Fetch the total number of records
    $total_sql = "SELECT COUNT(*) FROM med";
    $total_records = $conn->query($total_sql)->fetchColumn();

    // Calculate total pages
    $total_pages = ceil($total_records / $records_per_page);

    // SQL query to fetch data for the current page
    $sql = "SELECT * FROM med LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands Table</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a class='active' href="medication_management.php">View All Medication</a></li>
            <li class="navbar_li"><a href="add_new_med.php">Create New Medication</a></li>
            <li class="navbar_li"><a href="export_meds.php">Export All Medication</a></li>
        </div>
    </ul>
</div>
<h1>Medication Table</h1>
<table class="big_table">
    <thead>
    <tr>
        <?php
        // Output custom table headers
        if (!empty($brands)) {
            foreach (array_keys($brands[0]) as $columnName) {
                echo "<th class='big_table_th'>" . htmlspecialchars($custom_headings[$columnName] ?? $columnName) . "</th>";
            }
        }
        ?>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($brands)): ?>
        <?php foreach ($brands as $row): ?>
            <tr>
                <td class="big_table_td_custom_one"><?= htmlspecialchars($row['med_id']) ?></td>
                <td class="big_table_td"><?= htmlspecialchars($row['med_name']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2" class="big_table_td">No data found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Pagination Links -->
<div class="pagination">
    <?php
    if ($total_pages > 1) {
        for ($page = 1; $page <= $total_pages; $page++) {
            echo '<a href="?page=' . $page . '" class="' . ($page == $current_page ? 'active' : '') . '">' . $page . '</a>';
        }
    }
    ?>
</div>
</body>
</html>
