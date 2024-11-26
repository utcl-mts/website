<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>CSV Upload Results</title>
</head>
<body>
<div class="container">
    <!-- Navbar -->
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data.html">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <fieldset>
        <legend>Upload Results</legend>

        <?php
        session_start();
        include "../server/db_connect.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileType = mime_content_type($fileTmpPath);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                if ($fileExtension === 'csv') {
                    if (($handle = fopen($fileTmpPath, 'r')) !== false) {
                        echo "<h2>CSV Content</h2>";
                        echo "<table border='1' class='csv-table'>";
                        $rowIndex = 0;

                        // Prepare for database insertion
                        $insertCount = 0;

                        // Begin a transaction
                        $conn->beginTransaction();

                        // Prepare SQL statement
                        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, year) VALUES (?, ?, ?)");

                        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                            echo "<tr>";
                            foreach ($row as $cell) {
                                if ($rowIndex === 0) {
                                    echo "<th>" . htmlspecialchars($cell) . "</th>"; // Header
                                } else {
                                    echo "<td>" . htmlspecialchars($cell) . "</td>"; // Data
                                }
                            }
                            echo "</tr>";

                            // Skip the header row
                            if ($rowIndex > 0) {
                                try {
                                    // Insert data into the database
                                    $stmt->execute([$row[0], $row[1], $row[2]]);
                                    $insertCount += $stmt->rowCount(); // Count successful inserts
                                } catch (PDOException $e) {
                                    echo "<p style='color: red;'>Error inserting row: " . htmlspecialchars(implode(", ", $row)) . "</p>";
                                }
                            }

                            $rowIndex++;
                        }

                        // Commit the transaction
                        $conn->commit();

                        fclose($handle);
                        echo "</table>";

                        // Display success message
                        echo "<p>Data successfully inserted into the database! Rows inserted: <strong>$insertCount</strong></p>";
                        $staff_id = $_SESSION["staff_id"];
                        $act = "$insertCount students have been added";
                        $date_time = date("U");
                        $sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES(?, ?, ?)";

                        $stmt = $conn->prepare($sql);

                        $stmt->bindParam(1,$staff_id);
                        $stmt->bindParam(2,$act);
                        $stmt->bindParam(3,$date_time);

                        $stmt->execute();
                    } else {
                        echo "<p style='color: red;'>Error opening the file. Please try again.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Invalid file type. Please upload a valid CSV file.</p>";
                }
            } else {
                echo "<p style='color: red;'>Error uploading the file. Code: " . $_FILES['file']['error'] . "</p>";
            }
        }
        ?>
    </fieldset>
</div>
</body>
</html>
