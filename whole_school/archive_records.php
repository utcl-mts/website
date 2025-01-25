<?php
// Include the database connection file
include "../server/db_connect.php";
include "../server/navbar/whole_school.php";
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Create Whole School Item</title>
<div>
    <br>

    <div>
    <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="active_records.php">Active Records Table</a></li>
                <li class="navbar_li"><a class='active' href="archive_records.php">Archived Records Table</a></li>
                <li class="navbar_li"><a href="whole_school_form.php">Add a new record</a></li>
            </div>
        </ul>
    </div>

<div id="archived-records">
        <h2>Archived Records</h2>
        <?php
        try {
            // Fetch archived records
            $sql = "SELECT whole_school_id, name, exp_date, amount_left, notes FROM whole_school WHERE archived = 1";
            $stmt = $conn->query($sql);
            $archived_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($archived_results) {
                echo "<table class='big_table'>";
                echo "<tr>";

                $customHeadings = [
                    'whole_school_id' => 'Record ID',
                    'name' => 'Item Name',
                    'exp_date' => 'Expiry Date',
                    'amount_left' => 'Amount Left',
                    'notes' => 'Notes',
                ];

                foreach (array_keys($archived_results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($customHeadings[$header] ?? $header) . "</th>";
                }
                echo "</tr>";

                foreach ($archived_results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
                        }
                        echo "<td class='big_table_td'>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No archived records found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>