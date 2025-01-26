<?php
// Include the database connection file
include "../server/db_connect.php";
include "../server/navbar/student_management.php";
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<div>
    <br>

    <div>
    <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="student_table.php">View All Students</a></li>
                <li class="navbar_li"><a class='active' href="progress_students.php">Progress Students</a></li>
            </div>
        </ul>
    </div>
    <h1>Progress Student Year Group</h1>

    <div id="progress-year">
        <form method="GET" action="">
            <div class='text-element'>Enter year group</div>
            <div class='text-element-faded'>Example: 12</div>
            <input class="smaller_int_input" type="number" id="year" name="year" max="13" required>
            <br><br>
            <button class="submit" type="submit" name="progress">View Year Group</button>
        </form>
    </div>

    <?php
    // Check if the progress button is clicked
    if (isset($_GET['progress']) && isset($_GET['year'])) {
        $selected_year = trim($_GET['year']);

        try {
            // Fetch all students in the selected year group
            $sql = "SELECT student_id, first_name, last_name, year FROM students WHERE year = :year";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':year', $selected_year, PDO::PARAM_INT);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($students) {
                echo "<form method='POST' action=''>";
                echo "<h2>Progress Year Group $selected_year</h2>";
                echo "<table class='big_table'>";
                echo "<tr>";
                foreach (array_keys($students[0]) as $header) {
                    echo "<th class='big_table_th'>" . htmlspecialchars($header) . "</th>";
                }
                echo "<th class='big_table_th'>Action</th>";
                echo "</tr>";

                foreach ($students as $student) {
                    echo "<tr>";
                    foreach ($student as $key => $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }

                    // Add logic for Year 11 and Year 12 students
                    if ($student['year'] == 11 || $student['year'] == 12) {
                        echo "<td class='big_table_td'>
                                <div class='centered-form'>
                                    <label><input type='checkbox' name='progress_ids[]' value='" . htmlspecialchars($student['student_id']) . "' checked> Progress</label>
                                    <label><input type='checkbox' name='archive_ids[]' value='" . htmlspecialchars($student['student_id']) . "'> Archive</label>
                                </div>
                            </td>";
                    } elseif ($student['year'] == 13) {
                        echo "<td class='big_table_td'>
                                <div class='centered-form'>
                                    <input type='checkbox' name='archive_ids[]' value='" . htmlspecialchars($student['student_id']) . "'> Archive
                                </div>
                            </td>";
                    } else {
                        echo "<td class='big_table_td'>
                                <div class='centered-form'>
                                    <input type='checkbox' name='progress_ids[]' value='" . htmlspecialchars($student['student_id']) . "' checked>
                                </div>
                            </td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
                echo '<br><br>';
                echo "<button class='submit' type='submit' name='finalize_progress'>Finalise Progress</button>";
                echo "</form>";
            } else {
                echo "<p>No students found in Year $selected_year.</p>";
            }
        } catch (PDOException $e) {
            die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
        }
    }

    // Handle the final progress submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['finalize_progress'])) {
            // Handle year progression
            if (isset($_POST['progress_ids'])) {
                $progress_ids = $_POST['progress_ids'];

                try {
                    // Update the year group for selected students
                    $update_sql = "UPDATE students SET year = year + 1 WHERE student_id = :student_id";
                    $update_stmt = $conn->prepare($update_sql);

                    foreach ($progress_ids as $id) {
                        $update_stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
                        $update_stmt->execute();
                    }

                    echo "<p class='success'>Year group progression completed successfully.</p>";
                } catch (PDOException $e) {
                    die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
                }
            }

            // Handle archiving students
            if (isset($_POST['archive_ids'])) {
                $archive_ids = $_POST['archive_ids'];

                try {
                    // Archive the students by updating their status
                    $archive_sql = "UPDATE students SET archived = 1 WHERE student_id = :student_id";
                    $archive_stmt = $conn->prepare($archive_sql);

                    foreach ($archive_ids as $id) {
                        $archive_stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
                        $archive_stmt->execute();
                    }

                    echo "<p class='success'>Students archived successfully.</p>";
                } catch (PDOException $e) {
                    die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
                }
            }
        }
    }
    ?>
</div>
</body>
