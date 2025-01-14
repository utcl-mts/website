<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/style.css">
    <title>CSV Upload Results</title>
</head>
<body class="full_page_styling">
<div>
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
                <li class="navbar_li"><a href="../administer/administer.html">Administer Medication</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

        <?php
        session_start();
        include "../server/db_connect.php";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['confirm_insertion'])) {
                // Handle data insertion after confirmation
                $filePath = $_SESSION['uploaded_file'];
                if (file_exists($filePath)) {
                    if (($handle = fopen($filePath, 'r')) !== false) {
                        echo "<h2>Inserting Data...</h2>";
                        $insertCount = 0;

                        // Begin a transaction
                        $conn->beginTransaction();
                        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, year) VALUES (?, ?, ?)");

                        $rowIndex = 0;
                        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                            if ($rowIndex > 0) { // Skip header
                                try {
                                    $stmt->execute([$row[0], $row[1], $row[2]]);
                                    $insertCount += $stmt->rowCount();
                                } catch (PDOException $e) {
                                    echo "<p style='color: red;'>Error inserting row: " . htmlspecialchars(implode(", ", $row)) . "</p>";
                                }
                            }
                            $rowIndex++;
                        }

                        $conn->commit();
                        fclose($handle);

                        echo "<p>Data successfully inserted into the database! Rows inserted: <strong>$insertCount</strong></p>";
                        header("refresh:5; insert_data_home.php");


                        // Log activity
                        $staff_id = $_SESSION["staff_id"];
                        $act = "$insertCount students have been added";
                        $date_time = date("U");
                        $logStmt = $conn->prepare("INSERT INTO audit_logs (staff_id, act, date_time) VALUES (?, ?, ?)");
                        $logStmt->execute([$staff_id, $act, $date_time]);

                        // Cleanup
                        unlink($filePath); // Delete the temporary file
                        unset($_SESSION['uploaded_file']); // Clear session variable
                    }
                } else {
                    echo "<p style='color: red;'>Error: File not found for insertion.</p>";
                }
            } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = basename($_FILES['file']['name']);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                if ($fileExtension === 'csv') {
                    $uploadDir = '../uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true); // Create uploads directory if not exists
                    }

                    $savedFilePath = $uploadDir . uniqid() . '-' . $fileName;

                    if (move_uploaded_file($fileTmpPath, $savedFilePath)) {
                        $_SESSION['uploaded_file'] = $savedFilePath;

                        if (($handle = fopen($savedFilePath, 'r')) !== false) {
                            echo "<h2>CSV Content</h2>";
                            echo "<table class='csv-table'>";
                            $rowIndex = 0;

                            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                                echo "<tr>";
                                foreach ($row as $cell) {
                                    if ($rowIndex === 0) {
                                        echo "<th>" . htmlspecialchars($cell) . "</th>";
                                    } else {
                                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                                    }
                                }
                                echo "</tr>";
                                $rowIndex++;
                            }

                            fclose($handle);
                            echo "</table>";

                            // Display confirmation button
                            echo '<form method="POST">';
                            echo '<br>';
                            echo '<button class="submit" type="submit" name="confirm_insertion">Confirm and Insert Data</button>';
                            echo '</form>';
                        } else {
                            echo "<p style='color: red;'>Error opening the file for preview.</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>Failed to save the uploaded file.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Invalid file type. Please upload a valid CSV file.</p>";
                }
            } else {
                echo "<p style='color: red;'>Error uploading the file. Code: " . $_FILES['file']['error'] . "</p>";
            }
        }
        ?>
</div>
</body>
</html>
