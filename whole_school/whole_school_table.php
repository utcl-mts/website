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
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="exp_date">Expiration Date (dd/mm/yyyy):</label>
            <input type="text" id="exp_date" name="exp_date" placeholder="e.g., 01/12/2024" required>

            <label for="amount_left">Amount Left:</label>
            <input type="number" id="amount_left" name="amount_left" min="0" required>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea>

            <button type="submit" name="add_record">Add Record</button>
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
                echo "<table>";
                echo "<tr>";
                foreach (array_keys($archived_results[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";

                foreach ($archived_results as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key === 'exp_date' && is_numeric($value)) {
                            $value = date('d/m/Y', $value);
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

