<?php
// Include the database connection file
include "../server/db_connect.php";
include "../server/navbar/whole_school.php";

// Handle archiving a record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive'])) {
    $whole_school_id = intval($_POST['whole_school_id']);
    try {
        $sql = "UPDATE whole_school SET archived = 1 WHERE whole_school_id = :whole_school_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':whole_school_id', $whole_school_id, PDO::PARAM_INT);
        $stmt->execute();
        $success_message = "Record archived successfully.";
    } catch (PDOException $e) {
        $error_message = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Whole School</title>
<div>

    <br>

    <div>
    <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a class='active' href="active_records.php">Active Records Table</a></li>
                <li class="navbar_li"><a href="archive_records.php">Archived Records Table</a></li>
                <li class="navbar_li"><a href="whole_school_form.php">Add a new record</a></li>
            </div>
        </ul>
    </div>

    <br>

    <!-- Display Active Records -->
    <div id="bigt">
        <h2>Whole School Records</h2>
        <?php
        try {
            // Fetch non-archived records
            $sql = "SELECT whole_school_id, name, exp_date, amount_left, notes FROM whole_school WHERE archived = 0";
            $stmt = $conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<table class='big_table'>";
                echo "<tr>";
                // Define custom headings
                $customHeadings = [
                    'whole_school_id' => 'Record ID',
                    'name' => 'Item Name',
                    'exp_date' => 'Expiry Date',
                    'amount_left' => 'Amount Left',
                    'notes' => 'Notes',
                ];

                // Print custom headings based on fetched columns
                foreach (array_keys($results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($customHeadings[$header] ?? $header) . "</th>";
                }
                echo "<th class='big_table_th'>Actions</th>";
                echo "</tr>";

                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
                        }
                        echo "<td class='big_table_td'>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "<td>
                            <form method='GET' action='edit_school_record.php' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button class='table_button' type='submit'>Edit</button>
                            </form>
                            <form method='POST' action='' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button class='table_button' type='submit' name='archive'>Archive</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No records found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>

    <!-- Display Archived Records -->
    
</body>
