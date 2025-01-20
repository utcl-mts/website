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

    <div id="search-bar">
        <form method="GET" action="">
            <input
                type="text"
                name="search"
                class="search_bar"
                placeholder="Search by student name, medication, or brand"
                value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>"
            >
            <button class="submit" type="submit">Search</button>
        </form>
    </div>

    <br><br>

    <?php
    include "../server/db_connect.php";

    $results_per_page = 15;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $results_per_page;
    $search_term = trim($_GET['search'] ?? '');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decrement'])) {
        $takes_id = intval($_POST['takes_id']);

        try {
            $check_sql = "SELECT current_dose FROM takes WHERE takes_id = :takes_id";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);
            $check_stmt->execute();
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['current_dose'] > 0) {
                $update_sql = "UPDATE takes SET current_dose = current_dose - 1 WHERE takes_id = :takes_id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':takes_id', $takes_id, PDO::PARAM_INT);
                $update_stmt->execute();
                echo "<p class='success'>Dose decremented successfully.</p>";
            } else {
                echo "<p class='error'>Cannot decrement. Dose is already at zero.</p>";
            }
        } catch (PDOException $e) {
            die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "</p>");
        }
    }

    try {
        $total_sql = "SELECT COUNT(*) AS total_records FROM takes 
                      INNER JOIN med ON takes.med_id = med.med_id 
                      INNER JOIN brand ON takes.brand_id = brand.brand_id 
                      INNER JOIN students ON takes.student_id = students.student_id 
                      WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                      OR med.med_name LIKE :search OR brand.brand_name LIKE :search";
        $total_stmt = $conn->prepare($total_sql);
        $search_param = '%' . $search_term . '%';
        $total_stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $total_stmt->execute();
        $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

        $total_pages = ceil($total_records / $results_per_page);

        $sql = "SELECT takes.takes_id, takes.exp_date, takes.current_dose, takes.min_dose, 
                       takes.strength, med.med_name, brand.brand_name, 
                       students.student_id, students.first_name, students.last_name, students.year 
                FROM takes 
                INNER JOIN med ON takes.med_id = med.med_id 
                INNER JOIN brand ON takes.brand_id = brand.brand_id 
                INNER JOIN students ON takes.student_id = students.student_id 
                WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                OR med.med_name LIKE :search OR brand.brand_name LIKE :search 
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();

        $custom_headings = [
            'takes_id' => 'ID',
            'exp_date' => 'Expiry Date',
            'current_dose' => 'Current Dose',
            'min_dose' => 'Minimum Dose',
            'strength' => 'Strength',
            'med_name' => 'Medication Name',
            'brand_name' => 'Brand Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'year' => 'Year'
        ];

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<div id ='bigt'>";
        if ($results) {
            echo "<table class='big_table'>";
            echo "<tr>";
            foreach ($custom_headings as $heading) {
                echo "<th>" . htmlspecialchars($heading, ENT_QUOTES) . "</th>";
            }
            echo "<th>Actions</th>";
            echo "<th>Notes</th>";
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";
                foreach ($custom_headings as $column => $heading) {
                    $value = $row[$column] ?? '';
                    if ($column === 'exp_date' && is_numeric($value)) {
                        $value = date('d/m/y', $value);
                    }
                    echo "<td class='big_table_td'>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
                }
                echo "<td>
                        <form method='POST' action=''>
                            <input type='hidden' name='takes_id' value='" . htmlspecialchars($row['takes_id'], ENT_QUOTES) . "'>
                            <button type='submit' name='decrement' " . ($row['current_dose'] <= 0 ? "disabled" : "") . ">
                                Decrement Dose
                            </button>
                        </form>
                    </td>";
                echo "<td>
                        <form method='GET' action='notes.php'>
                            <input type='hidden' name='student_id' value='" . htmlspecialchars($row['student_id'], ENT_QUOTES) . "'>
                            <button type='submit'>
                                Notes
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

        echo "<div class='pagination'>";
        if ($page > 1) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>Previous</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<span class='active'>$i</span>";
            } else {
                echo "<a href='?search=" . urlencode($search_term) . "&page=$i'>$i</a>";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next</a>";
        }
        echo "</div>";
    } catch (PDOException $e) {
        die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
    }
    ?>
</div>
</body>
</html>
