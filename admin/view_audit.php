<!DOCTYPE html>
<html>
<head>
    <title>Audit Log</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
    <?php
    session_start();
    include "../server/db_connect.php";
    include "../server/audit-log.php";
    include "../server/navbar/admin_dashboard.php";
    include "../server/check_cookie_admin.php";
    ?>

    <br><br>

    <div id="search-bar">
        <form method="GET" action="">
            <input
                    type="text"
                    name="search"
                    class="text_input2"
                    placeholder="Search by staff ID, action, or source"
                    value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>"
            >
            <button class="submit" type="submit">Search</button>
        </form>
    </div>

    <br><br>

    <?php
    $results_per_page = 15;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $results_per_page;
    $search_term = trim($_GET['search'] ?? '');

    if (!empty($search_term)) {
        $source = "Audit Log";
        $staff_id = $_SESSION['staff_id'];
        $staff_code = $_SESSION['staff_code'];
        $action = "$staff_code searched audit log for: $search_term";

        logAction($conn, $staff_id, $action, $source);
    }

    try {
        // Total records query
        $total_sql = "SELECT COUNT(*) AS total_records FROM audit_logs
                      WHERE staff_id LIKE :search 
                      OR act LIKE :search OR source LIKE :search";
        $total_stmt = $conn->prepare($total_sql);
        $search_param = '%' . $search_term . '%';
        $total_stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $total_stmt->execute();
        $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

        $total_pages = ceil($total_records / $results_per_page);

        // Fetch audit log data
        $sql = "SELECT * FROM audit_logs WHERE staff_id LIKE :search 
                OR act LIKE :search OR source LIKE :search 
                ORDER BY date_time DESC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();

        $custom_headings = [
            'staff_id' => 'Staff ID',
            'act' => 'Action',
            'source' => 'Source',
            'date_time' => 'Epoch Time',
        ];

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<div id='bigt'>";
        if ($results) {
            echo "<table class='big_table'>";
            echo "<tr>";
            foreach ($custom_headings as $heading) {
                echo "<th class='big_table_th'>" . htmlspecialchars($heading, ENT_QUOTES) . "</th>";
            }
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";
                foreach ($custom_headings as $column => $heading) {
                    $value = $row[$column] ?? '';

                    if ($column === 'date_time' && is_numeric($value)) {
                        $value = date('d-m-Y H:i:s', $value);
                    }
                    echo "<td class='big_table_td'>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No records found.";
        }
        echo "</div>";

        // Pagination
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
