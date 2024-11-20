<link rel="stylesheet" href="../style.css">
<body>
<div class="container">
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

    <?php

    // Include the database connection file
    include "../server/db_connect.php";

    // Set the number of results per page
    $results_per_page = 25;

    // Get the current page number from the URL, defaulting to page 1
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start_from = ($page - 1) * $results_per_page;

    try {
        // SQL query to get the total number of records (for pagination) with archived = 0
        $total_sql = "
        SELECT COUNT(*) AS total_records 
        FROM 
            takes
        INNER JOIN 
            med ON takes.med_id = med.med_id
        INNER JOIN 
            brand ON takes.brand_id = brand.brand_id
        INNER JOIN 
            students ON takes.student_id = students.student_id
        WHERE takes.archived = 0
    ";
        $total_stmt = $conn->prepare($total_sql);
        $total_stmt->execute();
        $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

        // Calculate the total number of pages
        $total_pages = ceil($total_records / $results_per_page);

        // SQL query to join tables, including pagination (LIMIT and OFFSET) with archived = 0
        $sql = "
        SELECT 
            takes.*, 
            med.med_name, 
            brand.brand_name, 
            students.first_name, 
            students.last_name, 
            students.year
        FROM 
            takes
        INNER JOIN 
            med ON takes.med_id = med.med_id
        INNER JOIN 
            brand ON takes.brand_id = brand.brand_id
        INNER JOIN 
            students ON takes.student_id = students.student_id
        WHERE takes.archived = 0
        LIMIT :limit OFFSET :offset
    ";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();
      
    if ($results) {
        echo "<table border='1' id='big_table'>";
        echo "<tr>";
        // Print table headers dynamically
        foreach (array_keys($results[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        // Fetch and display results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<table border='1'>";
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
            echo "<a href='?page=" . ($page - 1) . "'>Previous</a>";
        }

        // Loop through all pages
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<span>$i</span>";
            } else {
                echo "<a href='?page=$i'>$i</a>";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "'>Next</a>";
        }
        echo "</div>";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
    ?>
