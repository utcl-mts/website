<?php

session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

// Include the database connection file
include "../server/db_connect.php";
include "../server/navbar/student_profile.php";

?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Dashboard</title>
<div>

    <h1>View Student Details</h1>

    <div class="searchbar">
        <form method="GET" action="">
            <div class='text-element'>Enter either first name or last name</div>
            <div class='text-element-faded'>Example: Joe</div>
            <input
                    class="text_input"
                    id="search-input"
                    type="text"
                    name="student_name"
                    value="<?php echo htmlspecialchars(isset($_GET['student_name']) ? $_GET['student_name'] : ''); ?>"
            >
            <br><br>
            <button class="blue_submit" id="search-button" type="submit">Continue</button>
        </form>
    </div>

    <?php

    if (isset($_GET['student_name']) && !empty(trim($_GET['student_name']))) {
        $student_name = trim($_GET['student_name']);

        try {
            // SQL query to retrieve students matching the name
            $sql = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name, year 
                    FROM students 
                    WHERE CONCAT(first_name, ' ', last_name) LIKE :student_name";

            $stmt = $conn->prepare($sql);
            $search_param = '%' . $student_name . '%';
            $stmt->bindParam(':student_name', $search_param, PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div id='student_data'>";
            if ($results) {
                echo"<h2>Select student</h2>";
                echo "<form method='POST' action=''>";
                echo "<select name='selected_student' required>";
                echo "<option value=''>--Select a Student--</option>";
                foreach ($results as $row) {
                    $full_name = htmlspecialchars($row['full_name']);
                    $student_id = htmlspecialchars($row['student_id']);
                    echo "<option value='$student_id'>$full_name (Year: " . htmlspecialchars($row['year']) . ")</option>";
                }
                echo "</select>";
                echo "<br><br>";
                echo "<button class='blue_submit' type='submit' name='view_student'>View Student</button>";
                echo "</form>";
            } else {
                echo "<p>No records found for the given student name.</p>";
            }
            echo "</div>";

        } catch (PDOException $e) {
            die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
        }
    } else {
        echo "<p>Please enter a student name to search.</p>";
    }

    // Display selected student's data and medication records
    if (isset($_POST['view_student']) && !empty($_POST['selected_student'])) {
        $student_id = $_POST['selected_student'];

        try {
            $sql = "SELECT students.first_name, students.last_name, students.year, med.med_name, brand.brand_name, takes.current_dose, takes.exp_date
                    FROM students 
                    LEFT JOIN takes ON students.student_id = takes.student_id 
                    LEFT JOIN med ON takes.med_id = med.med_id 
                    LEFT JOIN brand ON takes.brand_id = brand.brand_id 
                    WHERE students.student_id = :student_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();

            $student_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($student_data)) {
                $full_name = htmlspecialchars($student_data[0]['first_name'] . ' ' . $student_data[0]['last_name']);
                $year = htmlspecialchars($student_data[0]['year']);
                echo "<h2>Details for $full_name (Year: $year)</h2>";
                echo "<table class='notification_table'>";
                echo "<tr>
                            <th class='notification_table_th'>Medication</th>
                            <th class='notification_table_th'>Brand</th>
                            <th class='notification_table_th'>Current Dose</th>
                            <th class='notification_table_th'>Expiry Date</th>
                    </tr>";
                foreach ($student_data as $row) {
                    echo "<tr>";
                    echo "<td class='notification_table_td'>" . htmlspecialchars($row['med_name'] ?? 'N/A') . "</td>";
                    echo "<td class='notification_table_td'>" . htmlspecialchars($row['brand_name'] ?? 'N/A') . "</td>";
                    echo "<td class='notification_table_td'>" . htmlspecialchars($row['current_dose'] ?? 'N/A') . "</td>";
                    echo "<td class='notification_table_td'>" . 
                        (isset($row['exp_date']) ? date('Y-m-d', htmlspecialchars($row['exp_date'])) : 'N/A') . 
                        "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<h2>No details available for the selected student.</h2>";
            }

        } catch (PDOException $e) {
            echo "<p class='error'>Failed to retrieve student data: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    ?>
</div>
</body>
