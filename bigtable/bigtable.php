<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Student Medication</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
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

    <br><br>

    <div class="searchbar">
        <form method="GET" action="">
            <input
                    id="search-input"
                    type="text"
                    name="search"
                    class="search_bar"
                    placeholder="Search by student name, medication, or brand"
                    value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>"
            >
            <button class="small_submit" id="search-button" type="submit">Search</button>
        </form>
    </div>

    <br><br>

    <?php

session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

// Include necessary files
include "../server/db_connect.php";
include "../audit-log/audit-log.php"; // Ensure logAction function is included

// Set the number of results per page
$results_per_page = 15;

// Get the current page number from the URL, defaulting to page 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $results_per_page;

// Get the search term if provided
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Log the search term if it's not empty
if (!empty($search_term)) {
    $staff_id = $_SESSION['staff_id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $action = "Searched for: " . $search_term;
    // ID of the user performing the action
    logAction($conn, $staff_id, $action);
}


try {
    // SQL query to get the total number of records (for pagination)
    $total_sql = "SELECT COUNT(*) AS total_records FROM takes 
                  INNER JOIN med ON takes.med_id = med.med_id 
                  INNER JOIN brand ON takes.brand_id = brand.brand_id 
                  INNER JOIN students ON takes.student_id = students.student_id 
                  WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                  OR med.med_name LIKE :search 
                  OR brand.brand_name LIKE :search";

    $total_stmt = $conn->prepare($total_sql);
    $search_param = '%' . $search_term . '%';
    $total_stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    $total_stmt->execute();
    $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $results_per_page);

    // SQL query to join tables, including pagination (LIMIT and OFFSET)
    $sql = "SELECT takes.*, med.med_name, brand.brand_name, students.first_name, students.last_name, students.year 
            FROM takes 
            INNER JOIN med ON takes.med_id = med.med_id 
            INNER JOIN brand ON takes.brand_id = brand.brand_id 
            INNER JOIN students ON takes.student_id = students.student_id 
            WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
            OR med.med_name LIKE :search 
            OR brand.brand_name LIKE :search 
            LIMIT :limit OFFSET :offset";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and display results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<div id='bigt'>";
    if ($results) {
        echo "<table class='big_table'>";
        echo "<tr>";

        // Print table headers dynamically
        $headers = ['first_name' => 'First Name', 'last_name' => 'Last Name', 'med_name' => 'Medication Name', 'brand_name' => 'Brand Name', 'year' => 'Year'];
        foreach ($headers as $key => $label) {
            echo "<th class='big_table_th'>" . htmlspecialchars($label) . "</th>";
        }
        echo "<th class='big_table_th'>Actions</th>"; // Add a header for the button
        echo "</tr>";

        // Print rows
        foreach ($results as $row) {
            echo "<tr>";
            foreach ($headers as $key => $label) {
                // Format the data according to each header
                echo "<td class='big_table_td'>" . htmlspecialchars($row[$key] ?? '') . "</td>";
            }

            // Add the decrement button
            echo "<td class='big_table_td'>
                    <form method='POST' action=''>
                        <input type='hidden' name='takes_id' value='" . htmlspecialchars($row['takes_id']) . "'>
                        <button type='submit' name='decrement' " . ($row['current_dose'] <= 0 ? "disabled" : "") . ">
                            Decrement Dose
                        </button>
                    </form>
                </td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No records found.";
    }
    echo "</div>";

    // Pagination links
    echo "<div class='pagination'>";
    if ($page > 1) {
        echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>← Previous</a>";
    }

    // Loop through all pages
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            echo "<span class='active'>$i</span>";
        } else {
            echo "<a href='?search=" . urlencode($search_term) . "&page=$i'>$i</a>";
        }
    }

    if ($page < $total_pages) {
        echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next →</a>";
    }
    echo "</div>";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
