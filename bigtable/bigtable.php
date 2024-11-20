<link rel="stylesheet" href="../style.css">
<body>

    <div class="container">

        <!-- universal nav bar-->
        <div class="navbar">

            <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">

            <h1 id="med_tracker">Med Tracker</h1>

            <ul>

                <li><a href="../dashboard/dashboard.php">Home</a></li>
                <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
                <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <li><a href="../administer/administer.html">Administer Medication</a></li>
                <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                <li class="logout"><a>Logout</a></li>

            </ul>

        </div>

        <div class="search-bar">

            <form method="GET" action="">

                <input
                        type="text"
                        name="search"
                        placeholder="Search by student name, medication, or brand"
                        value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>"
                >
                <button type="submit">Search</button>

            </form>

        </div>

        <?php

            // Include the database connection file
            include "../server/db_connect.php";

            // Set the number of results per page
            $results_per_page = 10;

            // Get the current page number from the URL, defaulting to page 1
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $start_from = ($page - 1) * $results_per_page;

            // Get the search term if provided
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

            try {
                // SQL query to get the total number of records (for pagination)
                $total_sql = "SELECT COUNT(*) AS total_records FROM takes INNER JOIN med ON takes.med_id = med.med_id INNER JOIN brand ON takes.brand_id = brand.brand_id INNER JOIN students ON takes.student_id = students.student_id WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search OR med.med_name LIKE :search OR brand.brand_name LIKE :search";
                $total_stmt = $conn->prepare($total_sql);
                $search_param = '%' . $search_term . '%';
                $total_stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
                $total_stmt->execute();
                $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

                // Calculate the total number of pages
                $total_pages = ceil($total_records / $results_per_page);

                // SQL query to join tables, including pagination (LIMIT and OFFSET)
                $sql = "SELECT takes.*, med.med_name,brand.brand_name, students.first_name, students.last_name, students.year FROM takes INNER JOIN med ON takes.med_id = med.med_id INNER JOIN brand ON takes.brand_id = brand.brand_id INNER JOIN  students ON takes.student_id = students.student_id WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search OR med.med_name LIKE :search OR brand.brand_name LIKE :search LIMIT :limit OFFSET :offset";

                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
                $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
                $stmt->execute();

                // Fetch and display results
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    echo "<table>";
                    echo "<tr>";
                    // Print table headers dynamically
                    foreach (array_keys($results[0]) as $header) {
                        echo "<th>" . htmlspecialchars($header) . "</th>";
                    }
                    echo "</tr>";

                    // Print rows
                    foreach ($results as $row) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            // Format exp_date column if it's an epoch timestamp
                            if ($key === 'exp_date' && is_numeric($value)) {
                                $value = date('d/m/y', $value);
                            }
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No records found.";
                }

                // Pagination links
                echo "<div class='pagination'>";
                if ($page > 1) {
                    echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>Previous</a>";
                }

                // Loop through all pages
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span>$i</span>";
                    } else {
                        echo "<a href='?search=" . urlencode($search_term) . "&page=$i'>$i</a>";
                    }
                }

                if ($page < $total_pages) {
                    echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next</a>";
                }
                echo "</div>";
            } catch (PDOException $e) {
                die("Database error: " . $e->getMessage());
            }

        ?>
    </div>
</body>