<?php
// Include the database connection file
include "../server/db_connect.php";
include "../server/navbar/whole_school.php";

// Handle adding a new record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_record'])) {
    $name = trim($_POST['name']);
    $exp_date_input = trim($_POST['exp_date']);
    $amount_left = trim($_POST['amount_left']);
    $notes = trim($_POST['notes']);

    // Validate inputs
    if (!empty($name) && !empty($exp_date_input) && is_numeric($amount_left) && intval($amount_left) >= 0) {
        // Convert date to UNIX epoch timestamp at start of day
        $exp_date = strtotime('today', strtotime($exp_date_input));

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
                
                header("Location: whole_school_table.php");
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
                <li class="navbar_li"><a href="archive_records.php">Archived Records Table</a></li>
                <li class="navbar_li"><a class='active' href="whole_school_form.php">Add a new record</a></li>
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
            <div class='text-element'>Enter date:</div>
            <div class='text-element-faded'>Example: 01/12/2025</div>
            <input class="temp_date_field" type="date" id="exp_date" name="exp_date" required>
            <br><br>
            <div class='text-element'>Enter amount:</div>
            <div class='text-element-faded'>Example: 12</div>
            <input class="smaller_int_input" type="number" id="amount_left" name="amount_left" min="0" required>
            <br><br>
            <div class='text-element'>Enter notes:</div>
            <div class='text-element-faded'>Example: More arrive each month</div>
            <textarea class="text_area" id="notes" name="notes"></textarea>
            <br><br>
            <button class="submit" type="submit" name="add_record">Add Record</button>
        </form>
    </div>
</div>
</body>