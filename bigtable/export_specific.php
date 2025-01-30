<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Student Data to Excel</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
    <?php
    session_start();
    include "../server/db_connect.php";
    include "../server/navbar/bigtable.php";
    include "../server/check_cookie_user.php";
    ?>

<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="bigtable.php">View All Student Medication</a></li>
            <li class="navbar_li"><a class='active' href="export_specific.php">Export Specific Student Data</a></li>
        </div>
    </ul>
</div>

<br>

    <!-- Search Form -->
    <div id="search-bar">
        <form method="GET" action="">
            <input
                type="text"
                name="search"
                class="text_input2"
                placeholder="Search by student name, medication, brand, or year group"
                value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>"
            >
            <button class="submit" type="submit">Search</button>
        </form>
    </div>

    <!-- Export Form -->
    <form method="POST" action="generate_excel.php">
        <?php
        // Pagination settings
        $results_per_page = 15;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $start_from = ($page - 1) * $results_per_page;
        $search_term = trim($_GET['search'] ?? '');

        try {
            // Database query to get total count and results
            $search_param = '%' . $search_term . '%';

            $sql = "SELECT takes.takes_id, students.student_id, students.first_name, students.last_name, students.year, 
                           med.med_name, brand.brand_name, takes.exp_date, takes.current_dose, takes.min_dose
                    FROM takes 
                    INNER JOIN med ON takes.med_id = med.med_id 
                    INNER JOIN brand ON takes.brand_id = brand.brand_id 
                    INNER JOIN students ON takes.student_id = students.student_id 
                    WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                    OR med.med_name LIKE :search OR brand.brand_name LIKE :search OR students.year LIKE :search 
                    ORDER BY students.last_name ASC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
            $stmt->execute();

            $custom_headings = [
                'takes_id' => 'ID',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'year' => 'Year',
                'med_name' => 'Medication Name',
                'brand_name' => 'Brand Name',
                'exp_date' => 'Expiry Date',
                'current_dose' => 'Current Dose',
                'min_dose' => 'Minimum Dose',
            ];

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Display Table
            echo "<table class='big_table'>";
            echo "<tr>";
            echo "<th class='big_table_th'><input type='checkbox' id='select_all'></th>"; // Checkbox for select all
            foreach ($custom_headings as $heading) {
                echo "<th class='big_table_th'>" . htmlspecialchars($heading, ENT_QUOTES) . "</th>";
            }
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";
                echo "<td class='big_table_td'>
                        <input type='checkbox' name='selected_students[]' value='" . htmlspecialchars($row['student_id'], ENT_QUOTES) . "'>
                      </td>";
                foreach ($custom_headings as $column => $heading) {
                    $value = $row[$column] ?? '';
                    if ($column === 'exp_date' && is_numeric($value)) {
                        $value = date('d/m/y', $value);
                    }
                    echo "<td class='big_table_td'>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
        } catch (PDOException $e) {
            die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
        }
        ?>
        <br><br>
        <button class="submit" type="submit">Generate Excel</button>
    </form>

    <!-- Pagination Controls -->
    <div class="pagination">
        <?php
        // Calculate total pages for pagination
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM takes 
                                INNER JOIN students ON takes.student_id = students.student_id 
                                WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search");
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->execute();
        $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_records / $results_per_page);

        // Display pagination links
        if ($page > 1) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>Previous</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . $i . "' " . ($i == $page ? "class='active'" : "") . ">" . $i . "</a>";
        }

        if ($page < $total_pages) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>
</div>

<script>
// Select all checkboxes functionality
document.getElementById('select_all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
</body>
</html>
