<?php
// Include the database connection file
include "../server/db_connect.php";

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

<link rel="stylesheet" href="../style.css">
<body>
<div class="container">

    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a>Logout</a></li>
        </ul>
    </div>

    <!-- Display Active Records -->
    <div id="bigt">
        <h2>Whole School Records</h2>
        <?php
        try {
            // Fetch non-archived records
            $sql = "SELECT * FROM whole_school WHERE archived = 0";
            $stmt = $conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<table>";
                echo "<tr>";
                foreach (array_keys($results[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "<th>Actions</th>";
                echo "</tr>";

                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/Y', $value);
                        }
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "<td>
                            <form method='GET' action='edit_school_record.php' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button type='submit'>Edit</button>
                            </form>
                            <form method='POST' action='' style='display:inline'>
                                <input type='hidden' name='whole_school_id' value='" . htmlspecialchars($row['whole_school_id']) . "'>
                                <button type='submit' name='archive'>Archive</button>
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

    <!-- Button to go to the form to add new records -->
    <a href="whole_school_table.php">
        <button>Add New Record</button>
    </a>
</div>
</body>
