<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Insert Data</title>
</head>
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
            <li><a href="../log/log.html">Log Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a>Logout</a></li>

        </ul>

    </div>


    <fieldset>
        <legend>Insert Data</legend>
        <button>
            <a href="import_students_template.csv" download>Download CSV Template</a>
        </button><br><br>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="file">Upload a CSV File:</label><br>
            <input id="file" accept=".csv" type="file" name="file" required><br><br>
            <input type="submit" name="submit" value="Upload To Preview">
        </form>

        <?php
        session_start();
        // Include the database connection file
        include "../server/db_connect.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file']) && isset($_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileType = mime_content_type($fileTmpPath);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                // Check if the uploaded file is a CSV
                if ($fileExtension === 'csv') {
                    // Open the CSV file and read its content
                    if (($handle = fopen($fileTmpPath, 'r')) !== false) {
                        echo "<h2>Preview the CSV Uploaded:</h2>";
                        echo "<table border='1' class='csv-table'>";
                        $rowIndex = 0;

                        // Store CSV data to insert later
                        $csvData = [];

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

                            // Collect data from rows (skip header)
                            if ($rowIndex > 0) {
                                $csvData[] = $row; // Store rows for insertion
                            }

                            $rowIndex++;
                        }

                        fclose($handle);
                        echo "</table>";

                        // Add submit button to insert data into database
                        echo '<form action="" method="post">';
                        echo '<input type="submit" name="submit_data" value="Submit to Database">';
                        echo '<input type="hidden" name="csv_data" value="' . base64_encode(serialize($csvData)) . '">';
                        echo '</form>';
                    } else {
                        echo "<p style='color: red;'>Error opening the file. Please try again.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Invalid file type. Please upload a valid CSV file.</p>";
                }
            } else {
                if (!isset($_FILES['file'])) {
//                    echo "<p style='color: red;'>No file uploaded. Please try again.</p>";
                } elseif ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                    echo "<p style='color: red;'>Error uploading the file. Code: " . $_FILES['file']['error'] . "</p>";
                }
            }

            // Handle inserting data to database
            // Handle inserting data to database
            if (isset($_POST['submit_data']) && isset($_POST['csv_data'])) {
                // Get the CSV data
                $csvData = unserialize(base64_decode($_POST['csv_data']));

                try {
                    $conn->beginTransaction();

                    // Prepare the SQL query
                    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, year) VALUES (?, ?, ?)");

                    $rowCount = 0; // Initialize a counter for inserted rows

                    // Insert each row into the database
                    foreach ($csvData as $row) {
                        $stmt->execute([$row[0], $row[1], $row[2]]); // Assumes CSV has first_name, last_name, year in that order
                        $rowCount += $stmt->rowCount(); // Add affected rows to the counter
                    }

                    $conn->commit();

                    // Print the number of rows inserted
                    echo "<p>Data successfully inserted into the database! Rows inserted: <strong>$rowCount</strong></p>";

                    $staff_id = $_SESSION['staff_id'];
                    $date_time = time();
                    $action = "$rowCount users were added inserted"; // Update action to indicate successful login

                    $sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES(?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(1,$staff_id);
                    $stmt->bindParam(2,$action);
                    $stmt->bindParam(3,$date_time);
                    $stmt->execute();

                } catch (PDOException $e) {
//                    $conn->rollBack();
                    echo "<p style='color: red;'>Error inserting data: " . $e->getMessage() . "</p>";
                }
            }
        }
        ?>
    </fieldset>
</div>
</body>
</html>
