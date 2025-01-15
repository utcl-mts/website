<?php
// Include the database connection file
include "../server/db_connect.php";

// Handle adding a new record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_record'])) {
    $name = trim($_POST['name']);
    $exp_date_input = trim($_POST['exp_date']); // User input for the date
    $amount_left = trim($_POST['amount_left']); // Keep as string for validation first
    $notes = trim($_POST['notes']);

    // Validate inputs
    if (!empty($name) && !empty($exp_date_input) && is_numeric($amount_left) && intval($amount_left) >= 0) {
        $exp_date = strtotime($exp_date_input); // Convert date to timestamp

        // Ensure the date conversion is successful
        if ($exp_date) {
            try {
                // Insert the new record
                $sql = "INSERT INTO whole_school (name, exp_date, amount_left, notes, archived) VALUES (:name, :exp_date, :amount_left, :notes, 0)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':exp_date', $exp_date, PDO::PARAM_INT);
                $stmt->bindParam(':amount_left', $amount_left, PDO::PARAM_INT);
                $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
                $stmt->execute();

                $success_message = "New record added successfully.";
            } catch (PDOException $e) {
                $error_message = "Database error: " . htmlspecialchars($e->getMessage());
            }
        } else {
            $error_message = "Invalid expiration date. Please use the format 'dd/mm/yyyy'.";
        }
    } else {
        $error_message = "All fields are required, and amount left must be a non-negative integer.";
    }
}

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
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Log Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <!-- Add Record Form -->
    <div id="add-record">
        <h2>Add New Whole School Record</h2>
        <?php if (!empty($success_message)) : ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class='text-element'>Enter item name:</div>
            <div class='text-element-faded'>Example: Defib Pads</div>
            <input class="text_input" type="text" id="name" name="name" required>
            <br><br>
            <div class='text-element'>Enter exp date:</div>
            <div class='text-element-faded'>Example: 01/12/2025</div>
            <input class="temp_date_field" type="date" id="exp_date" name="exp_date" required>
            <br><br>
            <div class='text-element'>Enter amount:</div>
            <div class='text-element-faded'>Example: Defib Pads</div>
            <input class="smaller_int_input" type="number" id="amount_left" name="amount_left" min="0" required>
            <br><br>
            <div class='text-element'>Enter notes:</div>
            <div class='text-element-faded'>Example: Defib Pads</div>
            <textarea class="text_area" id="notes" name="notes"></textarea>
            <br><br>
            <button class="submit" type="submit" name="add_record">Add Record</button>
        </form>
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
                echo "<table class='big_table'>";
                echo "<tr>";
                foreach (array_keys($results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($header) . "</th>";
                }
                echo "<th class='big_table_th'>Actions</th>";
                echo "</tr>";

                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
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

    <!-- Display Archived Records -->
    <div id="archived-records">
        <h2>Archived Records</h2>
        <?php
        try {
            // Fetch archived records
            $sql = "SELECT * FROM whole_school WHERE archived = 1";
            $stmt = $conn->query($sql);
            $archived_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($archived_results) {
                echo "<table class='big_table'>";
                echo "<tr>";
                foreach (array_keys($archived_results[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";

                foreach ($archived_results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/y', $value);
                        }
                        echo "<td>" . htmlspecialchars($value) . "</td>";
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
    </div>
</div>
</body>

